<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesLeaderLevels extends AbstractModel
{
    use HasFactory;

    public    $timestamps = true;
    protected $fillable   = [
        'title','min_personal_sales','total_actives_team','g1_new_recruits','total_team_sales','spons_b_new_r','g1_bonus','g2_bonus','life_time_bonus'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
