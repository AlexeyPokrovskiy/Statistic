<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaing extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','outer_id'
    ];

    /**
     * Создание если компания новая
     *
     * @param $data
     */
    public static function createFromParser($data){
        foreach ($data->data as $item) {
            Campaing::firstOrCreate(
                ['name' => $item->name,'outer_id' => $item->id]
            );
        }
    }
}
