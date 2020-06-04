<?php

namespace App\Jobs;

use App\Models\Parsers\NavitrinuCom;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Campaing;

class CampaingParseJob implements ShouldQueue
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
        $parser = new NavitrinuCom();
        $parser->loginInSite();
        $data = $parser->getCompaings();
        Campaing::createFromParser($data);
        CampaingParseJob::dispatch()->delay(now()->addMinutes(60));
    }
}
