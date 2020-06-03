<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteStatistic extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'site_id', 'campaign_id', 'clicks','revenue','date','hour'
    ];
}
