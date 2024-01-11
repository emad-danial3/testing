<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spinner extends AbstractModel
{
    use HasFactory;
    protected $hidden=[
        'created_at',
        'updated_at'];

    public function SpinnerCategories()
    {
        return $this->belongsTo(SpinnerCategory::class,'spinner_category_id')->where('is_looked',0);
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'gift_id');
    }
}
