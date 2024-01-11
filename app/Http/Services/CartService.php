<?php

namespace App\Http\Services;

use App\Constants\OrderTypes;
use App\Constants\ProductStatus;
use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\CartRepository;

use App\Http\Repositories\ProductRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Repositories\AccountTypeRepository;
use App\Http\Repositories\UserWalletRepository;
use App\Models\OrderHeader;
use App\Models\OrderLine;
use App\Models\WelcomeProgramProduct;
use phpDocumentor\Reflection\Types\Integer;
use Illuminate\Support\Facades\Http;

use Carbon\Carbon;

class CartService extends BaseServiceController
{

    private $CartRepository;
    private $UserRepository;
    private $UserWalletRepository;
    private $ProductRepository;
    public  $orderType;
    private $OrderRepository;
    public  $AccountTypeRepository;

    public function __construct(ProductRepository $ProductRepository, OrderRepository $OrderRepository, CartRepository $CartRepository, UserRepository $UserRepository, UserWalletRepository $UserWalletRepository, AccountTypeRepository $AccountTypeRepository)
    {
        $this->CartRepository        = $CartRepository;
        $this->UserRepository        = $UserRepository;
        $this->OrderRepository          = $OrderRepository;
        $this->UserWalletRepository  = $UserWalletRepository;
        $this->ProductRepository     = $ProductRepository;
        $this->AccountTypeRepository = $AccountTypeRepository;
    }

    public function calculateProducts($productsData, $hasDiscount): array
    {
        $totalExcludersProducts                 = 0;
        $totalOffersProducts                    = 0;
        $subTotal                               = 0;
        $totalProducts                          = 0;
        $totalProductsCosmeticsAndFood          = 0;
        $totalProductsNotCosmeticsOrFood        = 0;
        $totalProductsAfterDiscountCosmetics    = 0;
        $totalProductsAfterDiscountNotCosmetics = 0;
        $totalProductsAfterDiscount             = 0;
        $value_will_has_commission              = 0;
        $discountExcludersProductsAmount        = 0;
        $discountPercentage                     = 0;
        $discountCosmeticFood                   = 0;
        $discountNotCosmetic                    = 0;
        $gift                                   = null;
        $returngift                             = null;
        $userRedeemGift                         = false;
        $userIdGift                             = 0;
        $giftProducts                           = [];

        $returnedExcludersProducts = [];
        $returnedOffersProducts = [];
        $returnedProduct           = [];
        $outStockProducts          = [];

        foreach ($productsData as $item) {
            if (isset($item['userRedeemGift']) && $item['userRedeemGift'] == true) {
                $userHasReceivedGift          = $this->OrderRepository->userHasReceivedGift(request()->user_id);
               if($userHasReceivedGift==false){
                   $welcomeProgram = WelcomeProgramProduct::with('product')->find($item['id']);
                   if (!empty($welcomeProgram)) {
                       if (!empty($welcomeProgram->product) && count($welcomeProgram->product) > 0) {
                           $gift                               = $welcomeProgram;
                           $userRedeemGift                     = true;
                           $userIdGift                         = $item['id'];
                           $returngift                         = $item;
                           $returngift['flag']                 = 5;
                           $returngift['excluder_flag']        = "N";
                           $returngift['old_price']            = $gift->total_old_price;
                           $returngift['old_discount']         = round((($gift->total_old_price - $gift->total_price) * 100) / $gift->total_old_price);
                           $returngift['discount_rate']        = round((($gift->total_old_price - $gift->total_price) * 100) / $gift->total_old_price);
                           $returngift['full_name']            = $gift->name_ar;
                           $returngift['description_en']       = $gift->name_en;
                           $returngift['name_en']              = $gift->name_en;
                           $returngift['name_ar']              = $gift->name_ar;
                           $returngift['description_ar']       = $gift->name_ar;
                           $returngift['oracle_short_code']    = "1100110011";
                           $returngift['price_after_discount'] = $gift->total_price;
                           $returngift['price']                = $gift->total_price;
                           $returngift['quantity']             = 1;
                           $returngift['image']                = $gift->image;
                           $returngift['stock_status']         = "in stock";


                           $returngift['userRedeemGift'] = true;

                           foreach ($welcomeProgram->product as $pro) {

                               $product = $this->ProductRepository->calculatePrice([['id', '=', $pro->product_id], ['stock_status', '=', ProductStatus::IN_STOCK]], ['id', 'flag', 'excluder_flag', 'old_price', 'old_discount', 'full_name', 'name_en', 'name_ar', 'description_en',
                                   'description_ar', 'image', 'oracle_short_code', 'discount_rate', 'price', 'price_after_discount', 'quantity', 'stock_status']);
                               if (!empty($product)) {
                                   $product->quantity             = 1;
                                   $product->price                = (float)$pro->price;
                                   $product->discount_rate        = (float)$pro->discount_rate;
                                   $product->price_after_discount = (float)$pro->price_after_discount;
                                   $product->is_gift              = 1;
                                   $subTotal                      += $product->price * $product->quantity;
                                   $giftProducts[]                = $product;
                               }

                           }
                       }
                   }
               }
            }
            else {
                $product = $this->ProductRepository->calculatePrice([['id', '=', $item['id']], ['quantity', '>', 0], ['quantity', '>=', $item['quantity']], ['stock_status', '=', ProductStatus::IN_STOCK]], ['id', 'flag', 'excluder_flag', 'old_price', 'old_discount', 'full_name', 'name_en', 'name_ar', 'description_en',
                                                                                                                                                                                                              'description_ar', 'image', 'oracle_short_code', 'discount_rate',
                                                                                                                                                                                                              'price', 'price_after_discount', 'quantity', 'stock_status','filter_id','tax']);
                if (!empty($product)) {
                    if ($product->excluder_flag == 'Y') {
                        $product->quantity               = $item['quantity'];
                        $product->price_after_discount   = isset($item['price_after_discount']) ? (float)$item['price_after_discount'] : $product->price_after_discount;
                        $totalExcludersProducts          += $product->price_after_discount * $product->quantity;
                        $subTotal                        += $product->price * $product->quantity;
                        $returnedExcludersProducts[]     = $product;
                        $discountExcludersProductsAmount += (($product->price - $product->price_after_discount) * $product->quantity);
                    }
//                    elseif($product->filter_id==8){
//                        // this offer 1 + 1 Soliteint
//                        $product->quantity               = $item['quantity'];
//                        $ppTax=($product->price*($product->tax/100));//14
//                        $offerPrice=($product->price+($ppTax));
//                        $totalOffersProducts          += ($offerPrice*$product->quantity);
//                        $product->price_after_discount=$offerPrice;
//                        $product->discount_rate=0;
//                        $subTotal                     += $offerPrice;
//                        $returnedOffersProducts[]     = $product;
//                    }
                    else {
                        $product->quantity = $item['quantity'];
                        if($product->filter_id==8){
                            if ($product->quantity==3 || $product->quantity==4){
                                $product->quantity=4;
                            }elseif ($product->quantity==5||$product->quantity==6){
                                $product->quantity=6;
                            }else{
                                $product->quantity=2;
                            }
                        }
                        $totalProducts     += $product->price * $product->quantity;
                        $subTotal          += $product->price * $product->quantity;

                        if ($product->flag == 5 || $product->flag == 9) {
                            $totalProductsCosmeticsAndFood += $product->price * $product->quantity;
                        }
                        else {
                            $totalProductsNotCosmeticsOrFood += $product->price * $product->quantity;
                        }
                        $product->price_after_discount = isset($item['price_after_discount']) ? (float)$item['price_after_discount'] : $product->price_after_discount;
                        $returnedProduct[]             = $product;
                    }
                }
                else {
                    $product = $this->ProductRepository->calculatePrice([['id', '=', $item['id']], ['quantity', '>', 0], ['quantity', '<', $item['quantity']], ['stock_status', '=', ProductStatus::IN_STOCK]], ['id', 'flag', 'excluder_flag', 'old_price', 'old_discount', 'full_name', 'name_en', 'name_ar', 'description_en',
                                                                                                                                                                                                                 'description_ar', 'image', 'oracle_short_code', 'discount_rate', 'price', 'price_after_discount', 'quantity', 'stock_status','filter_id','tax']);
                    if (!empty($product)) {

                        if ($product->excluder_flag == 'Y') {
                            $item['quantity']                = $product->quantity;
                            $totalExcludersProducts          += (float)$product->price_after_discount * $product->quantity;
                            $subTotal                        += $product->price * $product->quantity;
                            $returnedExcludersProducts[]     = $product;
                            $discountExcludersProductsAmount += (($product->price - $product->price_after_discount) * $product->quantity);
                        }
//                        elseif($product->filter_id==8){
//                            // this offer 1 + 1 Soliteint
//                            $product->quantity               = $item['quantity'];
//                            $ppTax=($product->price*($product->tax/100));//14
//                            $offerPrice=($product->price+($ppTax));
//                            $totalOffersProducts          += ($offerPrice*$product->quantity);
//                            $product->price_after_discount=$offerPrice;
//                            $product->discount_rate=0;
//                            $subTotal                     += $offerPrice;
//                            $returnedOffersProducts[]     = $product;
//                        }
                        else {
                            $item['quantity'] = $product->quantity;
                            if($product->filter_id==8){
                                if ($product->quantity==3 || $product->quantity==4){
                                    $product->quantity=4;
                                }elseif ($product->quantity==5||$product->quantity==6){
                                    $product->quantity=6;
                                }else{
                                    $product->quantity=2;
                                }
                            }
                            $totalProducts    += $product->price * $product->quantity;
                            $subTotal         += $product->price * $product->quantity;
                            if ($product->flag == 5 || $product->flag == 9) {
                                $totalProductsCosmeticsAndFood += $product->price * $product->quantity;
                            }
                            else {
                                $totalProductsNotCosmeticsOrFood += $product->price * $product->quantity;
                            }
                            $product->price_after_discount = isset($item['price_after_discount']) ? (float)$item['price_after_discount'] : $product->price_after_discount;
                            $returnedProduct[]             = $product;
                        }
                    }
                    else {
                        $product            = $this->ProductRepository->calculatePrice(['id' => $item['id'], 'stock_status' => ProductStatus::OUT_STOCK], ['id', 'flag', 'excluder_flag', 'old_price', 'old_discount', 'full_name', 'name_en', 'name_ar', 'description_en',
                                                                                                                                                           'description_ar', 'image', 'oracle_short_code', 'discount_rate', 'price', 'price_after_discount', 'quantity', 'stock_status','filter_id','tax']);
                        $outStockProducts[] = $product;
                    }
                }
            }

        }


        if ($hasDiscount == 1) {
            $discountLevel = $this->getOrderDiscount($totalProducts);

            if (!empty($discountLevel)) {
                $discountNotCosmetic                    = (float)$discountLevel->monthly_immediate_discount;
                $discountCosmeticFood                   = (float)$discountLevel->food_discount;
                $totalProductsAfterDiscountCosmetics    = ((float)$totalProductsCosmeticsAndFood - ((float)$totalProductsCosmeticsAndFood * $discountCosmeticFood / 100));
                $totalProductsAfterDiscountNotCosmetics = ((float)$totalProductsNotCosmeticsOrFood - ((float)$totalProductsNotCosmeticsOrFood * $discountNotCosmetic / 100));
            }
            else{
                $totalProductsAfterDiscountCosmetics    = $totalProductsCosmeticsAndFood;
                $totalProductsAfterDiscountNotCosmetics = $totalProductsNotCosmeticsOrFood;
            }
        }
        else {
            $totalProductsAfterDiscountCosmetics    = $totalProductsCosmeticsAndFood;
            $totalProductsAfterDiscountNotCosmetics = $totalProductsNotCosmeticsOrFood;
        }

        $totalProductsAfterDiscount = ($totalProductsAfterDiscountCosmetics + $totalProductsAfterDiscountNotCosmetics);
        foreach ($returnedProduct as $rProduct) {
            if ($rProduct->quantity == 0) {
                return [];
            }
            if ($rProduct->flag == 5 || $rProduct->flag == 9) {
                if (isset($discountCosmeticFood) && $discountCosmeticFood > 0) {
                    $rProduct->price_after_discount = ((float)$rProduct->price - ((float)$rProduct->price * $discountCosmeticFood / 100));
                    $rProduct->discount_rate        = $discountCosmeticFood;
                }
                else {
                    $rProduct->price_after_discount = ((float)$rProduct->price);
                    $rProduct->discount_rate        = 0;
                }
            }
            else {
                if (isset($discountNotCosmetic) && $discountNotCosmetic > 0) {
                    $rProduct->price_after_discount = ((float)$rProduct->price - ((float)$rProduct->price * $discountNotCosmetic / 100));
                    $rProduct->discount_rate        = $discountNotCosmetic;
                }
                else {
                    $rProduct->price_after_discount = ((float)$rProduct->price);
                    $rProduct->discount_rate        = 0;
                }
            }
        }

        $discount_amount           = ((float)$totalProducts - (float)$totalProductsAfterDiscount);
        $value_will_has_commission = $totalProductsAfterDiscount;

        if (!empty($gift)) {
//            $returnedProduct          = array_merge($returnedProduct, $giftProducts);
            $discount_amount            += ((float)$gift->total_old_price - (float)$gift->total_price);
            $totalProductsAfterDiscount = ((float)$totalProductsAfterDiscount + (float)$gift->total_price);
        }
        if ($totalExcludersProducts > 0) {
            $totalProductsAfterDiscount = ((float)$totalProductsAfterDiscount + (float)$totalExcludersProducts);
            $discount_amount            += $discountExcludersProductsAmount;
            $returnedProduct            = array_merge($returnedProduct, $returnedExcludersProducts);
        }

        if ($totalOffersProducts > 0) {
            $totalProductsAfterDiscount = ((float)$totalProductsAfterDiscount + (float)$totalOffersProducts);
            $value_will_has_commission  += $totalOffersProducts;
            $returnedProduct            = array_merge($returnedProduct, $returnedOffersProducts);
        }

        return [
            "products"                   => $returnedProduct,
            "giftProducts"               => $giftProducts,
            "out_stock_products"         => $outStockProducts,
            "excluders_products"         => $returnedExcludersProducts,
            "totalProducts"              => round($totalProducts, 2),
            "value_will_has_commission"  => round($value_will_has_commission, 2),
            "totalExcludersProducts"     => round($totalExcludersProducts, 2),
            "totalProductsAfterDiscount" => round($totalProductsAfterDiscount, 2),
            "discountPercentage"         => round($discountPercentage, 2),
            "discount_amount"            => round($discount_amount, 2),
            "sub_total"                  => round($subTotal, 2),
            "userRedeemGift"             => $userRedeemGift,
            "userIdGift"                 => $userIdGift,
            "returngift"                 => $returngift,
        ];
    }

    public function calculateProductsBazar($productsData, $hasDiscount, $type = null, $new_discount = null): array
    {
        $totalExcludersProducts          = 0;
        $totalMagicProducts          = 0;
        $totalProducts                   = 0;
        $totalProductsAfterDiscount      = 0;
        $value_will_has_commission       = 0;
        $discountExcludersProductsAmount = 0;
        $discountmagicProductsAmount = 0;
        $returnedExcludersProducts       = [];
        $returnedProduct                 = [];


        foreach ($productsData as $item) {
            if (isset($item['quantity']) && $item['quantity'] > 0) {
                $conditions=$type=='onLine'?[['id', '=', $item['id']]]: [['id', '=', $item['id']], ['stock_status', '=', ProductStatus::IN_STOCK]];
                $product = $this->ProductRepository->calculatePrice($conditions, ['id', 'flag', 'excluder_flag', 'old_price', 'old_discount', 'full_name', 'name_en', 'name_ar', 'description_en',
                                                                                                 'description_ar', 'image', 'oracle_short_code', 'discount_rate',
                                                                                                 'price', 'price_after_discount', 'quantity', 'stock_status']);
                if (!empty($product)) {

                    if ($product->quantity > 0 && $product->quantity >= $item['quantity']) {
                        if ($product->excluder_flag == 'Y') {
                            $product->quantity               = $item['quantity'];
                            $totalExcludersProducts          += (float)$product->price_after_discount * $product->quantity;
                            $returnedExcludersProducts[]     = $product;
                            $discountExcludersProductsAmount += (($product->price - $product->price_after_discount) * $product->quantity);
                        }elseif ($product->flag == 7 || $product->flag == 23){
                            $product->quantity             = $item['quantity'];
                            $totalMagicProducts              += $product->price * $product->quantity;
                            $product->price_after_discount = isset($item['price_after_discount']) ? (float)$item['price_after_discount'] : $product->price_after_discount;
                            $discountmagicProductsAmount += (($product->price - $product->price_after_discount) * $product->quantity);
                            $returnedProduct[]             = $product;
                        }
                        else {
                            $product->quantity             = $item['quantity'];
                            $totalProducts                 += $product->price * $product->quantity;
                            $product->price_after_discount = isset($item['price_after_discount']) ? (float)$item['price_after_discount'] : $product->price_after_discount;
                            $returnedProduct[]             = $product;
                        }
                    }
                }
            }
        }

        $discountPercentage = $new_discount;

            $totalProductsAfterDiscount = ((float)$totalProducts - ((float)$totalProducts * $discountPercentage / 100));

            foreach ($returnedProduct as $rProduct) {
                if ($rProduct->quantity == 0) {
                    return [];
                }

                $rProduct->price_after_discount = ((float)$rProduct->price - ((float)$rProduct->price * $discountPercentage / 100));
               if($rProduct->flag == 7 || $rProduct->flag == 23){
                   $ddis=$type=='onLine'?30:$discountPercentage;
                  $rProduct->price_after_discount = ((float)$rProduct->price - ((float)$rProduct->price * $ddis / 100));
                }
                if ($rProduct->excluder_flag != 'Y') {
                    $value_will_has_commission += ($rProduct->price_after_discount * $rProduct->quantity);
                }
            }

        $discount_amount = ((float)$totalProducts - (float)$totalProductsAfterDiscount);

        if ($totalExcludersProducts > 0) {
            $totalProductsAfterDiscount = ((float)$totalProductsAfterDiscount + (float)$totalExcludersProducts);
            $discount_amount            += $discountExcludersProductsAmount;
            $returnedProduct            = array_merge($returnedProduct, $returnedExcludersProducts);
        }

        if ($totalMagicProducts > 0) {
            $ddis=$type=='onLine'?30:$discountPercentage;
            $totalMagicProducts=((float)$totalMagicProducts - ((float)$totalMagicProducts * $ddis / 100));
            $totalProductsAfterDiscount = ((float)$totalProductsAfterDiscount + (float)$totalMagicProducts);
            $totalProducts = ((float)$totalProducts + (float)$totalMagicProducts);
            $discount_amount            += $discountmagicProductsAmount;
        }

        return [
            "products"                   => $returnedProduct,
            "totalProducts"              => round($totalProducts, 2),
            "value_will_has_commission"  => round($value_will_has_commission, 2),
            "totalExcludersProducts"     => round($totalExcludersProducts, 2),
            "totalProductsAfterDiscount" => round($totalProductsAfterDiscount, 2),
            "discountPercentage"         => round($discountPercentage, 2),
            "discount_amount"            => round($discount_amount, 2),
        ];
    }




public function checkItemsAvalability($productsData): array
    {
         $outStockProducts = [];
        foreach ($productsData as $item) {
            if(isset($item['userRedeemGift']) && $item['userRedeemGift'] == true){
            }else{
                $productinDB = $this->ProductRepository->calculatePrice([['id', '=', $item['id']]], ['id', 'flag', 'excluder_flag', 'full_name', 'name_en', 'name_ar', 'oracle_short_code', 'price', 'price_after_discount', 'quantity', 'stock_status','visible_status']);
                if (!empty($productinDB)) {
                    if($productinDB->visible_status == '1' && $productinDB->stock_status == 'in stock'){
                        if ((($item['quantity']+10) > ($productinDB['quantity'] )) || ($item['quantity'] > 6 && $item['flag'] == 5)) {
                            $outStockProducts[] = $productinDB;
                            if (($productinDB['quantity'] <= 10)) {
                                $this->ProductRepository->changeStatus([$item['id']], 'out stock');
                            }
                        }
                    }else{
                        $outStockProducts[] = $productinDB;
                    }
                }else{
                    $outStockProducts[] = $item;
                }
            }
        }
        return $outStockProducts;

    }




    // public function checkItemsAvalability($productsData): array
    // {
    //     $checkProducts    = [];
    //     $outStockProducts = [];
    //     foreach ($productsData as $item) {
    //         $product = $this->ProductRepository->calculatePrice([['id', '=', $item['id']]], ['id', 'flag', 'excluder_flag', 'full_name', 'name_en', 'name_ar', 'oracle_short_code', 'price', 'price_after_discount', 'quantity', 'stock_status']);
    //         if (!empty($product)) {
    //             $checkProducts[] = ['item_code' => $product->oracle_short_code, 'id' => $item['id'], 'quantity' => $item['quantity'], 'name_en' => $product->name_en, 'flag' => $product->flag];
    //         }
    //     }
    //     if (!empty($checkProducts) && count($checkProducts) > 0) {
    //         $client   = new \GuzzleHttp\Client();
    //         $response = $client->request('POST', 'https://sales.atr-eg.com/api/get_NettinghubItems_item_avalability.php', ['verify' => false, 'form_params' => array('items' => $checkProducts)]);
    //         $products = $response->getBody();
    //         $products = json_decode($products, true);
    //         foreach ($products as $Product) {
    //             foreach ($checkProducts as $cp) {
    //                 // user can not request more than 6 pieces
    //                 $pendingq=  OrderHeader::leftJoin('order_lines', 'order_lines.order_id', 'order_headers.id')->where('order_lines.product_id',$cp['id'])->where('order_headers.order_status','!=','Cancelled')->where('order_headers.platform','!=','admin')->where('order_headers.send_t_o','0')->sum('order_lines.quantity');
    //                 if ($cp['item_code'] == $Product['item_code'] && (($cp['quantity'] > ($Product['quantity']-$pendingq) ) || ($cp['quantity'] > 6 && $cp['flag'] == 5))) {
    //                     $outStockProducts[] = $cp;
    //                     if ($cp['quantity'] > $Product['quantity']) {
    //                         $this->ProductRepository->changeStatus([$cp['id']], 'out stock');
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     return $outStockProducts;
    // }


    public function calculateShipping($created_for_user_id, $order_type, $totalOrderPrice)
    {

        $user_create_for_row = $this->UserRepository->find($created_for_user_id, ['id', 'account_type']);
        if ($order_type == OrderTypes::SINGLE) {
            if ($totalOrderPrice < 250) {//500 //300
                return 25;
            }
            else
                return 0;
//           else {
//               $newShip = $totalOrderPrice * 0.05;
//               return  round(($newShip > 100) ?100 : $newShip,2);
//           }
        }
        else {
            return $user_create_for_row->userType->delivery_fees;

            if ($user_create_for_row->account_type == 1) {
                return 75;
            }
            else if ($user_create_for_row->account_type == 2) {
                return 50;
            }
            else {
                return 25;
            }
        }
    }

    public function getServices($totalOrderPrice, $user_id): array
    {
        $is_wallet_allow  = 0;
        $is_spinner_allow = 0;
        $user             = $this->UserRepository->find($user_id, ['stage', 'freeaccount']);
        if ($totalOrderPrice > 500) {
            $is_spinner_allow = 1;
        }
        if ($user->stage <= 2 && $user->freeaccount = 0)
            $is_spinner_allow = 0;

        $walletAmount = $this->UserWalletRepository->getCurrentWallet($user_id);

        if (!empty($walletAmount) && $walletAmount['current_wallet'] > 0) {
            $is_wallet_allow = 1;
        }
        return [
            "is_wallet_allowed"  => $is_wallet_allow,
            "is_spinner_allowed" => $is_spinner_allow,
        ];
    }

    public function getOrderDiscount($totalOrderPrice)
    {
        $getDiscount = $this->AccountTypeRepository->getAccountType($totalOrderPrice);
        if (!empty($getDiscount)) {
            return $getDiscount;
        }
        return 0;
    }

    public function saveProductsToCart($products, $user_id, $created_for_user_id): bool
    {
        $this->CartRepository->deleteUserProducts($user_id, $created_for_user_id);
        foreach ($products as $product) {
            $this->CartRepository->safeProduct($product, $user_id, $created_for_user_id);
        }
        return 1;
    }

    public function getTotalPriceOfCart($user_id, $created_for_user_id): int
    {
        return $this->CartRepository->getTotal($user_id, $created_for_user_id);
    }

    public function getMyCart($user_id, $created_for_user_id)
    {
        $cart       = $this->CartRepository->getMyCartForSpinner($user_id, $created_for_user_id);
        $cartHeader = $this->CartRepository->getMyCartHeader($user_id, $created_for_user_id);
        if (!empty($cartHeader)) {
            return [
                "products"                   => $cart,
                "totalProducts"              => $cartHeader->total_products_price,
                "totalProductsAfterDiscount" => round($cartHeader->total_products_after_discount, 2),
                "shipping"                   => $cartHeader->shipping_amount,
            ];
        }
        return [];

    }

    public function saveCartHeader($user_id, $created_for_user_id, $totalProductsPrice, $totalProductsAfterDiscount, $shipping_amount, $discount_amount)
    {
        $this->CartRepository->deleteCartHeader($user_id, $created_for_user_id);
        $this->CartRepository->safeCartHeader($user_id, $created_for_user_id, $totalProductsPrice, $totalProductsAfterDiscount, $shipping_amount, $discount_amount);
    }


}
