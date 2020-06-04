<?php

namespace App\Jobs;

use App\Models\Campaing;
use App\Models\Parsers\NavitrinuCom;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CampaingParseStatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $campaings = Campaing::all();
        $parser = new NavitrinuCom();
        $parser->loginInSite();
        foreach ($campaings as $item) {
            // запускаем очереди
            $data_promo = $parser->getPromoStat($item->outer_id);
            foreach ($data_promo as $promo) {
                StatPromoJob::dispatch($promo,$item->id);
            }

            $data_site = $parser->getSiteStat($item->outer_id);
            foreach ($data_site as $site) {
                StatSiteJob::dispatch($site,$item->id);
            }
        }
        CampaingParseStatJob::dispatch()->delay(now()->addMinutes(10));
    }
}
