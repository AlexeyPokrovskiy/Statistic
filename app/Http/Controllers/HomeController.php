<?php

namespace App\Http\Controllers;

use App\Jobs\CampaingParseJob;
use App\Jobs\CampaingParseStatJob;
use App\Jobs\StatPromoJob;
use App\Jobs\StatSiteJob;
use Illuminate\Http\Request;
use App\Models\Campaing;
use App\Models\PromoStatistic;
use App\Models\SiteStatistic;
use App\Models\Parsers\NavitrinuCom;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    /**
     *  Роут запуска приложения
     */
    public function run()
    {
        CampaingParseJob::dispatch();
        CampaingParseStatJob::dispatch();
    }
}
