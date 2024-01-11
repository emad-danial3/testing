<?php

namespace App\Http\Services;

use App\Constants\OrderStatus;
use App\Constants\OrderTypes;
use App\Constants\PaymentNames;
use App\Http\Controllers\Application\UserDashboardController;
use App\Models\WelcomeProgramProduct;
use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\PaymentLogRepository;
use App\Http\Repositories\IUserWalletRepository;
use App\Http\Services\PaidOrderActions\SingleOrderPaidActions;
use App\Http\Repositories\IUserRepository;
use App\Mail\checkGiftEmail;
use App\Models\OrderHeader;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
class PaymentService extends BaseServiceController
{
    private   $PaymentLogRepository;
    private   $OrderHeaderRepository;
    private   $SingleOrderPaidActions;
    private   $UserWalletRepository;
    private   $OrderRepository;
    private   $UserRepository;
    private   $CommissionService;
    private   $UserDashboardController;
    private   $ProductService;
    protected $CartService;

    public function __construct(PaymentLogRepository    $PaymentLogRepository,
                                CartService             $CartService,
                                OrderRepository         $OrderRepository,
                                OrderRepository         $OrderHeaderRepository,
                                SingleOrderPaidActions  $SingleOrderPaidActions,
                                IUserRepository         $UserRepository,
                                CommissionService       $CommissionService,
                                UserDashboardController $UserDashboardController,
                                ProductService          $ProductService,
                                IUserWalletRepository   $UserWalletRepository)
    {
        $this->PaymentLogRepository    = $PaymentLogRepository;
        $this->OrderHeaderRepository   = $OrderHeaderRepository;
        $this->SingleOrderPaidActions  = $SingleOrderPaidActions;
        $this->UserWalletRepository    = $UserWalletRepository;
        $this->OrderRepository         = $OrderRepository;
        $this->UserRepository          = $UserRepository;
        $this->CommissionService       = $CommissionService;
        $this->UserDashboardController = $UserDashboardController;
        $this->ProductService          = $ProductService;
        $this->CartService             = $CartService;
    }

    public function saveRequest($paymentData)
    {
        $paymentData['orderItems'] = json_encode($paymentData['orderItems'], true);
        $this->PaymentLogRepository->create($paymentData);
    }

    public function findOrderHeaderId($orderItems)
    {
        $idds = [];
        foreach ($orderItems as $product) {
            array_push($idds, $product['itemCode']);
            if (strpos($product['itemCode'], PaymentNames::ORDER_HEADER_ID_SUB_STRING) !== false) {
                $order = explode("-", $product['itemCode']);
                return $order[1];
            }
        }
        $orderh = OrderHeader::whereIn('id', $idds)->first();
        if ($orderh) {
            return $orderh->id;
        }
        else {
            $lastItem = end($orderItems);
            return $lastItem['itemCode'];
        }
    }

    public function canChangeOrderStatus($order_id): int
    {
        $orderHeader = $this->OrderHeaderRepository->find($order_id, ['payment_status']);
        if (!empty($orderHeader) && $orderHeader->payment_status == OrderStatus::PENDING)
            return 1;
        return 0;
    }

    public function payOrder($orderHeader, $payment_code = null)
    {
        $this->sendOrderToOracle($orderHeader['id']);
        if (isset($orderHeader['id']) && $orderHeader['id'] > 0) {
            $this->ProductService->updateOrderProductQuntity($orderHeader['id']);
            $this->CommissionService->createCommission($orderHeader);
            $this->UserDashboardController->calculateMyMonthlyCommission($orderHeader['user_id']);
            $this->UserDashboardController->calculateToMyParentMonthlyCommission($orderHeader['user_id']);
        }
        $this->OrderHeaderRepository->updateOrder(['id' => $orderHeader['id']], ['payment_status' => OrderStatus::PAID, 'payment_code' => $payment_code]);
    }

    public function expiredOrder($orderHeader)
    {
        if ($orderHeader['wallet_used_amount'] > 0) {
            $this->OrderHeaderRepository->updateOrder(['id' => $orderHeader['id']], ['payment_status' => OrderStatus::DELETED]);
            $this->UserWalletRepository->updateWalletWhenExpired($orderHeader['user_id'], $orderHeader['wallet_used_amount']);
        }
        $this->OrderHeaderRepository->updateOrder(['id' => $orderHeader['id']], ['payment_status' => OrderStatus::EXPIRED]);
    }

    public function updateOrderPaymentNumber($order_header_id, $payment_code)
    {
        $this->OrderHeaderRepository->updateOrder(['id' => $order_header_id], ['payment_code' => $payment_code]);
    }

//calculateMinRequired
    public function sendOrderToOracle($order_id)
    {

        $OrderLines                 = $this->OrderRepository->getOrder($order_id);
        $user_id                    = OrderHeader::where('id', $order_id)->first()['user_id'];
        $total_order_has_commission = OrderHeader::where('id', $order_id)->first()['totalProducts'];
        $hasDiscount                = 0;
        $currentuser                = User::find($user_id);
        if (!empty($currentuser)) {
            $hasDiscount          = $currentuser->stage == 2 ? 1 : 0;
            $discountLevel        = $this->CartService->getOrderDiscount($total_order_has_commission);
            $discountCosmeticFood = 0;
            $discountNotCosmetic  = 0;
            if (!empty($discountLevel)) {
                $discountNotCosmetic  = (float)$discountLevel->monthly_immediate_discount;
                $discountCosmeticFood = (float)$discountLevel->food_discount;
            }
        }


        $newValue    = [];
        $paymentCode = [];
        $max         = 0;

        if (count($OrderLines) > 0) {
            foreach ($OrderLines as $orderLine) {


        //   $aItemtrray=[];
        //     foreach ($OrderLines as $orddderLine) {
        //         $itm=['id'=>$orddderLine->id,'quantity'=>$orddderLine->quantity,'flag'=>$orddderLine->flag,'orecal_number'=>$orddderLine->orecal_number];
        //         array_push($aItemtrray,$itm);
        //     }
        //     $checkItemsAvalability = $this->CartService->checkItemsAvalability($aItemtrray);
        //     if ($checkItemsAvalability && count($checkItemsAvalability) > 0) {
        //         $message='products Quantity More than Quantity In stock  Or more than 6 piece order_number '.$aItemtrray[0]['orecal_number'];
        //         Log::info($message);
        //         return 0;
        //     }


                if (!array_key_exists($orderLine->payment_code, $paymentCode)) {
                    $max                                       += 1;
                    $OrderTypesArray[$orderLine->payment_code] = $max;
                }
                else {
                    $orderLine->has_free_product = 0;
                }

                if ($hasDiscount == 1) {
                    if (isset($orderLine->is_gift) && $orderLine->is_gift > 0) {
                        if ($orderLine->discount_rate == 70) {
                            $orderLine->offer_flag = '28';
                        }
                        elseif ($orderLine->discount_rate == 75) {
                            $orderLine->offer_flag = '29';
                        }
                        elseif ($orderLine->discount_rate == 80) {
                            $orderLine->offer_flag = '30';
                        }
                        else {
                            $orderLine->offer_flag = '0';
                        }
                    }
                    elseif ($orderLine->excluder_flag == 'Y') {
                        $orderLine->offer_flag = '0';
                    }
                  /*  elseif ($orderLine->filter_id == '8') {
                        $orderLine->offer_flag = '0';
                    }*/
                    else {
                        if ($orderLine->flag == 5 || $orderLine->flag == 9) {
                            if ($discountCosmeticFood == 10) {
                                $orderLine->offer_flag = '23';
                            }
                            elseif ($discountCosmeticFood == 15) {
                                $orderLine->offer_flag = '24';
                            }
                            elseif ($discountCosmeticFood == 20) {
                                $orderLine->offer_flag = '25';
                            }
                            elseif ($discountCosmeticFood == 25) {
                                $orderLine->offer_flag = '26';
                            }
                            else {
                                $orderLine->offer_flag = '0';
                            }
                        }
                        elseif ($orderLine->flag == 7 || $orderLine->flag == 23) {
                            if ($discountNotCosmetic == 10) {
                                $orderLine->offer_flag = '23';
                            }
                            elseif ($discountNotCosmetic == 15) {
                                $orderLine->offer_flag = '24';
                            }
                            elseif ($discountNotCosmetic == 20) {
                                $orderLine->offer_flag = '25';
                            }
                            elseif ($discountNotCosmetic == 25) {
                                $orderLine->offer_flag = '26';
                            }
                            elseif ($discountNotCosmetic == 30) {
                                $orderLine->offer_flag = '27';
                            }
                            else {
                                $orderLine->offer_flag = '0';
                            }
                        }

                    }
                }
                else {
                    $orderLine->offer_flag = '0';
                }

                $newValue[] = $orderLine;

            }
            // dd($newValue);
            try {
                Log::info("send order to oracle");
                $client   = new \GuzzleHttp\Client();
                $response = $client->request('POST', 'https://sales.atr-eg.com/api/save_order_nettinghub4u.php', [
                    'form_params' => [
                        'order_lines' => $newValue
                    ], 'verify'   => false]);

                Log::info($response->getBody()->getContents());
                if ($response->getStatusCode() == 200) {
                    $updateOrder = OrderHeader::where('id', $order_id)->first();
                    if ($updateOrder) {
                        $updateOrder->send_t_o = '1';
                        $updateOrder->save();
                    }
                }

//                Log::critical('respons is ::' . $response->getBody()->getContents());
            }
            catch (\Exception $e) {
                Log::error('sending ORDER to oracle ORDER_ID=' . $order_id . ' ERROR::' . $e->getMessage());
                $code = $e->getCode();
                if ($code == 503) {

                    $userRow        = $this->UserRepository->find($user_id, ['account_id', 'full_name', 'phone', 'nationality_id', 'address','created_at','send_to_oracle']);
//                    $olddat= Carbon::now()->subDays(2)->toDateTimeString();
//                    if(Carbon::parse($userRow->created_at)->toDateTimeString() < $olddat && $userRow['send_to_oracle'] == '0'){
//                        $client         = new \GuzzleHttp\Client();
//                        $data           = [
//                            'account_id'     => $userRow->account_id,
//                            'full_name'      => $userRow->full_name,
//                            'mobile'         => $userRow->phone,
//                            'nationality_id' => $userRow->nationality_id,
//                            'address'        => $this->faTOen($userRow->address) ?? "9 El sharekat, Opera",
//                        ];
//                        $this->response = $client->request('POST', 'https://sales.atr-eg.com/api/save_user_nettinghub_4uNew.php', ['form_params' => $data, 'verify' => false])->getBody()->getContents();
//                        User::where('id', $user_id)->update(['send_to_oracle' =>'1']);
//
//                        Log::info($this->response);
//                    }else{
                        $client         = new \GuzzleHttp\Client();
                        $data           = [
                            'account_id'     => $userRow->account_id,
                            'full_name'      => $userRow->full_name,
                            'mobile'         => $userRow->phone,
                            'nationality_id' => $userRow->nationality_id,
                            'address'        => $this->faTOen($userRow->address) ?? "9 El sharekat, Opera",
                        ];
                        $this->response = $client->request('POST', 'https://sales.atr-eg.com/api/save_user_nettinghub_4u.php', ['form_params' => $data, 'verify' => false])->getBody()->getContents();
                        Log::info($this->response);
//                    }

                }
//                Log::critical('sending ORDER to oracle ORDER_ID=' . $order_id . ' ERROR::' . $e->getMessage());
            }
        }
        else
            Log::info('successfully added out of oracle');
    }

    public function sendOrderToOracleEventNettingHup($order_id)
    {
        $OrderLines  = $this->OrderRepository->getOrder($order_id);
        $newValue    = [];
        $paymentCode = [];
        $max         = 0;

        if (count($OrderLines) > 0) {
            foreach ($OrderLines as $orderLine) {
                if (!array_key_exists($orderLine->payment_code, $paymentCode)) {
                    $max                                       += 1;
                    $OrderTypesArray[$orderLine->payment_code] = $max;
                }
                else {
                    $orderLine->has_free_product = 0;
                }

                if ($orderLine->excluder_flag == 'Y') {
                    $orderLine->offer_flag = '0';
                }

                $newValue[] = $orderLine;
            }
//            dd($newValue);
            try {
//                Log::info("send order to oracle event");
                $client   = new \GuzzleHttp\Client();
                $response = $client->request('POST', 'https://sales.atr-eg.com/api/save_order_nettinghub4uEvent.php', [
                    'form_params' => [
                        'order_lines' => $newValue
                    ], 'verify'   => false]);

//                Log::info($response->getBody()->getContents());
                if ($response->getStatusCode() == 200) {
                    $updateOrder = OrderHeader::where('id', $order_id)->first();
                    if ($updateOrder) {
                        $updateOrder->send_t_o = '1';
                        $updateOrder->save();
                    }
                }

//                Log::critical('respons is ::' . $response->getBody()->getContents());
            }
            catch (\Exception $e) {
//                Log::error('sending ORDER to oracle ORDER_ID=' . $order_id . ' ERROR::' . $e->getMessage());

//                Log::critical('sending ORDER to oracle ORDER_ID=' . $order_id . ' ERROR::' . $e->getMessage());
            }
        }
        else
            Log::info('successfully added out of oracle');
    }

    public function sendOrderToOracleThatNotSending()
    {
        $ordersNotSend = OrderHeader::where(function ($query) {
            $query->where('payment_status', 'PAID')->where('wallet_status', 'only_fawry');
        })->where('user_id', '!=', '1')->where('user_id', '!=', '2')->where('send_t_o', '0')->where('wallet_status', 'only_fawry')->where('created_at', '>', Carbon::now()->subDays(2))->pluck('id')->toArray();
        foreach ($ordersNotSend as $orderId) {
            $this->sendOrderToOracle($orderId);
        }
    }
    public function sendOrderToOracleChashThatNotSending()
    {
        $ordersNotSend = OrderHeader::where('user_id', '!=', '1')->where('user_id', '!=', '2')->where('platform', '!=', 'onLine')->where('wallet_status', 'cash')->where('send_t_o', '0')->where('order_status', 'pending')->where('created_at', '>', Carbon::now()->subDays(30))->orderBy('id', 'desc')->limit(20)->pluck('id')->toArray();
        foreach ($ordersNotSend as $orderId) {
            $this->sendOrderToOracle($orderId);
        }
    }

    public function checkGiftProductsAvailability()
    {

        $pproducts = DB::table('products')
            ->join('welcome_program_product_details', 'welcome_program_product_details.product_id', '=', 'products.id')
            ->where('products.quantity', '<', 500)
            ->select('products.full_name', 'products.oracle_short_code', 'products.barcode', 'products.quantity')
            ->groupBy('products.id')
            ->orderBy('products.id')
            ->get();

        $pproducts15 = DB::table('products')
            ->join('welcome_program_product_details', 'welcome_program_product_details.product_id', '=', 'products.id')
            ->where('products.quantity', '<', 15)
            ->select('products.full_name', 'products.oracle_short_code', 'products.barcode', 'products.quantity')
            ->groupBy('products.id')
            ->orderBy('products.id')
            ->get();

        if (count($pproducts) > 0) {
                $emailData = [
                    "subject"  => "Check Gift Products Availability Mail",
                    "email"    => 'samah.ismail@atr-eg.com',
                    "products" => $pproducts,
                ];
                // send Email

                $toEmails = ['samah.ismail@atr-eg.com', 'inji.mahmoud@atr-eg.com', 'madonna.saad@atr-eg.com', 'melisiamorad.atr@yahoo.com', 'antony.alfreid@atr-eg.com', 'mirna.salah@atr-eg.com','mohasep.adeib@atr-eg.com','mario.nasser@atr-eg.com'];
                Mail::to($toEmails)->send(new checkGiftEmail($emailData));
        }
        if (count($pproducts15) > 0) {

            WelcomeProgramProduct::where('id','>',0)->update(['status'=>'0']);

                $emailData = [
                    "subject"  => "Check Gift Products Availability Mail",
                    "email"    => 'samah.ismail@atr-eg.com',
                    "products" => $pproducts,
                ];
                // send Email
                $toEmails = ['samah.ismail@atr-eg.com', 'inji.mahmoud@atr-eg.com', 'madonna.saad@atr-eg.com', 'melisiamorad.atr@yahoo.com', 'antony.alfreid@atr-eg.com', 'mirna.salah@atr-eg.com','mohasep.adeib@atr-eg.com','mario.nasser@atr-eg.com'];
                Mail::to($toEmails)->send(new checkGiftEmail($emailData));
        }else{
            WelcomeProgramProduct::where('id','>',0)->update(['status'=>'1']);
        }
        return '';
    }

    public function sendOrderToOracleNotSending($orderId)
    {
        $this->sendOrderToOracle($orderId);
    }

    public function sendOrderToOracleOnline($orderId)
    {
        $this->sendOrderToOracleEventNettingHup($orderId);
    }

    public function faTOen($string)
    {
        return strtr($string, array('۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9', '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'));
    }
}
