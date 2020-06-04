<?php

namespace App\Http\Controllers;

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

        $test = new NavitrinuCom();
        $test->loginInSite();
//        $data = $test->getCompaings();
//        Campaing::createFromParser($data);
        $data = $test->getPromoStat(1118);
        $promo = new SiteStatistic();

        $result = $promo->createFromParser($data,31);


        dd($result);
        return view('home');

    }
}
