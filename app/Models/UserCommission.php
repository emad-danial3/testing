<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCommission extends AbstractModel
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function createdFor()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function order()
    {
        return $this->belongsTo(OrderHeader::class,'order_id');
    }
}
