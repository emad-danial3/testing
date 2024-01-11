<?php

namespace App\Http\Controllers\Admin;


use App\Constants\OrderTypes;
use App\Models\Product;
use App\Exports\OrdersExport;
use App\Exports\SalesReportSheetExport;
use App\Http\Repositories\IUserRepository;
use App\Http\Requests\ExportShippingSheetSheetRequest;
use App\Http\Services\CartService;
use App\Http\Services\CommissionService;
use App\Http\Services\OrderService;
use App\Http\Services\PaidOrderActions\SingleOrderPaidActions;
use App\Http\Services\UserService;
use App\Models\OrderHeader;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Services\OrderLinesService;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Repositories\IUserWalletRepository;

use Auth;


class ReportsController extends HomeController
{
    private   $OrderHeaderService;
    protected $CartService;
    protected $OrderRequest;
    protected $UserService;

    public function __construct(OrderService $OrderHeaderService, IUserRepository $UserRepository, OrderLinesService $OrderLinesService, Request $OrderRequest, CartService $CartService, UserService $UserService)
    {
        $this->OrderHeaderService = $OrderHeaderService;
        $this->OrderRequest       = $OrderRequest;
        $this->CartService        = $CartService;
        $this->UserRepository     = $UserRepository;
        $this->UserService        = $UserService;
        $this->OrderLinesService  = $OrderLinesService;

    }
   
    public function index()
    {
        $data = $this->OrderHeaderService->getAll(request()->all());
        return view('AdminPanel.PagesContent.OrderHeaders.index')->with('orderHeaders', $data);
    }



    public function report(Request $request)
    {

        $type              = $request->type;
        $date_to           = Carbon::parse($request->date_to)->endOfDay()->toDateTimeString();
        $date_from         = Carbon::parse($request->date_from)->startOfDay()->toDateTimeString();
        $usersCount        = 0;
        $ordersSalesWeb    = 0;
        $ordersSalesAdmin  = 0;
        $ordersSalesonLine = 0;
        $ordersSalesmobile = 0;
        $ordersSalesTotal  = 0;
        $totalcount  = 0;
        $ordersSalesTotalsWebCount  = 0;
        $ordersSalesTotalsmobileCount  = 0;
        $ordersSalesTotalsAdminCount  = 0;
        $ordersSalesTotalsonLineCount  = 0;
        $ordersSalesWebre    = 0;
        $ordersSalesAdminre  = 0;
        $ordersSalesonLinere = 0;
        $ordersSalesmobilere = 0;
        $ordersSalesTotalre  = 0;
        $totalcountre  = 0;
        $ordersSalesTotalsWebCountre  = 0;
        $ordersSalesTotalsmobileCountre  = 0;
        $ordersSalesTotalsAdminCountre  = 0;
        $ordersSalesTotalsonLineCountre  = 0;
        $ordersSalesWebca    = 0;
        $ordersSalesAdminca = 0;
        $ordersSalesonLineca = 0;
        $ordersSalesmobileca = 0;
        $ordersSalesTotalca  = 0;
        $totalcountca  = 0;
        $ordersSalesTotalsWebCountca  = 0;
        $ordersSalesTotalsmobileCountca  = 0;
        $ordersSalesTotalsAdminCountca  = 0;
        $ordersSalesTotalsonLineCountca  = 0;

        if ($type == 'order') {
            if ((isset($date_to) && $date_to != '') && (isset($date_from) && $date_from != '')) {

                $ordersSalesTotal  = DB::table('order_headers')
                    ->where('payment_status', 'PAID')
                    ->where('order_status', '!=','Cancelled')
                    ->whereIn('platform', ['web', 'admin', 'onLine', 'mobile'])
                    ->whereBetween('created_at', [$date_from, $date_to])->sum('total_order');
                $totalcount  = DB::table('order_headers')
                    ->where('payment_status', 'PAID')
                    ->where('order_status', '!=','Cancelled')
                    ->whereIn('platform', ['web', 'admin', 'onLine', 'mobile'])
                    ->whereBetween('created_at', [$date_from, $date_to])->count('id');

                $ordersSalesWeb    = DB::table('order_headers')
                    ->where('payment_status', 'PAID')
                    ->where('order_status', '!=','Cancelled')
                    ->where('platform', 'web')
                    ->whereBetween('created_at', [$date_from, $date_to])->sum('total_order');
                $ordersSalesTotalsWebCount    = DB::table('order_headers')
                    ->where('payment_status', 'PAID')
                    ->where('order_status', '!=','Cancelled')
                    ->where('platform', 'web')
                    ->whereBetween('created_at', [$date_from, $date_to])->count('id');
                $ordersSalesAdmin  = DB::table('order_headers')
                    ->where('payment_status', 'PAID')
                    ->where('order_status', '!=','Cancelled')
                    ->where('platform', 'admin')
                    ->whereBetween('created_at', [$date_from, $date_to])->sum('total_order');
                $ordersSalesTotalsAdminCount  = DB::table('order_headers')
                    ->where('payment_status', 'PAID')
                    ->where('order_status', '!=','Cancelled')
                    ->where('platform', 'admin')
                    ->whereBetween('created_at', [$date_from, $date_to])->count('id');
                $ordersSalesonLine = DB::table('order_headers')
                    ->where('payment_status', 'PAID')
                    ->where('order_status', '!=','Cancelled')
                    ->where('platform', 'onLine')
                    ->whereBetween('created_at', [$date_from, $date_to])->sum('total_order');
                $ordersSalesTotalsonLineCount = DB::table('order_headers')
                    ->where('payment_status', 'PAID')
                    ->where('order_status', '!=','Cancelled')
                    ->where('platform', 'onLine')
                    ->whereBetween('created_at', [$date_from, $date_to])->count('id');
                $ordersSalesmobile = DB::table('order_headers')
                    ->where('payment_status', 'PAID')
                    ->where('order_status', '!=','Cancelled')
                    ->where('platform', 'mobile')
                    ->whereBetween('created_at', [$date_from, $date_to])->sum('total_order');
                $ordersSalesTotalsmobileCount = DB::table('order_headers')
                    ->where('payment_status', 'PAID')
                    ->where('order_status', '!=','Cancelled')
                    ->where('platform', 'mobile')
                    ->whereBetween('created_at', [$date_from, $date_to])->count('id');
                ///
         /// registered
                $ordersSalesTotalre  = DB::table('order_headers')
                    ->where(function ($query) {
                        $query->where([['platform','=','admin'],['payment_status','=','PAID']])
                            ->orWhere([['platform','!=','admin'],['send_t_o','=','1']]);
                    })
                    ->where('order_status', '!=','Cancelled')
                    ->whereIn('platform', ['web', 'admin', 'onLine', 'mobile'])
                    ->whereBetween('created_at', [$date_from, $date_to])->sum('total_order');
                $totalcountre  = DB::table('order_headers')
                    ->where(function ($query) {
                        $query->where([['platform','=','admin'],['payment_status','=','PAID']])
                            ->orWhere([['platform','!=','admin'],['send_t_o','=','1']]);
                    })
                    ->where('order_status', '!=','Cancelled')
                    ->whereIn('platform', ['web', 'admin', 'onLine', 'mobile'])
                    ->whereBetween('created_at', [$date_from, $date_to])->count('id');

                $ordersSalesWebre    = DB::table('order_headers')
                    ->where('send_t_o', '1')
                    ->where('order_status', '!=','Cancelled')
                    ->where('platform', 'web')
                    ->whereBetween('created_at', [$date_from, $date_to])->sum('total_order');
                $ordersSalesTotalsWebCountre    = DB::table('order_headers')
                    ->where('send_t_o', '1')
                    ->where('order_status', '!=','Cancelled')
                    ->where('platform', 'web')
                    ->whereBetween('created_at', [$date_from, $date_to])->count('id');
                $ordersSalesAdminre  = DB::table('order_headers')
                    ->where('payment_status', 'PAID')
                    ->where('order_status', '!=','Cancelled')
                    ->where('platform', 'admin')
                    ->whereBetween('created_at', [$date_from, $date_to])->sum('total_order');
                $ordersSalesTotalsAdminCountre  = DB::table('order_headers')
                    ->where('payment_status', 'PAID')
                    ->where('order_status', '!=','Cancelled')
                    ->where('platform', 'admin')
                    ->whereBetween('created_at', [$date_from, $date_to])->count('id');
                $ordersSalesonLinere = DB::table('order_headers')
                    ->where('send_t_o', '1')
                    ->where('order_status', '!=','Cancelled')
                    ->where('platform', 'onLine')
                    ->whereBetween('created_at', [$date_from, $date_to])->sum('total_order');
                $ordersSalesTotalsonLineCountre = DB::table('order_headers')
                    ->where('send_t_o', '1')
                    ->where('order_status', '!=','Cancelled')
                    ->where('platform', 'onLine')
                    ->whereBetween('created_at', [$date_from, $date_to])->count('id');
                $ordersSalesmobilere = DB::table('order_headers')
                    ->where('send_t_o', '1')
                    ->where('order_status', '!=','Cancelled')
                    ->where('platform', 'mobile')
                    ->whereBetween('created_at', [$date_from, $date_to])->sum('total_order');
                $ordersSalesTotalsmobileCountre = DB::table('order_headers')
                    ->where('send_t_o', '1')
                    ->where('order_status', '!=','Cancelled')
                    ->where('platform', 'mobile')
                    ->whereBetween('created_at', [$date_from, $date_to])->count('id');
                /// cancelled sales
                $ordersSalesTotalca  = DB::table('order_headers')
                    ->where('order_status', '=','Cancelled')
                    ->whereIn('platform', ['web', 'admin', 'onLine', 'mobile'])
                    ->whereBetween('created_at', [$date_from, $date_to])->sum('total_order');
                $totalcountca  = DB::table('order_headers')
                    ->where('order_status', '=','Cancelled')
                    ->whereIn('platform', ['web', 'admin', 'onLine', 'mobile'])
                    ->whereBetween('created_at', [$date_from, $date_to])->count('id');

                $ordersSalesWebca    = DB::table('order_headers')
                    ->where('order_status', '=','Cancelled')
                    ->where('platform', 'web')
                    ->whereBetween('created_at', [$date_from, $date_to])->sum('total_order');
                $ordersSalesTotalsWebCountca    = DB::table('order_headers')
                    ->where('order_status', '=','Cancelled')
                    ->where('platform', 'web')
                    ->whereBetween('created_at', [$date_from, $date_to])->count('id');
                $ordersSalesAdminca  = DB::table('order_headers')
                    ->where('order_status', '=','Cancelled')
                    ->where('platform', 'admin')
                    ->whereBetween('created_at', [$date_from, $date_to])->sum('total_order');
                $ordersSalesTotalsAdminCountca  = DB::table('order_headers')
                    ->where('order_status', '=','Cancelled')
                    ->where('platform', 'admin')
                    ->whereBetween('created_at', [$date_from, $date_to])->count('id');
                $ordersSalesonLineca = DB::table('order_headers')
                    ->where('order_status', '=','Cancelled')
                    ->where('platform', 'onLine')
                    ->whereBetween('created_at', [$date_from, $date_to])->sum('total_order');
                $ordersSalesTotalsonLineCountca = DB::table('order_headers')
                    ->where('order_status', '=','Cancelled')
                    ->where('platform', 'onLine')
                    ->whereBetween('created_at', [$date_from, $date_to])->count('id');
                $ordersSalesmobileca = DB::table('order_headers')
                    ->where('order_status', '=','Cancelled')
                    ->where('platform', 'mobile')
                    ->whereBetween('created_at', [$date_from, $date_to])->sum('total_order');
                $ordersSalesTotalsmobileCountca = DB::table('order_headers')
                    ->where('order_status', '=','Cancelled')
                    ->where('platform', 'mobile')
                    ->whereBetween('created_at', [$date_from, $date_to])->count('id');
            }
        }
        elseif ($type == 'user') {
            if ((isset($date_to) && $date_to != '') && (isset($date_from) && $date_from != '')) {
                $usersCount = User::where('id', '>', 0);
                if ((isset($date_to) && $date_to != '') && (isset($date_from) && $date_from != '')) {
                    $usersCount = $usersCount->whereBetween('created_at', [$date_from, $date_to]);
                }
                $usersCount = $usersCount->count('id');
            }
        }
        return view('AdminPanel.PagesContent.Reports.report', compact('ordersSalesTotal', 'usersCount', 'date_to', 'date_from', 'type', 'ordersSalesWeb', 'ordersSalesAdmin', 'ordersSalesonLine', 'ordersSalesmobile','totalcount','ordersSalesTotalsWebCount','ordersSalesTotalsAdminCount','ordersSalesTotalsonLineCount','ordersSalesTotalsmobileCount', 'ordersSalesWebre', 'ordersSalesAdminre', 'ordersSalesonLinere', 'ordersSalesmobilere','totalcountre','ordersSalesTotalsWebCountre','ordersSalesTotalsAdminCountre','ordersSalesTotalsonLineCountre','ordersSalesTotalsmobileCountre','ordersSalesTotalre', 'ordersSalesWebca', 'ordersSalesAdminca', 'ordersSalesonLineca', 'ordersSalesmobileca','totalcountca','ordersSalesTotalsWebCountca','ordersSalesTotalsAdminCountca','ordersSalesTotalsonLineCountca','ordersSalesTotalsmobileCountca','ordersSalesTotalca'));
    }


    public function reports(Request $request)
    {

        $date_to   = $request->date_to;
        $date_from = $request->date_from;

        $productsReport = DB::table('order_headers')
            ->leftJoin('order_lines', 'order_headers.id', '=', 'order_lines.order_id')
            ->leftJoin('products', 'products.id', '=', 'order_lines.product_id')
            ->select('order_headers.id', 'order_lines.product_id', DB::raw("sum(order_lines.quantity) AS  total_quantity"), DB::raw("sum(order_lines.price*order_lines.quantity) AS  total_sales"), 'products.oracle_short_code', 'products.name_en', 'products.full_name', 'order_headers.created_at')
            ->where('order_headers.id', '>', 0)
            ->whereNotNull('order_lines.product_id');
        if ((isset($date_to) && $date_to != '') && (isset($date_from) && $date_from != '')) {
            $productsReport = $productsReport->whereBetween('order_headers.created_at', [$date_from, $date_to]);
        }
        $productsReport = $productsReport->groupBy('order_lines.product_id')
            ->orderBy('total_quantity', 'desc')
            ->paginate(30);
        return view('AdminPanel.PagesContent.Reports.reports', compact('productsReport', 'date_to', 'date_from'));
    }

    public function active_members(Request $request)
    {
        $date_from = $request->date_from;
        $date_to   = $request->date_to;
        if (!$date_to || !$date_from) {
            $date_from = Carbon::now()->startOfMonth()->toDateTimeString();
            $date_to   = Carbon::now()->endOfMonth()->toDateTimeString();
        }
        $productsReport = DB::table('users')
            ->leftJoin('order_headers', 'order_headers.user_id', '=', 'users.id')
            ->select('users.id', 'users.phone', 'users.email', 'users.full_name', 'order_headers.created_at', DB::raw("(SELECT SUM(order_headers.total_order) FROM order_headers WHERE order_headers.user_id = users.id AND order_headers.payment_status = 'PAID' AND   order_headers.created_at BETWEEN '{$date_from}' AND '{$date_to}'  ) AS  total_sales"))
            ->where('order_headers.id', '>', 0)
            ->groupBy('users.id')
            ->having('total_sales', '>', 250);
        if ((isset($date_to) && $date_to != '') && (isset($date_from) && $date_from != '')) {
            $productsReport = $productsReport->whereBetween('order_headers.created_at', [$date_from, $date_to]);
        }
        $productsReport = $productsReport->orderBy('total_sales', 'desc')->paginate(30);

        return view('AdminPanel.PagesContent.Reports.active_members', compact('productsReport', 'date_to', 'date_from'));
    }

    public function export(ExportShippingSheetSheetRequest $request)
    {
        $validated = $request->validated();
        try {
            return Excel::download(new SalesReportSheetExport($validated['start_date'], $validated['end_date']), 'salesreport.xlsx');
        }
        catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => $exception->getMessage()]);
        }
    }
    public function product_quantites_sold_view(Request $request)
    {

        $from = $request->from;
        $to = $request->to;
        if(!isset($from) || !isset($to))
        {
            $from = Carbon::now()->subDays(25)->toDateString();
            $to   = Carbon::now()->toDateString();

        }

        return view('AdminPanel.PagesContent.Reports.quantity_sold', get_defined_vars());
    }
    function product_quantites_sold(Request $request, $from = null, $to = null){


        $products = Product::all();
        $products_arr = [];

        if(!isset($from) || !isset($to))
        {
            $from = Carbon::now()->subDays(25)->toDateString();
            $to   = Carbon::now()->toDateString();

        }
        $to = Carbon::parse($to)->addDay()->toDateString();

        foreach ($products as $p) {
            $sum = 0;
            $order_lines  = $p->orderLines()->whereHas('Order', function ($query) use ($from,$to) {
                $query->where('created_at','>=',$from)
                    ->where('created_at','<=',$to)
                    ->where('platform','!=','admin')
                    ->where('order_status','!=','Cancelled');
            })->get();

            foreach ($order_lines as $line) {
                $sum+= $line->quantity ;
            }

            if($sum)
            {
                $quantity = $p->quantity ;
                $sum += 35 ;
                $wanted = 0;
                if($sum >= $quantity)
                {
                    $wanted = $sum - $quantity ;
                }

                $products_arr[] = [
                    'id' => $p->id,
                    'oracle_short_code' => $p->oracle_short_code,
                    'name' => $p->name_ar,
                    'total' => $sum,
                    'quantity' => $quantity,
                    'wanted' => $wanted,
                ];
            }
        }

        return response()->json([
            'data' => $products_arr,
        ]);

    }


}
