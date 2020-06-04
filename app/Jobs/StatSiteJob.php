<?php

namespace App\Jobs;

use App\Models\SiteStatistic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StatSiteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    protected $data;

    protected $campaing_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$campaing_id)
    {
        $this->data = $data;
        $this->campaing_id = $campaing_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $promo = new SiteStatistic();
        $promo->createFromParser($this->data,$this->campaing_id);
    }
}
