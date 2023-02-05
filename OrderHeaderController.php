<?php

namespace App\Http\Controllers\Admin;



use App\Constants\OrderTypes;
use App\Exports\OrdersExport;
use App\Exports\OrderUserExport;
use App\Exports\ShippingSheetSheetExport;
use App\Http\Controllers\Application\FawryPaymentController;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\ExportOrderHeadersSheet;
use App\Http\Requests\ExportShippingSheetSheetRequest;
use App\Http\Requests\OrderHeaderRequest;
use App\Http\Services\CartService;
use App\Http\Services\CommissionService;
use App\Http\Services\OrderService;
use App\Http\Services\UserService;
use App\Models\Area;
use App\Models\Category;
use App\Models\City;
use App\Models\OrderHeader;
use App\Models\OrderLine;
use App\Models\RtoSOrders;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Services\OrderLinesService;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Auth;





class OrderHeaderController extends HomeController
{
     private $OrderHeaderService;
    private $OrderRequest;
    protected  $CartService;
    protected  $UserService;
    private  $OrderLinesService;

    public function __construct(OrderService $OrderHeaderService ,   CommissionService  $CommissionService, OrderLinesService $OrderLinesService,Request  $OrderRequest,CartService $CartService,UserService $UserService)
    {
        $this->OrderHeaderService  = $OrderHeaderService;
        $this->OrderRequest        = $OrderRequest;
        $this->CartService=$CartService;
        $this->UserService=$UserService;
        $this->OrderLinesService     = $OrderLinesService;
        $this->CommissionService     = $CommissionService;
    }

    public function index()
    {
        $data = $this->OrderHeaderService->getAll(request()->all());
        return view('AdminPanel.PagesContent.OrderHeaders.index')->with('orderHeaders',$data);
    }

  public function getOracleNumberByOrderId(Request $request)
    {

        $name = $request->name;
        $date_to = $request->date_to;
        $date_from = $request->date_from;
        $oracle_numbers='';
        $orders=[];
        if(((isset($date_to) && $date_to !='') && (isset($date_from) && $date_from !='')) ||(isset($name) && $name !='')){
            $orders=OrderHeader::with('order_lines');
            if ((isset($date_to) && $date_to !='') && (isset($date_from) && $date_from !='')){
                $orders=$orders->whereBetween('created_at',[ $date_from,$date_to]);
            }
            if (isset($name) && $name !=''){
                $orders=$orders->where('id',$name);
            }
            $orders=$orders->orderBy('order_headers.id','DESC')->get();
        }
        foreach ($orders as $lines) {
            $lines->order_lines=$this->unique_multidimensional_array($lines->order_lines,'oracle_num');
        }
        return view('AdminPanel.PagesContent.OrderHeaders.oracle',compact('name','orders','oracle_numbers','date_to','date_from'));
//        return view('oracle_num',compact('name','orders','oracle_numbers','date_to','date_from'));
    }

    function unique_multidimensional_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();
        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }

   
   
    public function create()
    {

        $products=Product::select('products.id','products.flag','products.full_name','products.name_en','products.name_ar','products.description_en',
            'products.description_ar','products.image','products.oracle_short_code','products.discount_rate',
            'products.price','products.price_after_discount','products.quantity')
            ->where('products.stock_status','in stock');
        $products=$products->skip(0)
            ->take(10)->get();

        $user = $this->UserService->getUser(Auth::User()->id);
        $min_required=$this->calculateMinRequired($user);

//dd($products);

        $categories=Category::
        where([['id','!=',13]])
            ->select(['id', 'name_en', 'name_ar'])
            ->withCount('productStock')
            ->having('product_stock_count', '>', 0)
            ->where('is_available',1)
            ->get()
            ->makeHidden('product_stock_count');
        $cities =City::select(['name_en','id']) ->distinct()->get();

        return view('AdminPanel.PagesContent.OrderHeaders.form',compact('categories','products','cities','min_required'));

    }

    private function calculateMinRequired($user){
        //refactoring
        
         if (isset($user->stage) &&isset($user->freeaccount)){
                     
                if($user->stage<=2 && $user->freeaccount !=1)// activation premium account
                    return $user->userType->min_required;
                elseif ($user->stage<=2 && $user->freeaccount ==1)// activation free account
                    return 125;
        //        elseif ($user->stage == 3 && $user->freeaccount == 1) //single free order
        //            return 150;
                else
                    return 150; //any single order
            
         }else
            return 150;    

    }
 public function getAllproducts(Request $request)
    {

        $inputData=$request;
        $products=Product::select('products.id','products.flag','products.full_name','products.name_en','products.name_ar','products.description_en',
            'products.description_ar','products.image','products.oracle_short_code','products.discount_rate',
            'products.price','products.price_after_discount','products.quantity')
            ->where('products.stock_status','in stock');

        if (isset($inputData['name']) && $inputData['name']!='')
        {
            $products->where('products.name_en', 'like', '%'.$inputData['name'].'%');
        }
        if (isset($inputData['category_id']) && $inputData['category_id']!='')
        {
            $products->join('product_categories','product_categories.product_id' ,'products.id')
                ->where('product_categories.category_id',$inputData['category_id']);
        }
        $products=$products->skip(0)
            ->take(15)->get();

//dd($products);

     $response = [
         'status' => 200,
         'message' => "All Products",
         'data' => $products
     ];
     return response()->json($response);

    }

 public function getAreasByCityID(Request $request)
    {
     $areas = Area::select('id','region_en')->where("city_id",$request->city_id)->get();
     $response = [
         'status' => 200,
         'message' => "All Products",
         'data' => $areas
     ];
     return response()->json($response);
    }

    public function CalculateProductsAndShipping(Request  $request)
    {

        $data = [
            "user_id"             => request()->input('user_id'),
            "address"             => request()->input('address'),
            "landmark"            => request()->input('landmark'),
            "building_number"     => request()->input('building_number'),
            "floor_number"        => request()->input('floor_number'),
            "apartment_number"    => request()->input('apartment_number'),
            "city"                => request()->input('city'),
            "area"                => request()->input('area'),
            "created_for_user_id" => request()->input('created_for_user_id'),
            "order_type"          => request()->input('order_type'),
            "gift_category_id"          => 0,
            "items"               => request()->input('items')
        ];
        $has_discount     = $this->UserService->userHasDiscount($data['created_for_user_id']);
        $this->CartService->orderType=$data['order_type'];
        $productsAndTotal = $this->CartService->calculateProducts($data["items"],$has_discount);
        if (!empty($productsAndTotal)) {
            $this->CartService->saveProductsToCart($productsAndTotal['products'],$data['user_id'],$data['created_for_user_id']);
            $productsAndTotal['shipping']         = $this->CartService->calculateShipping($data['created_for_user_id'],$data['order_type'],$productsAndTotal['totalProductsAfterDiscount']);
            $productsAndTotal['allowed_services'] = $this->CartService->getServices($productsAndTotal['totalProductsAfterDiscount'],$data['user_id']);
            $this->CartService->saveCartHeader($data['user_id'],$data['created_for_user_id'],$data['order_type'],$productsAndTotal['totalProducts'],$productsAndTotal['totalProductsAfterDiscount'],$productsAndTotal['shipping']);



            $orderHeaderData = [
                'payment_code' =>  NULL,
                'total_order' => $productsAndTotal['totalProductsAfterDiscount'],
                'user_id' => $data['user_id'],//created by
                'created_for_user_id' => $data['created_for_user_id'],//created for
                'order_type' => $data['order_type'],
                'shipping_amount' => $productsAndTotal['shipping'],
                'address' => $data['address'],
                'city' => $data['city'],
                'area' => $data['area'],
                'building_number' => $data['building_number'],
                'landmark' => $data['landmark'],
                'floor_number' => $data['floor_number'],
                'apartment_number' => $data['apartment_number'],
                'gift_category_id' => $data['gift_category_id']
            ];
            $oorder = OrderHeader::create($orderHeaderData);
            $productsAndTotal['order_id'] = $oorder->id;

            if (!empty($oorder))
            {
                $this->OrderLinesService->createOrderLines($oorder['id'] , $data['user_id'], $data['created_for_user_id']);
                $this->CommissionService->createCommission($oorder);
                $this->OrderLinesService->deleteCartAndCartHeader($data['user_id'], $data['created_for_user_id']);
            }

            $response = [
                'status' => 200,
                'message' => "All Prices",
                'data' => $productsAndTotal
            ];
            return response()->json($response);

        }
        $response = [
            'status' => 200,
            'message' => "All Products",
            'data' => null
        ];
        return response()->json($response);
    }


    public function store()
    {

    }









    public function show(OrderHeader $orderHeader)
    {
        $orderNumber=$orderHeader->id;
        $invoicesCount=OrderLine::select('oracle_num')->where('order_id',$orderNumber)->distinct()->count('oracle_num');
        $invoicesNumber=OrderLine::select('oracle_num')->where('order_id',$orderNumber)->distinct()->get();
        $invoicesLines =DB::select('SELECT ol.oracle_num ,p.price pprice,p.tax ptax,ol.price olprice,p.name_en psku,ol.quantity olquantity FROM order_lines ol,products p
     	                        where 	ol.order_id ='.$orderNumber.'
     	                        and ol.product_id = p.id ');
        $invoicesTotalPrice=OrderLine::where('order_id',$orderNumber)->sum('quantity');
        $user=User::where('id',$orderHeader->created_for_user_id)->first();
        return view('AdminPanel.PagesContent.OrderHeaders.show',compact('orderHeader','invoicesNumber','invoicesCount','invoicesLines','invoicesTotalPrice','user'));
    }

     public function edit(OrderHeader $orderHeader)
    {
        $orderNumber = $orderHeader->id;
        $invoicesCount = OrderLine::select('oracle_num')->where('order_id', $orderNumber)->distinct()->count('oracle_num');
        $invoicesNumber = OrderLine::select('oracle_num')->where('order_id', $orderNumber)->distinct()->get();
        $invoicesLines = DB::select('SELECT ol.product_id, ol.oracle_num ,p.price pprice,p.tax ptax,ol.price olprice,p.name_en psku,ol.quantity olquantity FROM order_lines ol,products p
     	                        where 	ol.order_id =' . $orderNumber . '
     	                        and ol.product_id = p.id ');
        $invoicesTotalPrice = OrderLine::where('order_id', $orderNumber)->sum('quantity');
        $user = User::where('id', $orderHeader->created_for_user_id)->first();

        return view('AdminPanel.PagesContent.OrderHeaders.refund', compact('orderHeader', 'invoicesNumber', 'invoicesCount', 'invoicesLines', 'invoicesTotalPrice', 'user'));

    }



    public function refundOrderWallet($user_id,$oldTotalOrder,$newTotalOrder)
    {
        $walletEvaluation = $this->WalletEvaluationRepository->find(1, ['value', 'amount']);
        $myCurrentWallet = $this->UserWalletRepository->getCurrentWallet($user_id);
        if (!empty($myCurrentWallet)) {
            $oldWithdrawalBeforOrderCount = floor(((float)$myCurrentWallet->total_orders_amount - (float)$oldTotalOrder) / $walletEvaluation->value);
            if ($myCurrentWallet->withdrawal_order_count != $oldWithdrawalBeforOrderCount) {
                $difCount = $myCurrentWallet->withdrawal_order_count - $oldWithdrawalBeforOrderCount;
                $this->UserWalletRepository->updateWallet(['user_id' => $user_id], [
                    'total_orders_amount' => ((float)$myCurrentWallet->total_orders_amount - (float)$oldTotalOrder),
                    'withdrawal_order_count' => $oldWithdrawalBeforOrderCount,
                    'total_wallet' => ($myCurrentWallet->total_wallet - ($walletEvaluation->amount * $difCount)),
                    'current_wallet' => $myCurrentWallet->current_wallet - ($walletEvaluation->amount * $difCount),
                ]);
                $this->SingleOrderPaidActions->addOrderToMyWallet($user_id,$walletEvaluation,(float)$newTotalOrder);
            }else{
                $this->UserWalletRepository->updateWallet(['user_id' => $user_id], [
                    'total_orders_amount' => ((float)$myCurrentWallet->total_orders_amount - ((float)$oldTotalOrder - (float)$newTotalOrder)),
                ]);
            }
        }
    }
    public function refundOrderWalletToMyParent($child_id,$oldTotalOrder,$newTotalOrder,$orderId)
    {
        $my_parents = $this->UserRepository->getAccountParent($child_id);
        foreach ($my_parents as  $parent)
        {
            // if this parent Exist in wallet history
            if($this->UserWalletRepository->parentHaveWalletForThisOrder($parent->parent_id,$orderId)){
                $this->refundOrderWallet($parent->parent_id, $oldTotalOrder, $newTotalOrder);
            }
        }
    }
    public function refundOrderPaymentOnline($oldTotalOrder,$newTotalOrder,$payment_code)
    {
        $diffamount=((float)$oldTotalOrder-(float)$newTotalOrder);
        $newdif=number_format((float)$diffamount, 2, '.', '');
        $merchantCode = '1tSa6uxz2nQFNIFa9AHjDA==';
        $referenceNumber = $payment_code;
        $refundAmount = strval($newdif);
        $reason = 'Item not as described';
        $merchant_sec_key = 'b560c6003c9a4f95b963a7f3c965e24c'; // For the sake of demonstration
        $signature = hash('sha256', $merchantCode . $referenceNumber . $refundAmount . $reason . $merchant_sec_key);
        $httpClient = new \GuzzleHttp\Client(); // guzzle 6.3

        $response = $httpClient->request('POST', 'https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/refund', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
            'body' => json_encode([
                'merchantCode' => $merchantCode,
                'referenceNumber' => $referenceNumber,
                'refundAmount' => $refundAmount,
                'reason' => $reason,
                'signature' => $signature
            ], true)
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }


   
    public function update(Request $request, OrderHeader $orderHeader)
    {
        $this->refundOrderWallet($orderHeader->created_for_user_id,$orderHeader->total_order,$request->total_order);
        $this->refundOrderWalletToMyParent($orderHeader->created_for_user_id,$orderHeader->total_order,$request->total_order,$orderHeader->id);

        $orderCommission = UserCommission::where('user_id', $orderHeader->user_id)->where('order_id', $orderHeader->id)->first();
        $orderCommission->commission = ((float)$orderCommission->commission_percentage/100 * (float)$request->total_order);
        $orderCommission->save();

        if (isset($request->product_ids) && count($request->product_ids) > 0) {
            OrderLine::where('order_id', $orderHeader->id)->whereIn('product_id', $request->product_ids)->delete();
            OrderHeader::where('id', $orderHeader->id)->update(['total_order' => (float)$request->total_order, 'wallet_used_amount' => (float)$request->total_order, 'updated_at' => now()]);
        }

        if ($orderHeader->wallet_status == 'full_wallet') {
                $mywallet = $this->UserWalletRepository->getCurrentWallet($orderHeader->created_for_user_id);
                $mywallet->current_wallet=((float)$mywallet->current_wallet +  ((float)$orderHeader->total_order-(float)$request->total_order));
                $mywallet->save();
            $response['statusDescription']='Operation done successfully';
        }elseif($orderHeader->wallet_status == 'only_fawry'){
             $response= $this->refundOrderPaymentOnline($orderHeader->total_order,$request->total_order,$orderHeader->payment_code);
        }
        $statusDescription = $response['statusDescription']; // get response statusDescription
        return redirect('http://localhost:8000/admin/orderHeaders/'.$orderHeader->id.'/edit?message='.$statusDescription);
    }

    public function destroy(OrderHeader $product)
    {

    }

    public function ExportOrderHeadersSheet(ExportOrderHeadersSheet $request)
    {
        $validated = $request->validated();
        try {
            if ($request->input('with') == 'user')
                return Excel::download(new OrderUserExport($validated['start_date'],$validated['end_date'],$validated['payment_status']), 'orders.xlsx');

            else
            return Excel::download(new OrdersExport($validated['start_date'],$validated['end_date'],$validated['payment_status']), 'orders.xlsx');
        }
        catch (\Exception $exception)
        {

            return redirect()->back()->withErrors(['error' => $exception->getMessage()]);
        }
    }
    
    public function ExportOrderCharge(Request $request)
    {
        $oldbill=RtoSOrders::where('order_header_id',$request->order_id)->first();
        if(!$oldbill){
            $data=[
                "waybillRequestData" => [
                    "FromOU" => "",
                    "WaybillNumber" => "",
                    "DeliveryDate" => "",
                    "CustomerCode" => "ACME",
                    "ConsigneeCode" => "00000",
                    "ConsigneeAddress" => $request->user_address,
                    "ConsigneeCountry" => "EG",
                    "ConsigneeState" => "CAIRO",
                    "ConsigneeCity" => "CAIRO",
                    "ConsigneePincode" => "8523",
                    "ConsigneeName" => $request->user_name,
                    "ConsigneePhone" => $request->user_phone,
                    "ClientCode" => "ACME",
                    "NumberOfPackages" => 1,
                    "ActualWeight" => 1,
                    "ChargedWeight" => "",
                    "CargoValue" => 1100,
                    "ReferenceNumber" => $request->order_id,
                    "InvoiceNumber" => $request->order_id,
                    "CreateWaybillWithoutStock" => "False",
                    "PaymentMode" => "TBB",
                    "ServiceCode" => "PUD",
                    "WeightUnitType" => "KILOGRAM",
                    "Description" => "VZXC",
                    "COD" => 1125,
                    "CODPaymentMode" => "CASH",
                    "packageDetails" => [
                        "packageJsonString" => [
                            [
                                "barCode" => "",
                                "packageCount" => 1,
                                "length" => 20,
                                "width" => 20,
                                "height" => 20,
                                "weight" => 1,
                                "itemCount" => 1,
                                "chargedWeight" => 1,
                                "selectedPackageTypeCode" => "BOX"
                            ]
                        ]
                    ]
                ]
            ];

            try {
                $httpClient = new \GuzzleHttp\Client(); // guzzle 6.3
                $response = $httpClient->request('POST', 'https://api.r2slogistics.com/CreateWaybill?secureKey=A3604E505DB24D118B9A2D48BDC336B3', [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ],
                    'body' => json_encode($data, true)
                ]);

                $rrespose = json_decode($response->getBody()->getContents(), true);
                if($rrespose['messageType']=='Success'){
                    $rrespose['order_header_id']=$request->order_id;
                    RtoSOrders::create($rrespose);
                    $filename = 'chargePDF'.$request->order_id.'.pdf';
                    $tempImage = tempnam(sys_get_temp_dir(), $filename);
                    copy($rrespose['labelURL'], $tempImage);
                    return response()->download($tempImage, $filename);
                }else{
                    return redirect()->back()->withErrors(['error' => $rrespose['messageType']]);
                }

            } catch (\Exception $exception) {
                return redirect()->back()->withErrors(['error' => $exception->getMessage()]);
        }
       }else{
            $filename = 'chargePDF'.$request->order_id.'.pdf';
            $tempImage = tempnam(sys_get_temp_dir(), $filename);
            copy($oldbill['labelURL'], $tempImage);
            return response()->download($tempImage, $filename);
        }
    }
 public function changeOrderChargeStatus(Request $request)
    {
        $oldbill=RtoSOrders::where('order_header_id',$request->order_id)->first();
        $orderHeader=OrderHeader::where('id',$request->order_id)->first();
        if($oldbill&&$oldbill->waybillNumber){
            $url='https://webservice.logixerp.com/webservice/v2/MultipleWaybillTracking?SecureKey=A3604E505DB24D118B9A2D48BDC336B3&WaybillNumber='.$oldbill->waybillNumber;

            try {
                $httpClient = new \GuzzleHttp\Client(); // guzzle 6.3
                $response = $httpClient->request('GET', $url);
                $rrespose = json_decode($response->getBody()->getContents(), true);

                if( $rrespose && isset($rrespose['waybillTrackDetailList']) && isset($rrespose['waybillTrackDetailList'][0])){
                    $oldbill->status=$rrespose['waybillTrackDetailList'][0]['currentStatus'];
                    $oldbill->save();
                    $orderHeader->order_status=$rrespose['waybillTrackDetailList'][0]['currentStatus'];
                    $orderHeader->save();
                    return redirect()->back()->with('message', 'Updated successfully');
                }else{
                    return redirect()->back()->withErrors(['error' => 'error in R2S response']);
                }
            } catch (\Exception $exception) {
                return redirect()->back()->withErrors(['error' => $exception->getMessage()]);
        }
       }
        return redirect()->back()->withErrors(['error' => 'no way bill Number']);
    }


    public function ExportShippingSheetSheet()
    {
        return view('AdminPanel.PagesContent.OrderHeaders.shippingSheet');
    }

    public function HandelExportShippingSheetSheet(ExportShippingSheetSheetRequest $request)
    {
        $validated = $request->validated();
        try {
            return Excel::download(new ShippingSheetSheetExport($validated['start_date'],$validated['end_date']), 'orders.xlsx');
        }
        catch (\Exception $exception)
        {

            return redirect()->back()->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function ChangeStatusForOrder()
    {
        $orderHeaders = $this->OrderHeaderService->getPendingOrders();
        return view('AdminPanel.PagesContent.OrderHeaders.changeOrderStatus',compact('orderHeaders'));
    }

    public function HandelChangeStatusForOrder(ChangeStatusRequest  $request)
    {
        $inputs            = $request->validated();
        $order_id          = $inputs['order_id'];
        $data['item_code'] = "orderNumber-".$order_id;
        $this->OrderRequest->request->add([
            'fawryRefNumber' => '123454',
            'orderStatus'    => 'PAID',
            'paymentMethod' => 'BackEndPAY',
            'orderItems' => [
                $data
            ]
        ]);
        $order = app(FawryPaymentController::class)->changeOrderStatus($this->OrderRequest);
        return redirect()->back()->with(['message' => json_encode($order->original)]);
    }
}
