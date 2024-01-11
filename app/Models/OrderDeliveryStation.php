<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDeliveryStation extends Model
{
    use HasFactory;
    public $table = "order_delivery_status_stations";
    public $timestamps = true;
    protected $fillable = [
    'order_id','status','barcode','status_time'
    ];
}
