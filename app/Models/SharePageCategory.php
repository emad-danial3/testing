<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharePageCategory extends AbstractModel
{
    use HasFactory;
    protected $hidden =['created_at','updated_at'];
}
