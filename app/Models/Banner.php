<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends AbstractModel
{
    use HasFactory;
    protected $hidden =['created_at','updated_at','priority','id'];


    public function category(){
        return $this->belongsTo(Category::class)->with(['parent' => function ($query) {
            $query->select('id','name_en');
        }]);
    }
}
