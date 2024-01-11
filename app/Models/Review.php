<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends AbstractModel
{
    use HasFactory;

    public    $timestamps = true;
    protected $fillable   = [
        'id','user_id','product_id','rate','comment','status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}