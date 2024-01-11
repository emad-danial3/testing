<?php

namespace App\Http\Services\OrderTypesCommissions;

use App\Http\Repositories\AccountTypeRepository;
use App\Http\Repositories\IAccountTypeRepository;
use App\Http\Repositories\IUserCommissionRepositories;
use App\Http\Repositories\IUserRepository;
use App\Models\AccountType;
use App\Models\User;
use App\Models\UserCommission;

class CreateFreeUserCommission implements AbstractCommission
{

    public static function createCommission($orderHeader)
    {
        $UserRepository             = new IUserRepository(new User());
        $AccountTypeRepository      = new IAccountTypeRepository(new AccountType());
        $UserCommissionRepository   = new IUserCommissionRepositories(new UserCommission());
        $userRow                    = $UserRepository->find($orderHeader['created_for_user_id'], ['id', 'account_type']);
        $accountAmount              = $AccountTypeRepository->find($userRow->account_type,['amount']);
        $userParents                = $UserRepository->getAccountParent($orderHeader['created_for_user_id']);
        $totalOrderAmountCommission = ($orderHeader['total_order'] > 500 )? 500 : $orderHeader['total_order'];
        foreach ($userParents as $parent) {
            $commissionAmount              = ($parent->commission * $totalOrderAmountCommission) / 100;
            $data['user_id']               = $parent->parent_id;
            $data['commission']            = $commissionAmount;
            $data['commission_percentage'] = $parent->commission;
            $data['user_id']         = $orderHeader['user_id'];
            $data['order_id']              = $orderHeader['id'];
            $UserCommissionRepository->create($data);
        }
        //for free user commission
//        $UserCommissionRepository->create([
//            'user_id'               => $orderHeader['created_for_user_id'],
//            'commission'            => ($totalOrderAmountCommission * 10) / 100,
//            'commission_percentage' => 10,
//            'commission_by'         => $orderHeader['created_for_user_id'],
//            'order_id'              => $orderHeader['id'],
//        ]);
        return 1;
    }
}
