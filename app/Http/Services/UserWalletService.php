<?php

namespace App\Http\Services;

use App\Http\Repositories\CartRepository;
use App\Http\Repositories\IUserRepository;
use App\Http\Repositories\IUserWalletRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Repositories\UserWalletRepository;
use App\Http\Repositories\WalletEvaluationRepository;

class UserWalletService extends BaseServiceController
{
    private  $CartRepository;
    private  $UserRepository;
    private  $UserWalletRepository;
    private  $WalletEvaluationRepository;

    public function __construct(CartRepository $CartRepository, UserRepository $UserRepository, UserWalletRepository $UserWalletRepository
                                ,WalletEvaluationRepository $WalletEvaluationRepository)
    {
        $this->CartRepository=$CartRepository;
        $this->UserRepository=$UserRepository;
        $this->UserWalletRepository=$UserWalletRepository;
        $this->WalletEvaluationRepository=$WalletEvaluationRepository;
    }

    public function applyWalletPay($orderData, $userWalletRow)
    {
        $cartOrderHeader       = $this->CartRepository->getMyCartHeader($orderData['user_id'], $orderData['created_for_user_id']);
        if (empty($cartOrderHeader))
        {
            return  [
                "type"                => "error",
            ];
        }

        $totalOrderAndShipping = $cartOrderHeader['shipping_amount'] + $cartOrderHeader['total_products_after_discount'];
        $totalUserWallet       = $userWalletRow->current_wallet;
        if ($totalUserWallet < $totalOrderAndShipping ) {
//            $wallet_used_amount  = $totalUserWallet;
//            $percentageDiscount  = ($totalUserWallet / $totalOrderAndShipping) * 100;
//            $new_total_products_price              = 0;
//            $new_total_products_after_discount     = 0;
//            $getCartLines        = $this->CartRepository->getMyCart($orderData['user_id'], $orderData['created_for_user_id']);
//            foreach ($getCartLines as $product) {
//                $product->price_after_discount     = $product->price_after_discount - (($product->price_after_discount * $percentageDiscount ) / 100);
//                $data['price']                 = $product->price;
//                $data['price_after_discount'] = $product->price_after_discount;
//                $this->CartRepository->updateMyCart($product->id,$data);
//                $new_total_products_price          = $new_total_products_price + ($product->price * $product->quantity);
//                $new_total_products_after_discount = $new_total_products_after_discount+ ($product->price_after_discount * $product->quantity);
//
//            }
//            $cartHeaderData                    = [
//                'total_products_price'            => $new_total_products_price,
//                'total_products_after_discount'   => $new_total_products_after_discount,
//                "shipping_amount"                 => 0
//            ];
//            $this->CartRepository->updateHeader($orderData['user_id'], $orderData['created_for_user_id'],$cartHeaderData);
            return [
                "type"                => "out",
//                "wallet_used_amount"  => $wallet_used_amount,
                "rested_amount"       => 0
            ];
        }
        elseif ($totalUserWallet >= $totalOrderAndShipping ) {
            $rested_amount      = $totalUserWallet - $totalOrderAndShipping;
            $wallet_used_amount = $totalOrderAndShipping;

            $new_total_products_price              = 0;
            $new_total_products_after_discount     = 0;
            $getCartLines        = $this->CartRepository->getMyCart($orderData['user_id'], $orderData['created_for_user_id']);

//            foreach ($getCartLines as $product) {
//                $product->price_after_discount = 0;
//                $data['price']                 = $product->price;
//                $data['price_after_discount']  = 0;
//                $this->CartRepository->updateMyCart($product->id,$data);
//                $new_total_products_price          = $new_total_products_price + ($product->price * $product->quantity);
//                $new_total_products_after_discount = 0;
//
//            }
//            $cartHeaderData                    = [
//                'total_products_price'            => $new_total_products_price,
//                'total_products_after_discount'   => $new_total_products_after_discount,
//                "shipping_amount"                 => 0
//            ];
//            $this->CartRepository->updateHeader($orderData['user_id'], $orderData['created_for_user_id'],$cartHeaderData);
            return [
                "type"                => "full",
                "wallet_used_amount"  => $wallet_used_amount,
                "rested_amount"       => $rested_amount
            ];
        }
    }

    public function getMyWallet($user_id)
    {
        $myWalletRow      = $this->UserWalletRepository->getCurrentWallet($user_id);
        $walletEvaluation = $this->WalletEvaluationRepository->getAll(['name','value','amount']);
        if (isset($myWalletRow))
        {
            return[
               "total_orders_amount" => $myWalletRow['total_orders_amount'],
               "total_members_count" => $myWalletRow['total_members_count'],
               "total_wallet"        => $myWalletRow['total_wallet'],
               "current_wallet"      => $myWalletRow['current_wallet'],
               "ordersCommission"    => $myWalletRow['withdrawal_order_count'] * $walletEvaluation[0]['amount'],
                "membersCommission"   => (int)($myWalletRow['total_members_count']/$walletEvaluation[1]['value']) * $walletEvaluation[1]['amount'],
               "polices"             => $walletEvaluation
            ];
        }
        else
        {
            return[
                "total_orders_amount" => 0,
                "total_members_count" => 0,
                "total_wallet"        => 0,
                "current_wallet"      => 0,
                "ordersCommission"    => 0,
                "membersCommission"   => 0,
                "polices"             => $walletEvaluation
            ];
        }
    }

    public function updateWallet($user_id,$data)
    {
        return $this->UserWalletRepository->updateWallet(['user_id'=> $user_id],$data);
    }
    public function getAll($inputData){
        return $this->UserWalletRepository->getAllData($inputData);
    }
}
