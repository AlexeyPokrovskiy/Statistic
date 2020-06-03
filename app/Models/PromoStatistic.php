<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoStatistic extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'promo_id', 'campaign_id', 'clicks','revenue','date','hour'
    ];
}
