<?php

namespace App\Http\Controllers\Application;

use App\Http\Services\ProductService;
use App\Http\Services\UserService;
use App\Libraries\ApiResponse;
use App\Models\OrderHeader;
use App\Models\OrderLine;

use App\Libraries\ApiValidator;
use App\ValidationClasses\ProductsValidation;
use App\ValidationClasses\UserValidation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Repositories\CartRepository;
use App\Http\Repositories\ProductRepository;
use App\Http\Services\CartService;
use Illuminate\Support\Facades\App;
use App\Http\Services\PaymentService;
use App\Http\Services\OrderLinesService;

class UserCartController extends HomeController
{
    protected $API_VALIDATOR;
    protected $API_RESPONSE;
    protected $CartService;
    protected $ProductsValidation;
    protected $ProductService;
    protected $UserService;
    protected $UserValidation;
    protected $CartRepository;
    protected $ProductRepository;
    protected $OrderLinesService;
    protected $PaymentService;

    public function __construct(ProductRepository $ProductRepository, UserService $UserService, PaymentService $PaymentService, OrderLinesService $OrderLinesService, UserValidation $UserValidation, CartRepository $CartRepository, CartService $CartService, ProductService $ProductService, ApiValidator $apiValidator, ApiResponse $API_RESPONSE, ProductsValidation $ProductsValidation)
    {
        $this->ProductRepository = $ProductRepository;
        $this->CartRepository = $CartRepository;
        $this->API_VALIDATOR = $apiValidator;
        $this->API_RESPONSE = $API_RESPONSE;
        $this->CartService = $CartService;
        $this->ProductsValidation = $ProductsValidation;
        $this->ProductService = $ProductService;
        $this->UserService = $UserService;
        $this->UserValidation = $UserValidation;
        $this->OrderLinesService = $OrderLinesService;
        $this->PaymentService = $PaymentService;
    }

    public function addProductToCart(Request $request)
    {

        // check offer 1+ 1
        $offerproduct = $this->ProductRepository->calculatePrice([['id', '=', $request->product_id], ['stock_status', '=', 'in stock']], ['id', 'flag', 'quantity', 'stock_status', 'filter_id', 'tax']);
        if (!empty($offerproduct)&& $offerproduct->filter_id == 8) {
           if($request->quantity==0||$request->quantity==1||$request->quantity == 3||$request->quantity==5){
               $response = [
                   'status' => 201,
                   'message' => "يجب اضافه عدد قطعتين او مضاعفتها لتطبيق العرض",
                   'data' => null,
               ];
               return response()->json($response);
           }
        }
        $productexist = $this->CartRepository->getCartProduct($request->user_id, $request->product_id);
        if (isset($productexist) && !empty($productexist) && !is_null($productexist)) {
            // update it
            $product = $this->CartRepository->getCartProduct($request->user_id, $request->product_id);
            $nproduct = [
                'quantity' => $request->quantity > 0 ? $request->quantity : 1,
                'price' => $request->price,
                'price_after_discount' => $request->price_after_discount
            ];
            $this->CartRepository->updateMyCart($product->id, $nproduct);
        } else {
            $product = json_encode([
                'user_id' => $request->user_id,
                'id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'flag' => $request->flag,
                'price_after_discount' => $request->price_after_discount
            ]);
            $this->CartRepository->safeProduct(json_decode($product), $request->user_id, $request->user_id);
        }
        $cart = $this->updateMyCartHeader($request->user_id);
        return $this->API_RESPONSE->data(["cart" => $cart], 'product added successfully ', 200);
    }

    public function deleteProductFromCart(Request $request)
    {
        $delete = $this->CartRepository->deleteOneCartWithUserProduct($request->user_id, $request->product_id);
        $cart = $this->updateMyCartHeader($request->user_id);
        return $this->API_RESPONSE->data(['product' => [], "cart" => $cart], 'product deleted successfully ', 200);
    }

    public function getMyCart(Request $request)
    {
        $products = $this->CartRepository->getMyCart($request->user_id, $request->user_id);
        $cart = $this->updateMyCartHeader($request->user_id);
        return $this->API_RESPONSE->data(['products' => $products, "cart" => $cart], 'myCart', 200);
    }

    public function saveOrderApp(Request $request)
    {
        $validator = $this->API_VALIDATOR->validateWithNoToken($this->UserValidation->orderRules());
        if ($validator) {
            $errorMessages = [];
            foreach ($validator->messages() as $key => $value) {
                $errorMessages[$key] = ['isError' => true, 'message' => $value[0]];
            }
            foreach (array_keys($this->UserValidation->orderRules()) as $input) {
                if (!in_array($input, array_keys($errorMessages))) {
                    $errorMessages[$input] = ['isError' => false, 'message' => ''];
                }
            }
            return $this->API_RESPONSE->errors($errorMessages, 400);
        }
        $inputs = $request->all();
        $currentuser = $this->UserService->getUser($request->user_id);

        $dateTimestamp1 = strtotime('2023-04-19');
        $dateTimestamp2 = strtotime($currentuser->created_at);
        if ($dateTimestamp2 < $dateTimestamp1) {
            $now = Carbon::now()->toDateTimeString();
            $data = ['created_at' => $now];
            $this->UserService->updateUserRow($data, $currentuser->id);
        }

        $hasDiscount = $currentuser->stage == 2 ? 1 : 0;

        if($inputs['items']){
            // check offer 1 + 1
            foreach ($inputs['items'] as $item) {
                $offerproduct = $this->ProductRepository->calculatePrice([['id', '=', $item['id']], ['stock_status', '=', 'in stock']], ['id', 'flag', 'quantity', 'stock_status', 'filter_id', 'tax']);
                if (!empty($offerproduct) && $offerproduct->filter_id == 8) {
                    if($item['quantity']==0||$item['quantity']==1||$item['quantity'] == 3||$item['quantity']==5){
                        return $this->API_RESPONSE->errors(['products' => "يجب اضافه عدد قطعتين او مضاعفتها لتطبيق العرض"], 400);
                    }
                }
            }
        }
        $checkItemsAvalability = $this->CartService->checkItemsAvalability($inputs['items']);
        if ($checkItemsAvalability && count($checkItemsAvalability) > 0) {
            return $this->API_RESPONSE->errors(['products' => 'products Quantity More than Quantity In stock  Or more than 6 piece'], 400);
        }

        $productsAndTotal = $this->CartService->calculateProducts($inputs['items'], $hasDiscount);
        if (!empty($productsAndTotal) && isset($productsAndTotal['products']) && count($productsAndTotal['products']) > 0) {
            $productsAndTotal['shipping'] = ((float)$productsAndTotal['totalProductsAfterDiscount']) < 250 ? 50 : 0;
            $this->CartService->saveProductsToCart(array_merge($productsAndTotal['products'], $productsAndTotal['giftProducts']), $request->user_id, $request->user_id);
            $this->CartService->saveCartHeader($request->user_id, $request->user_id, $productsAndTotal['totalProducts'], $productsAndTotal['totalProductsAfterDiscount'], $productsAndTotal['shipping'], $productsAndTotal['discount_amount']);

            if (isset($productsAndTotal['userRedeemGift']) && $productsAndTotal['userRedeemGift'] == true) {
                array_push($productsAndTotal['products'], $productsAndTotal['returngift']);
            }

            $ttottalOrder=(float)$productsAndTotal['totalProductsAfterDiscount'] + $productsAndTotal['shipping'];

             $productsAndTotal['use_my_wallet']=isset($inputs['use_my_wallet'])&&$inputs['use_my_wallet']==1?true:false;
             $productsAndTotal['pay_from_my_wallet']=0;
              if(isset($inputs['use_my_wallet'])&&$inputs['use_my_wallet'] == true ){
                 $UserWallet = $this->UserService->getMyUserWallet($request->user_id);
                 if(!empty($UserWallet)&& $UserWallet->total_wallet > 0 &&$UserWallet->current_wallet > 0){
                     if($UserWallet->current_wallet >= $ttottalOrder){
                         $productsAndTotal['pay_from_my_wallet']=$ttottalOrder;
                     }else{
                         $productsAndTotal['pay_from_my_wallet']=(float)($UserWallet->current_wallet);
                     }
                 }
             }

            $data = [
                "user_id" => $currentuser->id,
                "address_id " => intval($inputs['address_id']),
                "shipping_amount" => $productsAndTotal['shipping'],
                'payment_code' => NULL,
                'wallet_status' => $inputs['wallet_status'],
                'total_order' => $ttottalOrder,
                'total_order_has_commission' => $hasDiscount == 1 ? (float)$productsAndTotal['value_will_has_commission'] : 0,
                'totalProducts' => (float)$productsAndTotal['totalProducts'],
                'discount_amount' => $productsAndTotal['discount_amount'],
                'platform' => 'mobile',
                // 'wallet_used_amount' => $productsAndTotal['pay_from_my_wallet'],
                'gift_id' => isset($productsAndTotal['userRedeemGift']) && $productsAndTotal['userRedeemGift'] == true ? $productsAndTotal['userIdGift'] : 0
            ];

            $order = OrderHeader::create($data);
            $order->address_id = intval($inputs['address_id']);
            $order->save();
            if (!empty($order)) {
                $productsAndTotal['order_id'] = $order->id;
                $productsAndTotal['wallet_status'] = $order->wallet_status;
                $productsAndTotal['payment_status'] = $order->payment_status;
                $this->OrderLinesService->createOrderLines($order['id'], $data['user_id'], $data['user_id']);
//                $this->OrderLinesService->deleteCartAndCartHeader($data['user_id'], $data['user_id']);
                if ($inputs['wallet_status'] == 'cash') {
                    $this->OrderLinesService->deleteCartAndCartHeader($data['user_id'], $data['user_id']);
                    // $this->PaymentService->sendOrderToOracle($order->id);
                    if (isset($order->id) && $order->id > 0) {
                        $this->ProductService->updateOrderProductQuntity($order->id);
                    }
                }
                  if(isset($inputs['use_my_wallet'])&&$inputs['use_my_wallet'] == true ){
                     $UserWallet = $this->UserService->getMyUserWallet($request->user_id);
                     if(!empty($UserWallet)){
                       $updaetWallet=[
                           "current_wallet"=>$UserWallet->current_wallet-$productsAndTotal['pay_from_my_wallet'],
                           "used_wallet"=>$UserWallet->used_wallet+$productsAndTotal['pay_from_my_wallet']
                       ];
                         $this->UserService->updateMyUserWallet(['user_id' => $request->user_id],$updaetWallet);
                     }
                 }
            }
            return $this->API_RESPONSE->data(['data' => $productsAndTotal], 'Order Add Success', 200);
        }
        return $this->API_RESPONSE->data(['data' => null], 'no products', 200);
    }


    public function saveOrderAppTest(Request $request)
    {
        $inputs = $request->all();
        $inputs['items'] = [];
        $oorrdders8s = OrderHeader::whereIn('id', [


            28000000011100,
            28000000011101,
            28000000011103,
            28000000011104,
            28000000011105,
            28000000011107,
            28000000011110,
            28000000011111,
            28000000011112,
            28000000011115,
            28000000011116,
            28000000011118,
            28000000011120,
            28000000011121,
            28000000011123,
            28000000011124,
            28000000011125,
            28000000011126,
            28000000011128,
            28000000011129,
            28000000011132,
            28000000011133,
            28000000011136,
            28000000011137

        ])->get();

        if (!empty($oorrdders8s)) {

            foreach ($oorrdders8s as $oorrdders) {
                $request->user_id = $oorrdders->user_id;
                $inputs['address_id'] = $oorrdders->address_id;
                $inputs['wallet_status'] = $oorrdders->wallet_status;
                $iiitteems = OrderLine::where('order_id', $oorrdders->id)->get();
                $inputs['items'] = [];
                foreach ($iiitteems as $ittm) {
                    $opj = ['id' => $ittm->product_id, 'flag' => $ittm->Product->flag, 'excluder_flag' => $ittm->Product->excluder_flag, 'quantity' => $ittm->quantity];
                    array_push($inputs['items'], $opj);
                }
                $currentuser = $this->UserService->getUser($request->user_id);
//        if ($currentuser->created_at < '2023-04-19') {
//            $now  = Carbon::now()->toDateTimeString();
//            $data = ['created_at' => $now];
//            $this->UserService->updateUserRow($data, $currentuser->id);
//        }
                $hasDiscount = $currentuser->stage == 2 ? 1 : 0;

                $checkItemsAvalability = $this->CartService->checkItemsAvalability($inputs['items']);

                if ($checkItemsAvalability && count($checkItemsAvalability) > 0) {
                    return $this->API_RESPONSE->errors(['products' => 'products Quantity More than Quantity In stock  Or more than 6 piece'], 400);
                }

                $productsAndTotal = $this->CartService->calculateProducts($inputs['items'], $hasDiscount);
                if (!empty($productsAndTotal) && isset($productsAndTotal['products']) && count($productsAndTotal['products']) > 0) {
                    $productsAndTotal['shipping'] = ((float)$productsAndTotal['totalProductsAfterDiscount']) < 250 ? 50 : 0;
                    $this->CartService->saveProductsToCart(array_merge($productsAndTotal['products'], $productsAndTotal['giftProducts']), $request->user_id, $request->user_id);
                    $this->CartService->saveCartHeader($request->user_id, $request->user_id, $productsAndTotal['totalProducts'], $productsAndTotal['totalProductsAfterDiscount'], $productsAndTotal['shipping'], $productsAndTotal['discount_amount']);
                    if (isset($productsAndTotal['userRedeemGift']) && $productsAndTotal['userRedeemGift'] == true) {
                        array_push($productsAndTotal['products'], $productsAndTotal['returngift']);
                    }

                    $str = (string)$oorrdders->id;
                    $pattern = '/00/i';
                    $newid = preg_replace($pattern, '0', $str);

                    $data = [
                        "user_id" => $currentuser->id,
                        "address_id " => intval($inputs['address_id']),
                        "shipping_amount" => $productsAndTotal['shipping'],
                        'payment_code' => NULL,
                        'wallet_status' => $inputs['wallet_status'],
                        'total_order' => (float)$productsAndTotal['totalProductsAfterDiscount'] + $productsAndTotal['shipping'],
                        'total_order_has_commission' => $hasDiscount == 1 ? (float)$productsAndTotal['value_will_has_commission'] : 0,
                        'totalProducts' => (float)$productsAndTotal['totalProducts'],
                        'discount_amount' => $productsAndTotal['discount_amount'],
                        'platform' => $oorrdders->platform,
                        'admin_id' => $oorrdders->id,
                        'gift_id' => isset($productsAndTotal['userRedeemGift']) && $productsAndTotal['userRedeemGift'] == true ? $productsAndTotal['userIdGift'] : 0
                    ];

                    $order = OrderHeader::create($data);
                    $order->id = (int)($newid);
                    $order->address_id = intval($inputs['address_id']);
                    $order->save();
                    if (!empty($order)) {
                        $productsAndTotal['order_id'] = $order->id;
                        $productsAndTotal['wallet_status'] = $order->wallet_status;
                        $productsAndTotal['payment_status'] = $order->payment_status;
                        $this->OrderLinesService->createOrderLines($order['id'], $data['user_id'], $data['user_id']);
//                $this->OrderLinesService->deleteCartAndCartHeader($data['user_id'], $data['user_id']);
                        if ($inputs['wallet_status'] == 'cash') {
                            $this->OrderLinesService->deleteCartAndCartHeader($data['user_id'], $data['user_id']);
                            // $this->PaymentService->sendOrderToOracle($order->id);
//                    if (isset($order->id) && $order->id > 0) {
//                        $this->ProductService->updateOrderProductQuntity($order->id);
//                    }
                        }
                    }
                }
            }


        }
        return $this->API_RESPONSE->data(['data' => null], 'Order Add Success', 200);
//        return $this->API_RESPONSE->data(['data' => null], 'no products', 200);
    }


    public function getOrderCheckout(Request $request)
    {
        $validator = $this->API_VALIDATOR->validateWithNoToken($this->UserValidation->orderCheckRules());
        if ($validator) {
            $errorMessages = [];
            foreach ($validator->messages() as $key => $value) {
                $errorMessages[$key] = ['isError' => true, 'message' => $value[0]];
            }
            foreach (array_keys($this->UserValidation->orderCheckRules()) as $input) {
                if (!in_array($input, array_keys($errorMessages))) {
                    $errorMessages[$input] = ['isError' => false, 'message' => ''];
                }
            }
            return $this->API_RESPONSE->errors($errorMessages, 400);
        }

        $inputs = $request->all();
        $currentuser = $this->UserService->getUser($request->user_id);
        $hasDiscount = $currentuser->stage == 2 ? 1 : 0;


        if($inputs['items']){
            // check offer 1 + 1
            foreach ($inputs['items'] as $item) {
                $offerproduct = $this->ProductRepository->calculatePrice([['id', '=', $item['id']], ['stock_status', '=', 'in stock']], ['id', 'flag', 'quantity', 'stock_status', 'filter_id', 'tax']);
                if (!empty($offerproduct) && $offerproduct->filter_id == 8) {
                    if($item['quantity']==0||$item['quantity']==1||$item['quantity'] == 3||$item['quantity']==5){
                        return $this->API_RESPONSE->errors(['products' => "يجب اضافه عدد قطعتين او مضاعفتها لتطبيق العرض"], 400);
                    }
                }
            }
        }

        $checkItemsAvalability = $this->CartService->checkItemsAvalability($inputs['items']);
        if ($checkItemsAvalability && count($checkItemsAvalability) > 0) {
            return $this->API_RESPONSE->errors(['products' => 'products Quantity More than Quantity In stock  Or more than 6 piece'], 400);
        }
        $productsAndTotal = $this->CartService->calculateProducts($inputs['items'], $hasDiscount);
        if (!empty($productsAndTotal)) {
            $productsAndTotal['shipping'] = ((float)$productsAndTotal['totalProductsAfterDiscount']) < 250 ? 50 : 0;
            $productsAndTotal['total_order_final'] = ((float)$productsAndTotal['totalProductsAfterDiscount']) + $productsAndTotal['shipping'];
            $productsAndTotal['value_will_has_commission'] = $hasDiscount == 1 ? (float)$productsAndTotal['value_will_has_commission'] : 0;
        }
        $gift = null;

        if ($hasDiscount == 1) {
            $userHasReceivedGift = $this->UserService->userHasReceivedGift([$currentuser->id]);
            $myOldPaidOrderThisMonthTotal = $this->UserService->getMyTeamTotalSales([$currentuser->id], null, null, 'month');

            if (((float)$productsAndTotal['totalProductsAfterDiscount'] + $myOldPaidOrderThisMonthTotal) >= 250 && $userHasReceivedGift == false) {

                $gift = $this->checkUserDeserveGift($currentuser->id, $currentuser->created_at);

                if (!empty($gift)) {
                    $gift->flag = 5;
                    $gift->excluder_flag = "N";
                    $gift->old_price = $gift->total_old_price;
                    $gift->old_discount = round((($gift->total_old_price - $gift->total_price) * 100) / $gift->total_old_price);
                    $gift->discount_rate = round((($gift->total_old_price - $gift->total_price) * 100) / $gift->total_old_price);
                    $gift->full_name = $gift->name_ar;
                    $gift->description_en = $gift->name_en;
                    $gift->description_ar = $gift->name_ar;
                    $gift->oracle_short_code = "1100110011";
                    $gift->price_after_discount = $gift->total_price;
                    $gift->price = $gift->total_price;
                    $gift->quantity = 1;
                    $gift->stock_status = "in stock";
                    $gift->userRedeemGift = true;
                }
            }
        }
        $productsAndTotal['gift'] = $gift;
        $conditions = ['user_addresses.user_id', '=', $currentuser->id];
        $address = $this->UserService->getMyMainAddresse($conditions);
        if (!empty($address)) {
            $productsAndTotal['address'] = $this->UserService->getMyMainAddresse($conditions);
        }
        $total = $this->CartRepository->getTotalProducts($currentuser->id);
        if ($total <= 0) {
            $productsAndTotal['products'] = [];
        }
        if (!empty($productsAndTotal) && isset($productsAndTotal['products']) && count($productsAndTotal['products']) > 0) {
            foreach ($productsAndTotal['products'] as $rProduct) {
                $rProduct->old_price = $rProduct->price;
                $rProduct->price = $rProduct->price_after_discount;
                $rProduct->old_discount = $rProduct->discount_rate;
            }
            if (isset($productsAndTotal['userRedeemGift']) && $productsAndTotal['userRedeemGift'] == true) {
                array_push($productsAndTotal['products'], $productsAndTotal['returngift']);
            }
        }
        if (isset($productsAndTotal['userRedeemGift']) && $productsAndTotal['userRedeemGift'] == true) {
            $productsAndTotal['gift'] = null;
            unset($productsAndTotal['giftProducts']);
            unset($productsAndTotal['returngift']);
        }

         $productsAndTotal['use_my_wallet']=isset($inputs['use_my_wallet'])&&$inputs['use_my_wallet']==1?true:false;;
         $productsAndTotal['pay_from_my_wallet']=0;
          if(isset($inputs['use_my_wallet'])&&$inputs['use_my_wallet'] == true ){
             $UserWallet = $this->UserService->getMyUserWallet($request->user_id);
           if(!empty($UserWallet)&& $UserWallet->total_wallet > 0 &&$UserWallet->current_wallet > 0){
               if($UserWallet->current_wallet >= $productsAndTotal['total_order_final']){
                   $productsAndTotal['pay_from_my_wallet']=$productsAndTotal['total_order_final'];
                   $productsAndTotal['total_order_final']=0;
               }else{
                   $productsAndTotal['pay_from_my_wallet']=(float)($UserWallet->current_wallet);
                   $productsAndTotal['total_order_final']= round((float)($productsAndTotal['total_order_final']-(float)$UserWallet->current_wallet), 2);
               }
           }
         }



        return $this->API_RESPONSE->data($productsAndTotal, 'check out', 200);
    }


    public function updateMyCartHeader($user_id)
    {
        $total = $this->CartRepository->getTotalProducts($user_id);
        $totalAfterDiscount = $this->CartRepository->getTotalProductsAfter($user_id);
        $shipping_amount = $total >= 250 ? 0 : 50;
        $discount_amount = $total - $totalAfterDiscount;
        $this->CartRepository->deleteCartHeader($user_id, $user_id);
        $cart = $this->CartRepository->safeCartHeader($user_id, $user_id, $total, $totalAfterDiscount, $shipping_amount, $discount_amount);
        $newCart = [
            "user_id" => $cart->user_id,
            "total_products_price" => $cart->total_products_price,
            "shipping_amount" => $cart->shipping_amount,
            "total" => ($cart->total_products_price + $cart->shipping_amount),
            "vip_total" => ($cart->total_products_after_discount),
        ];
        return $newCart;
    }

    public function checkUserDeserveGift($user_id, $created_at)
    {
        return $this->UserService->checkUserDeserveGift($user_id, $created_at);
    }


}
