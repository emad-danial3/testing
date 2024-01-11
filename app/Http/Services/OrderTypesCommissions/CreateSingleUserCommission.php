<?php

namespace App\Http\Services\OrderTypesCommissions;

use App\Http\Repositories\IUserCommissionRepositories;
use App\Models\UserCommission;

class CreateSingleUserCommission implements AbstractCommission
{

    public static function createCommission($orderHeader): int
    {
        $UserCommissionRepository = new IUserCommissionRepositories(new UserCommission());
        $commissionAmount              = ($orderHeader['total_order'] * 10) / 100;
        $data['user_id']               = $orderHeader['user_id'];
        $data['commission']            = $commissionAmount;
        $data['commission_percentage'] = 10;
        $data['order_id']              = $orderHeader['id'];
        $UserCommissionRepository->create($data);
        return 1;
    }
}
