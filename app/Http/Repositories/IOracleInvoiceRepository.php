<?php

namespace App\Http\Repositories;

use App\Models\OracleInvoices;
use App\Models\OrderHeader;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\returnArgument;

class IOracleInvoiceRepository extends BaseRepository implements OracleInvoiceRepository
{
    public function __construct(OracleInvoices $model)
    {
        parent::__construct($model);
    }

    public function updateOrCreate($invoice)
    {
        if (isset($invoice->oracle_invoice_number))
            return (OracleInvoices::where('oracle_invoice_number', $invoice->oracle_invoice_number)->count()) ? OracleInvoices::where('oracle_invoice_number', $invoice->oracle_invoice_number)->update([
                "web_order_number" => $invoice->web_order_number,
                "order_amount"     => $invoice->order_amount,
                "actual_amount"    => $invoice->actual_amount,
                "items_count"    => $invoice->items_count,
            ]) : OracleInvoices::create([
                "oracle_invoice_number" => $invoice->oracle_invoice_number,
                "web_order_number"      => $invoice->web_order_number,
                "order_amount"          => $invoice->order_amount,
                "actual_amount"         => $invoice->actual_amount,
                "items_count"         => $invoice->items_count,
            ]);
    }


    public function updateInvoices()
    {

        $invices = OracleInvoices::select('order_headers.id as Order_ID', 'oracle_invoices.web_order_number', 'oracle_invoices.oracle_invoice_number', 'order_headers.payment_code', 'order_headers.wallet_status', DB::raw("(order_headers.total_order - order_headers.shipping_amount) as total_order "), 'oracle_invoices.order_amount', 'oracle_invoices.actual_amount', 'order_headers.created_at')
            ->leftJoin('order_headers', 'oracle_invoices.web_order_number', '=', 'order_headers.id')
            ->where('order_headers.id', '>', '0')->where('oracle_invoices.created_at', '>', Carbon::now()->subDays(30))->distinct('oracle_invoices.oracle_invoice_number')->orderBy('order_headers.id', 'DESC');
        $invices = $invices->get();

        foreach ($invices as $invice) {
            if (isset($invice) && isset($invice->web_order_number) && isset($invice->oracle_invoice_number)) {
                $orinvice = OracleInvoices::where('web_order_number', $invice->web_order_number)->where('oracle_invoice_number', $invice->oracle_invoice_number)->first();
                if (!empty($orinvice)) {
                    if ((($invice->total_order - ($invice->actual_amount)) >= -1) && (($invice->total_order - ($invice->actual_amount)) <= 2)) {
                        $check_valid = 'valid';
                    }
                    else {
                        $check_valid = 'notvalid';
                    }
                    $newData = [
                        "total_order_in_oracle"  => $invice->actual_amount,
                        "total_order_out_oracle" => 0,
                        "check_valid"            => $check_valid,
                    ];
                    $orinvice->update($newData);
                    $orderHeader = OrderHeader::where('id', $invice->Order_ID)->first();
                    if (!empty($orderHeader)) {
                        $newData2 = [
                            "actual_amount"  => $invice->actual_amount,
                            "oracle_invoice_number" => $invice->oracle_invoice_number,
                            "check_valid2"            => $check_valid,
                            "oracle_flag"            => 1,
                        ];
                        $orderHeader->update($newData2);
                    }

                }
            }
        }
        return true;
    }



    public function updateInvoicesold()
    {
        $invices = OracleInvoices::select('order_headers.id as Order_ID', 'oracle_invoices.web_order_number', 'oracle_invoices.oracle_invoice_number', 'order_headers.payment_code', 'order_headers.wallet_status', DB::raw("(order_headers.total_order - order_headers.shipping_amount) as total_order "), 'oracle_invoices.order_amount', 'oracle_invoices.actual_amount', 'order_headers.created_at', DB::raw("(SELECT SUM(oracle_invoices.actual_amount) FROM oracle_invoices WHERE oracle_invoices.web_order_number in(SELECT DISTINCT order_lines.oracle_num FROM order_lines  LEFT JOIN products  on(order_lines.product_id = products.id) WHERE order_lines.order_id=order_headers.id AND products.flag IN (5,7,9,23))) AS  total_order_in_oracle"), DB::raw("(SELECT SUM(order_lines.price * quantity) FROM order_lines WHERE order_lines.oracle_num in(SELECT DISTINCT order_lines.oracle_num FROM order_lines LEFT JOIN oracle_invoices on(order_lines.oracle_num = oracle_invoices.web_order_number) LEFT JOIN products  on(order_lines.product_id = products.id) WHERE order_lines.order_id=order_headers.id AND oracle_invoices.web_order_number IS NULL AND products.flag NOT IN (5,7,9,23) )) AS  total_order_out_oracle"))
            ->leftJoin('order_lines', 'order_lines.oracle_num', '=', 'oracle_invoices.web_order_number')
            ->leftJoin('order_headers', 'order_lines.order_id', '=', 'order_headers.id')
            ->where('order_headers.id', '>', '0')->where('oracle_invoices.updated_at', '>', Carbon::now()->subHours(1))->distinct('oracle_invoices.oracle_invoice_number')->orderBy('order_headers.id', 'DESC');
        $invices = $invices->get();

        foreach ($invices as $invice) {

            if (isset($invice) && isset($invice->web_order_number) && isset($invice->oracle_invoice_number)) {
                $orinvice = OracleInvoices::where('web_order_number', $invice->web_order_number)->where('oracle_invoice_number', $invice->oracle_invoice_number)->first();
                if (!empty($orinvice)) {
                    if ((($invice->total_order - ($invice->total_order_in_oracle + $invice->total_order_out_oracle)) >= -1) && (($invice->total_order - ($invice->total_order_in_oracle + $invice->total_order_out_oracle)) <= 2)) {
                        $check_valid = 'valid';
                    }
                    else {
                        $check_valid = 'notvalid';
                    }
                    $newData = [
                        "total_order_in_oracle"  => $invice->total_order_in_oracle,
                        "total_order_out_oracle" => $invice->total_order_out_oracle,
                        "check_valid"            => $check_valid,
                    ];
                    $orinvice->update($newData);
                }
            }
        }
        return true;
    }


    public function getAllData11($inputData)
    {
        //$res = OracleInvoices::find(1700);
       // dd($res->lines);
   /*      $invices = OracleInvoices::whereHas('lines', function ($query) {
             $query->where('order_lines.id','24717');
         });*/
       /* $invices = OracleInvoices::whereHas('lines', function ($query) {
            $query->whereHas('Order',
                 function ($query2) {
                    if (Auth::guard('admin')->user()->id == 24) {
                        $query2->where('order_headers.is_printed', '0');
                    }
                    if (isset($inputData['Order_ID']) && $inputData['Order_ID'] != '') {
                        $query2->where('order_headers.id',  $inputData['Order_ID']);
                    }
                    if (isset($inputData['wallet_status']) && $inputData['wallet_status'] != '') {
                        $query2->where('order_headers.wallet_status',  $inputData['wallet_status']);
                    }
                    if (isset($inputData['wallet_status']) && $inputData['wallet_status'] != '') {
                        $query2->where('order_headers.wallet_status',  $inputData['wallet_status']);
                    }
                    if (isset($inputData['start_date']) && $inputData['start_date'] != '') {
                        $query2->where('order_headers.created_at', '>=', $inputData['start_date']);
                    }else
                    {
                         $query2->where('order_headers.created_at', '>=',  Carbon::now()->subDays(7));
                    }
                    if (isset($inputData['end_date']) && $inputData['end_date'] != '') {
                        $query2->where('order_headers.created_at', '<=', $inputData['end_date']);
                    }
                });
       });*/
       $invices = OracleInvoices::paginate(200);

    //   dd($invices);
        // if (Auth::guard('admin')->user()->id == 24) {
        //  // $invices->where('check_valid','valid');
        // }
        // if (isset($inputData['check_valid']) && $inputData['check_valid'] != '') {
        //   //invices->where('check_valid', $inputData['check_valid'])
        //     //  ->where('actual_amount', '>', 0);
        // }
        // if (isset($inputData['web_order_number']) && $inputData['web_order_number'] != '') {
        //  // $invices->where('web_order_number', $inputData['web_order_number']);
        // }

     //   $invices->orderBy('id', 'DESC');

        $invices->appends(request()->query())->links();
        return $invices;
    }


    
    public function check_filter($allowed,$sent)
    {
        $filter = [];
        foreach ($allowed as $value) {
 
           if(isset($sent[$value]) && $sent[$value] != '')
           {
            $filter[$value] =$sent[$value] ;
           }
        }

        return $filter ;
    }
    public function all($inputData)
    {
        $link = 'order_header';
        if(isset($inputData['invoice_link']))$link = 'order_header_link_with_invoices';
        $allowed_header_filters = array(
        'id',
        'wallet_status',

        );
        $allowed_invoice_filters = array(
        'check_valid' ,
        'oracle_invoice_number' ,

        );
        $order_id = isset($inputData['id']) ? $inputData['id'] : false ;

        if(!$order_id )
        {
            $header_filter = $this->check_filter($allowed_header_filters,$inputData);
            $invoice_filter = $this->check_filter($allowed_invoice_filters,$inputData);
        
     
            $invoices_query = OracleInvoices::whereHas($link, function ($query) use($inputData,$header_filter) {
            if (Auth::guard('admin')->user()->id == 24) $query->where('is_printed', '0');
            if(!empty($header_filter))
            {
                foreach($header_filter as $key => $val)
                {
                    $query->where($key, $val);
                }
            }
           


            if (isset($inputData['from']) && $inputData['from'] != '') 
            {

                $query->where('created_at', '>', $inputData['from'] .' 00:00:00');
            }
            else
            {
                $query->where('created_at', '>',  Carbon::now()->subDays(3));
            }
            if (isset($inputData['to']) && $inputData['to'] != '')  $query->where('created_at', '<', $inputData['to'] .' 23:59:59');
            
            });

           if(!empty($invoice_filter))
           {

                foreach($invoice_filter as $key => $val)
                {
                   $invoices_query->where($key, $val);
                }
           }
       }
       else
       {

        $invoices_query = OracleInvoices::whereHas($link, function ($query) use($order_id) {
              $query->where('id', $order_id);
        });

       }

           $invoices = $invoices_query->get();
           $invoices_wanted =[]; 
            foreach($invoices as $key => $in)
            {

                $invoice = [] ;
                $invoice['web_order_number'] = $in->web_order_number;
                $invoice['oracle_invoice_number'] = $in->oracle_invoice_number;
                $invoice['order_amount'] = $in->order_amount;
                $invoice['check_valid'] = $in->check_valid;
                $order_header_wanted = [
                    'id' =>  $in->$link->id ,
                    'wallet_status' =>  $in->$link->wallet_status ,
                    'payment_code' =>  $in->$link->payment_code ,
                    'total_order' =>  $in->$link->total_order ,
                    'delivery_status' =>  $in->$link->delivery_status ? $in->$link->delivery_status : 'order status '.$in->$link->order_status  ,
                ];
                $invoice['order_header'] = $order_header_wanted;
                $invoice['differnce']  = round( (float)$in->$link->total_order - (float) $in->order_amount ,2);
                $invoice['human_created_at'] = Carbon::parse($in->created_at)->format('Y-m-d  H:i') ;
                $invoice['human_updated_at'] = Carbon::parse($in->updated_at)->format('Y-m-d  H:i');
                $invoices_wanted[] = $invoice ;
            }

          return $invoices_wanted ;

    }
  

    public function getAllData($inputData)

    {

        $inputData['start_date'] =isset($inputData['start_date']) && $inputData['start_date'] != '' ? Carbon::parse($inputData['start_date'])->startOfDay()->toDateTimeString():null;
        $inputData['end_date'] =isset($inputData['start_date']) && $inputData['start_date'] != '' ? Carbon::parse($inputData['end_date'])->endOfDay()->toDateTimeString():null;
       

        $invices = OracleInvoices::select('order_headers.id as Order_ID', 'oracle_invoices.web_order_number', 'oracle_invoices.oracle_invoice_number', DB::raw("(order_headers.total_order - order_headers.shipping_amount) as total_order "), 'order_headers.payment_code', 'order_headers.wallet_status', 'oracle_invoices.order_amount', 'oracle_invoices.actual_amount', 'order_headers.created_at', 'oracle_invoices.total_order_in_oracle',
            'oracle_invoices.total_order_out_oracle'
            , DB::raw("((IF((order_headers.total_order - order_headers.shipping_amount) > 0,(order_headers.total_order - order_headers.shipping_amount),0)) -  ((IF(oracle_invoices.total_order_in_oracle > 0,oracle_invoices.total_order_in_oracle,0)) + (IF(oracle_invoices.total_order_out_oracle > 0,oracle_invoices.total_order_out_oracle,0)))) as total_4u_min_oracle"))
            ->leftJoin('order_headers', 'oracle_invoices.web_order_number', '=', 'order_headers.id')
            ->where('order_headers.id', '>', '0')->distinct('oracle_invoices.oracle_invoice_number')->orderBy('order_headers.created_at', 'DESC');

        if (Auth::guard('admin')->user()->id == 24) {
            $invices->where('order_headers.is_printed', '0');
            $invices->havingRaw("total_4u_min_oracle > -1 AND total_4u_min_oracle < 2");
        }
        if (isset($inputData['Order_ID']) && $inputData['Order_ID'] != '') {
            $invices->where('order_headers.id', $inputData['Order_ID']);
        }
        if (isset($inputData['wallet_status']) && $inputData['wallet_status'] != '') {
            $invices->where('order_headers.wallet_status', $inputData['wallet_status']);
        }
        if (isset($inputData['check_valid']) && $inputData['check_valid'] != '') {
            $invices->where('oracle_invoices.check_valid', $inputData['check_valid'])->where('oracle_invoices.actual_amount', '>', 0);
        }
        if (isset($inputData['web_order_number']) && $inputData['web_order_number'] != '') {
            $invices->where('oracle_invoices.web_order_number', $inputData['web_order_number']);
        }
        if (isset($inputData['oracle_invoice_number']) && $inputData['oracle_invoice_number'] != '') {
            $invices->where('oracle_invoices.oracle_invoice_number', $inputData['oracle_invoice_number']);
        }
        if (isset($inputData['start_date']) && $inputData['start_date'] != '') {
            $invices->where('order_headers.created_at', '>=', $inputData['start_date']);
        }
        else
        {
            $invices->where('order_headers.created_at', '>=',  Carbon::now()->subDays(7));
        }
        if (isset($inputData['end_date']) && $inputData['end_date'] != '') {
            $invices->where('order_headers.created_at', '<=', $inputData['end_date']);
        }
//        dd($invices->toSql());
        $invices = $invices->paginate(50);
        // dd($invices);
        $invices->appends(request()->query())->links();
        return $invices;
    }





    public function getOrdersNotSentToOracle()
    {
        return OrderHeader::where(function ($query) {
            $query->where('payment_status', 'PAID')->orWhere('wallet_status', 'cash');
        })->where('user_id', '!=', '1')->where('order_status', 'pending')->where('send_t_o', '0')->where('created_at', '>', Carbon::now()->subDays(2))->pluck('id')->count();
    }

    public function ordersCountSentToOracleNotReturn()
    {
        return OrderHeader::leftJoin('oracle_invoices', 'oracle_invoices.web_order_number', '=', 'order_headers.id')
                ->where('order_headers.send_t_o', '1')->where('order_headers.user_id','!=', '1')->whereNotNull('order_headers.id')->whereNull('oracle_invoices.id')
                ->where('order_headers.created_at', '>', Carbon::parse('1-12-2023'))
                ->groupBy('order_headers.id')->where('order_headers.order_status','pending')
                ->pluck('order_headers.id')->count();
    }

    public function insertProductsNotExist()
    {
        $products = DB::table('oracle_products')
            ->leftJoin('products', function ($join) {
                $join->on('oracle_products.item_code', '=', 'products.oracle_short_code');
            })->whereIn('oracle_products.company_name', ['Cosmetics', 'Food', 'MoreByEva'])->whereNull('products.id')->select('oracle_products.*')->get();
        foreach ($products as $product) {
            $newData = [
                "full_name"            => $product->description,
                "flag"                 => $product->company_name == 'Cosmetics' ? 5 : 9,
                "name_ar"              => $product->description,
                "name_en"              => $product->description,
                "price"                => $product->cust_price,
                "tax"                  => $product->percentage_rate,
                "discount_rate"        => $product->discount_rate,
                "excluder_flag"        => $product->excluder_flag,
                "price_after_discount" => $product->cust_price - (($product->cust_price * $product->discount_rate) / 100),
                "quantity"             => $product->quantity,
                "description_ar"       => $product->description,
                "description_en"       => $product->description,
                "oracle_short_code"    => $product->item_code,
                "image"                => '',
                "filter_id"            => 1,
                "old_price"            => $product->cust_price,
                "old_discount"         => 0,
            ];

            Product::create($newData);
        }

        return true;
    }

    public function truncateModel()
    {
        return OracleInvoices::truncate();
    }

}
