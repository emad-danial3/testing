<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CommissionsExport;
use App\Exports\MonthCommissionsExport;
use App\Exports\MonthCommissionsDetailsExport;
use App\Http\Requests\CommissionsExportRequest;
use App\Http\Requests\ImportCommissionsRequest;
use App\Http\Services\CommissionService;
use App\Http\Services\UserService;
use App\Imports\MonthCommissionsImport;
use App\Models\AccountLevel;
use App\Models\OrderHasMonthlyCommission;
use App\Models\UserCommission;
use App\Models\UserMonthlyCommission;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class MonthlyCommissionController extends HomeController
{

    private $CommissionService;
    protected $UserService;
    private $lsattestfromdate;
    private $lsattesttodate;

    public function __construct(CommissionService $CommissionService, UserService $UserService)
    {
        $this->CommissionService = $CommissionService;
        $this->UserService = $UserService;
        $this->lsattestfromdate = null;
        $this->lsattesttodate = null;
    }

    public function index()
    {
        $data = $this->CommissionService->getAllMonthly(request()->all());
        //change this
        return view('AdminPanel.PagesContent.MonthCommissions.index')->with('commissions', $data);
    }

    public function financecommissionreport()
    {
        $filters = request()->all();
        $filters['finance'] = 1;
        $data = $this->CommissionService->getAllMonthly($filters);
        //change this
        return view('AdminPanel.PagesContent.MonthCommissions.financecommissionreport')->with('commissions', $data);
    }
    public function finandetailscecommission()
    {
        $filters = request()->all();
        $data = $this->CommissionService->getAllCommissionOrders($filters);
        //change this
        return view('AdminPanel.PagesContent.MonthCommissions.finandetailscecommission')->with('commissions', $data);
    }

    public function financecommissionview($id)
    {
        $commition = $this->CommissionService->getOneCommission($id);

        if (!empty($commition)) {

            $this->lsattestfromdate = Carbon::parse($commition->created_at)->startOfMonth()->toDateTimeString();
            $this->lsattesttodate = Carbon::parse($commition->created_at)->endOfMonth()->toDateTimeString();

            $currentuser = $this->UserService->getUser($commition->user_id);
            $user_id = $currentuser->id;
            $userIsActiveInCurrentMonth = $this->UserService->userIsActiveInCurrentMonth($user_id, $this->lsattestfromdate, $this->lsattesttodate);
         $data=null;
            if (!empty($userIsActiveInCurrentMonth)) {
                $mytotalPaidOrderThisManth = $userIsActiveInCurrentMonth['total'];
                $getMyTeamG1 = $this->UserService->getMyTeamGeneration($user_id, $this->lsattestfromdate, $this->lsattesttodate, 1);
                array_push($getMyTeamG1, $user_id);
                $getMyTeamG2 = $this->UserService->getMyTeamGeneration($user_id, $this->lsattestfromdate, $this->lsattesttodate, 2);
                $myTeamMembers = array_merge($getMyTeamG1, $getMyTeamG2);
                $myTeamMembersActives = $this->getMyTeamMembersActives($myTeamMembers);
                $myTeamMembersActivesG1 = $this->getMyTeamMembersActives($getMyTeamG1);
                $myTeamMembersActivesG2 = $this->getMyTeamMembersActives($getMyTeamG2);
                $myTeamMembersActivesCount = count($myTeamMembersActives);
                $getMyNewMembers = $this->UserService->getMyTeamGeneration($user_id, $this->lsattestfromdate, $this->lsattesttodate, 1, true);

                if ($userIsActiveInCurrentMonth['new'] > 0)
                    array_push($getMyNewMembers, $user_id);
                $myNewMembersActivesCount = $this->getMyNewMembersActivesCount($getMyNewMembers);
                $myTeamTotalSales = $this->UserService->getMyTeamTotalSales($myTeamMembersActives, $this->lsattestfromdate, $this->lsattesttodate);
                $myTeamTotalSalesG1 = $this->UserService->getMyTeamTotalSales($myTeamMembersActivesG1, $this->lsattestfromdate, $this->lsattesttodate);
                $myTeamTotalSalesG2 = $this->UserService->getMyTeamTotalSales($myTeamMembersActivesG2, $this->lsattestfromdate, $this->lsattesttodate);
                $myNewMembersSales = $this->UserService->getMyTeamTotalSales($getMyNewMembers, $this->lsattestfromdate, $this->lsattesttodate);
                $mySalesLeaderLevel = $this->UserService->getMySalesLeaderLevel($myTeamMembersActivesCount, $myNewMembersActivesCount, $myTeamTotalSales);
                $myMonthlyEarnings = $this->getTotalMonthlyEarnings($mySalesLeaderLevel, $currentuser->sales_leaders_level_id, $myNewMembersSales, $myTeamTotalSalesG1, $myTeamTotalSalesG2);
                $getMyTeamDataG1 = $this->UserService->getMyTeamDataAndTotalSales($myTeamMembersActivesG1, $this->lsattestfromdate, $this->lsattesttodate);
                $getMyTeamDataG2 = $this->UserService->getMyTeamDataAndTotalSales($myTeamMembersActivesG2, $this->lsattestfromdate, $this->lsattesttodate);


                $data = [
                    "id" => $id,
                    "is_paid" => $commition->is_paid,
                    "created_at" => $commition->created_at,
                    "user_name" => $currentuser->full_name,
                    "total_personal" => $mytotalPaidOrderThisManth,
                    "user_id" => $user_id,
                    "personal_order" => $mytotalPaidOrderThisManth >= 250 ? '1' : 0,
                    "active_team" => $myTeamMembersActivesCount,
                    "g1_new" => $myNewMembersActivesCount,
                    "g1_new_sales" => $myNewMembersSales,
                    "total_g1_sales" => $myTeamTotalSalesG1,
                    "total_g2_sales" => $myTeamTotalSalesG2,
                    "total_sales_po_g1_g2" => $myTeamTotalSales,
                    "upLevel" => isset($myMonthlyEarnings) && isset($myMonthlyEarnings['upLevel']) && $myMonthlyEarnings['upLevel'] > 0 ? '1' : '0',
                    "e_g1_new_sales" => isset($myMonthlyEarnings) && isset($myMonthlyEarnings['earnFromNewMembersSales']) ? round($myMonthlyEarnings['earnFromNewMembersSales'], 2) : 0,
                    "e_total_g1_sales" => isset($myMonthlyEarnings) && isset($myMonthlyEarnings['earnFromMembersSalesG1']) ? round($myMonthlyEarnings['earnFromMembersSalesG1'], 2) : 0,
                    "e_total_g2_sales" => isset($myMonthlyEarnings) && isset($myMonthlyEarnings['earnFromMembersSalesG2']) ? round($myMonthlyEarnings['earnFromMembersSalesG2'], 2) : 0,
                    "e_upLevel" => isset($myMonthlyEarnings) && isset($myMonthlyEarnings['upLevel']) ? $myMonthlyEarnings['upLevel'] : 0,
                    "total_earnings" => isset($myMonthlyEarnings) && isset($myMonthlyEarnings['total']) ? round($myMonthlyEarnings['total'], 2) : 0,
                    "salesLeaderLevelId" => $mySalesLeaderLevel ? $mySalesLeaderLevel->id : 0,
                    "previousSalesLeaderLevelId" => $currentuser->sales_leaders_level_id,
                    "getMyTeamDataG1" => $getMyTeamDataG1,
                    "getMyTeamDataG2" => $getMyTeamDataG2,
                    "getMyNewMembers" => $getMyNewMembers,
                ];
            }
        }

        return view('AdminPanel.PagesContent.MonthCommissions.view')->with('commission', $data);
    }


    public function getMySalesLeaderLevel($user_id, $sales_leaders_level_id)
    {
        $userIsActiveInCurrentMonth = $this->UserService->userIsActiveInCurrentMonth($user_id, $this->lsattestfromdate, $this->lsattesttodate);
        if ($userIsActiveInCurrentMonth) {
            $getMyTeamG1 = $this->UserService->getMyTeamGeneration($user_id, $this->lsattestfromdate, $this->lsattesttodate, 1);
            array_push($getMyTeamG1, $user_id);
            $getMyTeamG2 = $this->UserService->getMyTeamGeneration($user_id, $this->lsattestfromdate, $this->lsattesttodate, 2);
            $myTeamMembers = array_merge($getMyTeamG1, $getMyTeamG2);
            $myTeamMembersActives = $this->getMyTeamMembersActives($myTeamMembers);
            $myTeamMembersNotActives = $this->getMyTeamMembersNotActives($myTeamMembers);
            $myTeamMembersActivesWithSales = $this->UserService->getUsersActiveSalesTeam($myTeamMembersActives);
            $myTeamMembersNotActivesWithSales = $this->UserService->getUsersNotActiveSalesTeam($myTeamMembersNotActives);
            $myTeamMembersActivesG1 = $this->getMyTeamMembersActives($getMyTeamG1);
            $myTeamMembersActivesG2 = $this->getMyTeamMembersActives($getMyTeamG2);
            $myTeamMembersActivesCount = count($myTeamMembersActives);
            $getMyNewMembers = $this->UserService->getMyTeamGeneration($user_id, $this->lsattestfromdate, $this->lsattesttodate, 1, true);

            if ($userIsActiveInCurrentMonth['new'] > 0)
                array_push($getMyNewMembers, $user_id);

            $myNewMembersActivesCount = $this->getMyNewMembersActivesCount($getMyNewMembers);
            $myTeamTotalSales = $this->UserService->getMyTeamTotalSales($myTeamMembersActives, $this->lsattestfromdate, $this->lsattesttodate);
            $myTeamTotalSalesG1 = $this->UserService->getMyTeamTotalSales($myTeamMembersActivesG1, $this->lsattestfromdate, $this->lsattesttodate);
            $myTeamTotalSalesG2 = $this->UserService->getMyTeamTotalSales($myTeamMembersActivesG2, $this->lsattestfromdate, $this->lsattesttodate);
            $myNewMembersSales = $this->UserService->getMyTeamTotalSales($getMyNewMembers, $this->lsattestfromdate, $this->lsattesttodate);
            $mySalesLeaderLevel = $this->UserService->getMySalesLeaderLevel($myTeamMembersActivesCount, $myNewMembersActivesCount, $myTeamTotalSales);
            $myMonthlyEarnings = $this->getTotalMonthlyEarnings($mySalesLeaderLevel, $sales_leaders_level_id, $myNewMembersSales, $myTeamTotalSalesG1, $myTeamTotalSalesG2);

            $data = [
                "level" => $mySalesLeaderLevel,
                "myTeamTotalSales" => $myTeamTotalSales,
                "totalSalesG1" => $myTeamTotalSalesG1,
                "totalSalesG2" => $myTeamTotalSalesG2,
                "myTeamMembersActivesCount" => $myTeamMembersActivesCount,
                "myTeamMembersActivesWithSales" => $myTeamMembersActivesWithSales,
                "myTeamMembersNotActivesWithSales" => $myTeamMembersNotActivesWithSales,
                "myNewMembersActivesCount" => $myNewMembersActivesCount,
                "myNewMembersSales" => $myNewMembersSales,
                "myTeamG1Count" => $getMyTeamG1,
                "myTeamG2Count" => $getMyTeamG2,
                "myMonthlyEarnings" => $myMonthlyEarnings,
            ];
            return $data;
        } else {
            return null;
        }
    }

    public function getMyNewMembersActivesCount($newTeam)
    {
        $count = 0;
        foreach ($newTeam as $member_id) {
            $data = $this->UserService->userIsActiveInCurrentMonth($member_id, $this->lsattestfromdate, $this->lsattesttodate);
            if ($data['active'] == true) {
                $count++;
            }
        }
        return $count;
    }

    public function getMyTeamMembersActives($Team)
    {

        $activeMembers = [];
        foreach ($Team as $member_id) {
            $data = $this->UserService->userIsActiveInCurrentMonth($member_id, $this->lsattestfromdate, $this->lsattesttodate);
            if ($data['active'] == true) {
                $activeMembers[] = $member_id;
            }
        }
        return $activeMembers;
    }

    public function getMyTeamMembersNotActives($Team)
    {
        $notActiveMembers = [];
        foreach ($Team as $member_id) {
            $data = $this->UserService->userIsActiveInCurrentMonth($member_id, $this->lsattestfromdate, $this->lsattesttodate);
            if ($data['active'] == true) {
            } else {
                $notActiveMembers[] = $member_id;
            }
        }
        return $notActiveMembers;
    }

    public function getTotalMonthlyEarnings($mySalesLeaderLevel, $sales_leaders_level_id, $myNewMembersSales, $myTeamTotalSalesG1, $myTeamTotalSalesG2)
    {
        if (!empty($mySalesLeaderLevel)) {
            $data['upLevel'] = $mySalesLeaderLevel->id > $sales_leaders_level_id ? $mySalesLeaderLevel->life_time_bonus : 0;
            $data['earnFromNewMembersSales'] = (float)$myNewMembersSales * ($mySalesLeaderLevel->spons_b_new_r / 100);
            $data['earnFromMembersSalesG1'] = (float)$myTeamTotalSalesG1 * ($mySalesLeaderLevel->g1_bonus / 100);
            $data['earnFromMembersSalesG2'] = (float)$myTeamTotalSalesG2 * ($mySalesLeaderLevel->g2_bonus / 100);
            $data['spons_b_new_r'] = ($mySalesLeaderLevel->spons_b_new_r / 100);
            $data['g1_bonus'] = ($mySalesLeaderLevel->g1_bonus / 100);
            $data['g2_bonus'] = ($mySalesLeaderLevel->g2_bonus / 100);
            $data['total'] = $data['upLevel'] + $data['earnFromNewMembersSales'] + $data['earnFromMembersSalesG1'] + $data['earnFromMembersSalesG2'];
            return $data;
        }

        return null;
    }


    public function ExportCommissionsSheet(CommissionsExportRequest $request)
    {

        $validated = $request->validated();
        $validated['start_date'] = Carbon::parse($validated['start_date'])->startOfDay()->toDateTimeString();
        $validated['end_date'] = Carbon::parse($validated['end_date'])->endOfDay()->toDateTimeString();
        $data = UserMonthlyCommission::where('created_at', '>', $validated['start_date'])
            ->where('created_at', '<', $validated['end_date'])->where('is_paid', $request->type ?? 1);
            if(isset($request->finance) &&$request->finance==1){
                $data->where('personal_order', '1')->where('total_earnings', '>', 1);
            }
        $data=$data->orderBy('updated_at', 'desc')->get();
            

        try {
            return Excel::download(new MonthCommissionsExport($validated['start_date'], $validated['end_date'], $data), 'monthcommissions.csv');
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => 'Commissions Error in Import']);
        }
    }
    public function ExportCommissionsSheetFOR(CommissionsExportRequest $request)
    {

        $validated = $request->validated();
        $validated['start_date'] = Carbon::parse($validated['start_date'])->startOfDay()->toDateTimeString();
        $validated['end_date'] = Carbon::parse($validated['end_date'])->endOfDay()->toDateTimeString();

        $data = OrderHasMonthlyCommission::where('order_has_monthly_commissions.commission_value','>=',0)
            ->where('order_has_monthly_commissions.created_at', '>', $validated['start_date'])
                ->where('order_has_monthly_commissions.created_at', '<', $validated['end_date'])
            ->orderBy('order_has_monthly_commissions.commission_id', 'asc')
            ->groupBy('order_has_monthly_commissions.user_id','order_has_monthly_commissions.commission_id')->select('order_has_monthly_commissions.created_at','order_has_monthly_commissions.commission_id','order_has_monthly_commissions.user_id','order_has_monthly_commissions.level','order_has_monthly_commissions.new','order_has_monthly_commissions.commission_percentage',DB::raw('SUM(order_has_monthly_commissions.total_order_has_commission) as total_order_has_commission'),DB::raw('SUM(order_has_monthly_commissions.commission_value) as commission_value'))->get();

        try {
            return Excel::download(new MonthCommissionsDetailsExport($validated['start_date'], $validated['end_date'], $data), 'monthcommissionsTree.csv');
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => 'Commissions Error in Import']);
        }
    }

    public function importCommissionsSheet(ImportCommissionsRequest $request)
    {
        $validated = $request->validated();
        try {
            Excel::import(new MonthCommissionsImport(), request()->file('file'), \Maatwebsite\Excel\Excel::XLSX);
            return redirect()->back()->with('message', 'Commissions Updated Successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => $exception->getMessage()]);
        }
    }
}
