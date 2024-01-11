<?php

namespace App\Http\Repositories;


use App\Models\UserWallet;
use App\Models\WalletHistory;
use Carbon\Carbon;

class IUserWalletRepository extends BaseRepository implements UserWalletRepository
{

    public function __construct(UserWallet $model)
    {
        parent::__construct($model);
    }

    public function getCurrentWallet($user_id)
    {
        return UserWallet::where(['user_id'=>$user_id])->select('*')->first();
    }
    public function getHistoryWalletAsParent($user_id)
    {
        $start = Carbon::now()->startOfMonth()->toDateTimeString();
        $end = Carbon::now()->toDateTimeString();
        return WalletHistory::where(['user_id'=>$user_id])->whereBetween('created_at', [$start, $end])->sum('amount');
    }
    public function parentHaveWalletForThisOrder($user_id,$order_id)
    {
        $exist=WalletHistory::where(['user_id'=>$user_id])->where(['order_id'=>$order_id])->first();
        return $exist?true:false;
    }

    public function updateWallet($conditions, $data)
    {
       return UserWallet::where($conditions)->update($data);
    }

    public function updateWalletWhenExpired($user_id,$wallet_amount_back)
    {
        UserWallet::where('user_id',$user_id)->increment('current_wallet',$wallet_amount_back);
    }
    public function getAllData($inputData){
        $data = UserWallet::orderBy('updated_at','desc');


        if (isset($inputData['user_id']))
        {
            $data->where('user_id',$inputData['user_id']);
        }

        return  $data->paginate($this->defaultLimit);

    }
}
