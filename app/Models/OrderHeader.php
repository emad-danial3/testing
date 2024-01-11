<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHeader extends AbstractModel
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

    public function giftCategory()
    {
        return $this->belongsTo(SpinnerCategory::class,'gift_category_id');
    }
    public function order_lines()
    {
        return $this->hasMany(OrderLine::class,'order_id');
    }
    public function prints()
    {
        return $this->hasMany(OrderPrintHistory::class,'order_header_id');
    }
    public function address()
    {
        return $this->belongsTo(UserAddress::class,'address_id');
    }
    public function delivery_status()
    {
        return $this->hasOne(OrderDeliveryStatus::class,'order_id','id');
    }
    public function delivery_stations()
    {
        return $this->hasMany(OrderDeliveryStation::class,'order_id','id');
    }

}
