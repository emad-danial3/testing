<?php

namespace App\Http\Repositories;


use App\Models\UserCommission;
use App\Models\UserMonthlyCommission;
use App\Models\OrderHasMonthlyCommission;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ICommissionRepository extends BaseRepository implements CommissionRepository
{
    public function __construct(UserCommission $model)
    {
        parent::__construct($model);
    }

    public function updateCommission($conditions, $data)
    {
        return UserCommission::where($conditions)->update($data);
    }

    public function CreateOrUpdateMonthlyCommission($user_id, $data, $date_from, $date_to)
    {
        if (!$date_from || !$date_to) {
            $start = Carbon::now()->startOfMonth()->toDateTimeString();
            $end = Carbon::now()->endOfMonth()->toDateTimeString();
        } else {
            $start = $date_from;
            $end = $date_to;
        }
        $comm = UserMonthlyCommission::where('user_id', $user_id)->whereBetween('created_at', [$start, $end])->first();
        if (!empty($comm)) {
            UserMonthlyCommission::where('user_id', $user_id)->whereBetween('created_at', [$start, $end])->update($data);
        } else {
            UserMonthlyCommission::create($data);
        }
        $comm = UserMonthlyCommission::where('user_id', $user_id)->whereBetween('created_at', [$start, $end])->first();
        return $comm;
    }

    public function CreateOrUpdateOrderHasMonthlyCommission($created_at,$commissionId, $order_id, $user_id, $level, $new, $value, $persintage,$total_order_has_commission)
    {
        $comm = OrderHasMonthlyCommission::where('commission_id', $commissionId)->where('order_id', $order_id)->where('user_id', $user_id)->first();
        $data = [
            'created_at' => $created_at,
            'commission_id' => $commissionId,
            'order_id' => $order_id,
            'user_id' => $user_id,
            'level' => $level,
            'new' => $new,
            'commission_percentage' => $persintage,
            'commission_value' => $value,
            'total_order_has_commission' => $total_order_has_commission
        ];
        if (!empty($comm)) {
            OrderHasMonthlyCommission::where('commission_id', $commissionId)->where('order_id', $order_id)->where('user_id', $user_id)->update($data);
        } else {
            OrderHasMonthlyCommission::create($data);
        }
        $comm = OrderHasMonthlyCommission::where('commission_id', $commissionId)->where('order_id', $order_id)->where('user_id', $user_id)->first();
        return $comm;
    }

    public function getMyCommission($user_id, $filterData)
    {

        $MyNetworkCommissions = UserCommission::where([
            'user_commissions.user_id' => $user_id,
            'user_commissions.is_paid' => 1,
        ])
            ->where('user_commissions.commission_by', '!=', $user_id)
            ->where('account_levels.parent_id', $user_id)
            ->join('users', 'users.id', 'user_commissions.commission_by')
            ->join('order_headers', 'order_headers.id', 'user_commissions.order_id')
            ->join('account_types', 'account_types.id', 'users.account_type')
            ->join('account_levels', 'account_levels.child_id', 'user_commissions.commission_by')
            ->select('users.full_name', 'users.account_id', 'account_types.name_en as name',
                'user_commissions.commission', 'user_commissions.is_redeemed', 'user_commissions.id', 'account_levels.level');
        if (isset($filterData['start_date']) && isset($filterData['end_date'])) {
            $MyNetworkCommissions->whereBetween(DB::raw('DATE(user_commissions.updated_at)'), [$filterData['start_date'], $filterData['end_date']]);
        }
        $MyNetworkCommissions = $MyNetworkCommissions->get();
        $MyCommissions = UserCommission::where([
            'user_commissions.user_id' => $user_id,
            'user_commissions.commission_by' => $user_id,
            'user_commissions.is_paid' => 1,
        ])
            ->join('users', 'users.id', 'user_commissions.user_id')
            ->join('order_headers', 'order_headers.id', 'user_commissions.order_id')
            ->join('account_types', 'account_types.id', 'users.account_type')
            ->select('users.full_name', 'users.account_id', 'account_types.name_en as name',
                'user_commissions.commission', 'user_commissions.is_redeemed', 'user_commissions.id');
        if (isset($filterData['start_date']) && isset($filterData['end_date'])) {
            $MyCommissions->whereBetween(DB::raw('DATE(user_commissions.updated_at)'), [$filterData['start_date'], $filterData['end_date']]);
        }
        $MyCommissions->addSelect(DB::raw("'0' as level"));
        $MyCommissions = $MyCommissions->get();


        return $MyCommissions->merge($MyNetworkCommissions);
    }

    public function myCurrentCommission($user_id, $period = 'month')
    {

        if ($period == 'quarter') {
            $start = Carbon::now()->firstOfQuarter()->toDateTimeString();
            $end = Carbon::now()->lastOfQuarter()->toDateTimeString();
        } elseif ($period == 'year') {
            $start = Carbon::now()->copy()->startOfYear()->toDateTimeString();
            $end = Carbon::now()->copy()->endOfYear()->toDateTimeString();
        } else {
            $start = Carbon::now()->startOfMonth()->toDateTimeString();
            $end = Carbon::now()->endOfMonth()->toDateTimeString();
        }
        $totalCommission = UserCommission::where('user_id', $user_id)->where('commission_type', $period)->whereBetween('created_at', [$start, $end])->sum('commission');
        return (float)$totalCommission;

    }

    public function getTotalCommissionAndRedeem($user_id)
    {
        if (request()->has('start_date') && request()->has('end_date')) {
            $total = UserCommission::where([
                'user_id' => $user_id,
                'is_paid' => 1,
            ])->select('commission')
                ->whereBetween(DB::raw('DATE(user_commissions.updated_at)'), [request()->input('start_date'), request()->input('end_date')])
                ->sum('commission');

            $redeemed = UserCommission::where([
                'user_id' => $user_id,
                'is_paid' => 1,
                'is_redeemed' => 1
            ])->select('commission')
                ->whereBetween(DB::raw('DATE(user_commissions.updated_at)'), [request()->input('start_date'), request()->input('end_date')])
                ->sum('commission');
            $balance = $total - $redeemed;
        } else {
            $total = UserCommission::where([
                'user_id' => $user_id,
                'is_paid' => 1,
            ])->select('commission')->sum('commission');

            $redeemed = UserCommission::where([
                'user_id' => $user_id,
                'is_paid' => 1,
                'is_redeemed' => 1
            ])->select('commission')->sum('commission');
            $balance = $total - $redeemed;
        }
        return [
            "total_commissions" => $total,
            "total_redeemed_commissions" => $redeemed,
            "total_balance_commissions" => $balance,
        ];
    }

    public function getAllData($inputData)
    {
        $data = UserCommission::orderBy('user_commissions.updated_at', 'desc');

        if (isset($inputData['name']) && $inputData['name'] != '') {
            $data->join('users', 'user_commissions.user_id', 'users.id')
                ->where('users.phone', $inputData['name']);
//            $data->where('order_id', $inputData['name']);
        }
        if (isset($inputData['user'])) {
            $data->where('user_commissions.user_id', $inputData['user']);
        }
        if (isset($inputData['type'])) {
            $data->where('user_commissions.is_redeemed', $inputData['type']);
        }
        if (isset($inputData['from_date']) && isset($inputData['to_date'])) {
            $data->whereBetween('user_commissions.created_at', [$inputData['from_date'], $inputData['to_date']]);
        }

        return $data->paginate($this->defaultLimit);
    }

    public function getAllDataMonthly($inputData)
    {
        $data = UserMonthlyCommission::orderBy('user_monthly_commissions.updated_at', 'desc');

        if (isset($inputData['name']) && $inputData['name'] != '') {
            $data->join('users', 'user_monthly_commissions.user_id', 'users.id')
                ->where('users.phone', $inputData['name']);
//            $data->where('order_id', $inputData['name']);
        }
        if (isset($inputData['user'])) {
            $data->where('user_monthly_commissions.user_id', $inputData['user']);
        }
        if (isset($inputData['type'])) {
            $data->where('user_monthly_commissions.is_redeemed', $inputData['type']);
        }
        if (isset($inputData['from_date']) && isset($inputData['to_date'])) {
            $data->whereBetween('user_monthly_commissions.created_at', [$inputData['from_date'], $inputData['to_date']]);
        }
        if (isset($inputData['finance'])) {
            $data->where('user_monthly_commissions.personal_order', '1')->where('user_monthly_commissions.total_earnings', '>', 1)->whereNotIn('user_monthly_commissions.user_id', [1, 2]);
        }

        return $data->paginate($this->defaultLimit);
    }

    public function getAllCommissionOrders($inputData)
    {
        $data = OrderHasMonthlyCommission::where('order_has_monthly_commissions.commission_value','>=',0)->orderBy('order_has_monthly_commissions.updated_at', 'desc')
            ->groupBy('order_has_monthly_commissions.user_id')->select('order_has_monthly_commissions.created_at','order_has_monthly_commissions.commission_id','order_has_monthly_commissions.user_id','order_has_monthly_commissions.level','order_has_monthly_commissions.new','order_has_monthly_commissions.commission_percentage',DB::raw('SUM(order_has_monthly_commissions.total_order_has_commission) as total_order_has_commission'),DB::raw('SUM(order_has_monthly_commissions.commission_value) as commission_value'));
        if (isset($inputData['name']) && $inputData['name'] != '') {
            $data->whereHas('user', function ($query) use ($inputData) {
                return $query->where('users.phone', 'like', '%' . $inputData['name'] . '%');
            });
        }
        if (isset($inputData['commission_id'])) {
            $data->where('order_has_monthly_commissions.commission_id', $inputData['commission_id']);
        }
        if (isset($inputData['order_id'])) {
            $data->where('order_has_monthly_commissions.order_id', $inputData['order_id']);
        }
        if (isset($inputData['user'])) {
            $data->where('order_has_monthly_commissions.user_id', $inputData['user']);
        }
        if (isset($inputData['from_date']) && isset($inputData['to_date'])) {
            $data->whereBetween('order_has_monthly_commissions.created_at', [$inputData['from_date'], $inputData['to_date']]);
        }
        return $data->paginate($this->defaultLimit);
    }


    public function getOneCommission($id)
    {
        $commission = UserMonthlyCommission::find($id);
        return $commission;
    }


}
