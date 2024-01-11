<?php

namespace App\Http\Repositories;

interface UserWalletRepository
{
    public function getCurrentWallet($user_id);
    public function updateWallet($conditions, $data);
    public function updateWalletWhenExpired($user_id,$wallet_amount_back);
}
