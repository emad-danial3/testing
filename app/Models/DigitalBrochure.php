<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalBrochure extends Model
{
    protected $table = "digital_brochure";
    use HasFactory;

    public    $timestamps = true;
    protected $fillable   = [
        'title','title_en', 'image', 'file'
    ];
}
