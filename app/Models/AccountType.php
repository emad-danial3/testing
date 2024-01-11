<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountType extends AbstractModel
{
    use HasFactory;
    protected $hidden = ['created_at','updated_at'];
    public function AccountCommissionLevels(){

        return $this->hasMany(AccountCommission::class,'account_id');
    }
}
