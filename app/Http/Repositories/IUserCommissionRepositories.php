<?php

namespace App\Http\Repositories;

use App\Models\UserCommission;

class IUserCommissionRepositories extends BaseRepository implements UserCommissionRepositories
{
    public function __construct(UserCommission $model)
    {
        parent::__construct($model);
    }

}
