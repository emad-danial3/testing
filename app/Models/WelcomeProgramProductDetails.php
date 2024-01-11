<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WelcomeProgramProductDetails extends AbstractModel
{
    use HasFactory;

    public    $timestamps = true;
    protected $fillable   = [
        'welcome_program_id','product_id','quantity','price','discount_rate','price_after_discount','start_date','end_date','status'
    ];

    public function welcomeProgram()
    {
        return $this->belongsTo(WelcomeProgramProduct::class, 'welcome_program_id');
    }
     public function product(){
         return $this->hasMany(Product::class, 'id','product_id');
     }

}
