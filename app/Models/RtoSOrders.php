<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RtoSOrders extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_header_id','labelURL', 'message', 'messageType', 'packageStickerURL', 'status','trackingURL','waybillNumber'
    ];
}
