<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHasMonthlyCommission extends AbstractModel
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function order()
    {
        return $this->belongsTo(OrderHeader::class,'order_id');
    }
    public function commission()
    {
        return $this->belongsTo(UserMonthlyCommission::class,'commission_id');
    }

}
