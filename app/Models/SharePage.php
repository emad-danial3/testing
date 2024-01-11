<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharePage extends AbstractModel
{
    use HasFactory;
    protected $hidden =['created_at','updated_at','id','share_page_category_id','status'];

    public function category()
    {
        return $this->belongsTo(SharePageCategory::class,'share_page_category_id');
    }
}
