<?php

namespace App\Console;


use App\Models\Campaing;
use App\Models\Parsers\NavitrinuCom;
use App\Models\PromoStatistic;
use App\Models\SiteStatistic;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->campaingParse($schedule);
        $this->importStat($schedule);
    }


    /**
     * Парсинг новый компаний если они есть
     *
     * @param Schedule $schedule
     */
    protected function campaingParse(Schedule $schedule){
        $schedule->call(function () {
            $parser = new NavitrinuCom();
            $parser->loginInSite();
            $data = $parser->getCompaings();
            Campaing::createFromParser($data);
        })->everyMinute();

    }

    /**
     * импортируем статистику
     *
     * @param Schedule $schedule
     */
    protected function importStat(Schedule $schedule){

        $schedule->call(function () {
            $campaings = Campaing::all();
            $parser = new NavitrinuCom();
            $parser->loginInSite();
            foreach ($campaings as $item) {
                $data_promo = $parser->getPromoStat($item->outer_id);
                foreach ($data_promo as $promo) {
                    $promoObj = new PromoStatistic();
                    $promoObj->createFromParser($promo,$item->id);
                }

                $data_site = $parser->getSiteStat($item->outer_id);
                foreach ($data_site as $site) {
                    $siteObj = new SiteStatistic();
                    $siteObj->createFromParser($site,$item->id);
                }
            }
        })->everyTenMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
