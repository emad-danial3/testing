<?php

namespace App\Http\Repositories;

use App\Models\AccountCommission;

class IAccountCommissionRepository extends BaseRepository implements AccountCommissionRepository
{
    public function __construct(AccountCommission $model) {
        parent::__construct($model);
    }
    public function  updateExist($account,$level,$data){
        AccountCommission::where('account_id',$account)->where('level',$level)->update($data);
    }
}
