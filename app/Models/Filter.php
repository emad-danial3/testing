<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filter extends AbstractModel
{
    use HasFactory;

    public function Products(){
        return $this->hasMany(Product::class,'filter_id','id');
    }

}
