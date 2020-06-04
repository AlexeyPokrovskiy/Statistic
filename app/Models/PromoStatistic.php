<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Campaing;

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


    /**
     * Преобразование типов при получении из базы
     *
     * @var array
     */
    protected $casts = [
        'clicks' => 'integer',
        'revenue' => 'double'
    ];

    /**
     * Записать историю по промо
     *
     * @param $data
     */
    public static function createFromParser($data,$campaing_id){
        date_default_timezone_set('UTC');

        //готовим основные данные
        $prepere_index_date = array(
            'promo_id' => $data->groupValue,
            'campaign_id' => $campaing_id,
            'date' => date('Y-m-d'),
            'hour' => date('H'),
        );


        //получаем статистику за день
        $old_stat = PromoStatistic::where("promo_id",$data->groupValue)
            ->where("campaign_id",$campaing_id)
            ->where("date",date('Y-m-d'))
            ->where("hour","<",date('H'));

        $old_clicks = $old_stat->sum('clicks');
        $old_revenue = $old_stat->sum('revenue');

        // доход
        $revenue = 0;
        if(!empty($data->incomes)){
            // разница дохода
            $revenue = $data->incomes[0]->partnerIncome - $old_revenue;
        }

        // разница кликов
        $clicks = $data->clicks - $old_clicks;

        // Обновляем или создаем статистику за час
        PromoStatistic::updateOrCreate(
            $prepere_index_date,
            array(
                'clicks' => $clicks,
                'revenue' => $revenue
            )
        );

    }
}
