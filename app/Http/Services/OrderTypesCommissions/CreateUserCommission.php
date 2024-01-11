<?php

namespace App\Http\Services\OrderTypesCommissions;


use App\Http\Repositories\IUserCommissionRepositories;
use App\Http\Repositories\IUserRepository;
use App\Http\Services\UserService;
use App\Models\User;
use App\Models\UserCommission;

class CreateUserCommission  implements AbstractCommission
{

    public static function createCommission($data):int
    {
        $UserCommissionRepository = new IUserCommissionRepositories(new UserCommission());
        $UserCommissionRepository->create($data);//check performance you should create array and push it one time
        return 1;
    }
}
