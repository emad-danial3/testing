<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WelcomeProgramProduct extends AbstractModel
{
    use HasFactory;

    public    $timestamps = true;
    protected $fillable   = [
        'month','name_en','name_ar','image','start_date','end_date','status','total_price','total_old_price'
    ];

    public function product()
    {
        return $this->hasMany(WelcomeProgramProductDetails::class, 'welcome_program_id');
    }
}
