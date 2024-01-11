<?php

namespace App\Http\Repositories;

use App\Models\AccountType;

class IAccountTypeRepository extends BaseRepository implements AccountTypeRepository
{
    public function __construct(AccountType $model){
        parent::__construct($model);
    }

     public function getAccountType($totalOrder)
    {
       return AccountType::where([['monthly_sales_from','<=',$totalOrder
                                  ], ['monthly_salls_to','>=',$totalOrder]])->first();
    }
}
