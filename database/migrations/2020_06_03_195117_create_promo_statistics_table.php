<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_statistics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('promo_id')->unsigned();
            $table->bigInteger('campaign_id')->unsigned();
            $table->integer('clicks')->unsigned();
            $table->double('revenue', 15, 8);
            $table->date('date');
            $table->integer('hour');
            $table->timestamps();
            $table->index(['promo_id', 'campaign_id','date','hour']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promo_statistics');
    }
}
