<?php

namespace App\Models\Parsers;

//use Illuminate\Database\Eloquent\Model;
use Sunra\PhpSimple\HtmlDomParser;

class NavitrinuCom
{
    /**
     * @param $url
     * @param array $post
     * @return string
     */
    public function sendRequest($url,$post = 0){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url ); // отправляем на
        curl_setopt($ch, CURLOPT_HEADER, 0); // пустые заголовки
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // возвратить то что вернул сервер
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // следовать за редиректами
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);// таймаут4
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt'); // сохранять куки в файл
        curl_setopt($ch, CURLOPT_COOKIEFILE,  dirname(__FILE__).'/cookie.txt');
        curl_setopt($ch, CURLOPT_POST, $post!==0 ); // использовать данные в post
        if($post)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * Функция авторизации на сайте донора
     */
    public function loginInSite(){
        $url = 'https://navitrinu.com/Identity/Account/Login';

        //получаем  RequestVerificationToken
        $data = $this->sendRequest($url);

        $data = HtmlDomParser::str_get_html($data);

        $auth = array(
            'token'=>$data->find('input[name="__RequestVerificationToken"]',0)->value,
        );
        $data->clear();
        unset($data);

        //готовим и отправляем запрос на авторизацию
        $post = array(
            'Input.UserName'=>env('NAVITRINU_LOGIN'),
            'Input.Password'=>env('NAVITRINU_PASS'),
            'Input.RememberMe'=>'true',
            '__RequestVerificationToken'=>$auth['token'],

        );
        $this->sendRequest($url,$post);

    }

    public function getCompaings(){
        $url = 'https://navitrinu.com/campaigns/griddata';

        $post = array(
            'range' => "Today"
        );

        //поучаем Json кампаний
        $data = $this->sendRequest($url,$post);

        return json_decode($data);
    }


    /**
     * Статистика promo _id по компании
     *
     * @param int $outer_id
     * @return mixed
     */
    public function getPromoStat($outer_id){
        $url = 'https://navitrinu.com/Statistics/StatisticsGridDataAsync';
        $post = array(
            "draw"=>2,
            "columns[0][data]"=>"groupValue",
            "columns[0][name]"=>"groupValue",
            "columns[0][searchable]"=>false,
            "columns[0][orderable]"=>true,
            "columns[0][search][value]"=>"",
            "columns[0][search][regex]"=>false,
            "columns[1][data]"=>"groupTitle",
            "columns[1][name]"=>"groupTitle",
            "columns[1][searchable]"=>false,
            "columns[1][orderable]"=>true,
            "columns[1][search][value]",
            "columns[1][search][regex]"=>false,
            "columns[2][data]"=>"clicks",
            "columns[2][name]"=>"clicks",
            "columns[2][searchable]"=>false,
            "columns[2][orderable]"=>true,
            "columns[2][search][value]"=>"",
            "columns[2][search][regex]"=>false,
            "columns[3][data]"=>"partnerIncome",
            "columns[3][name]"=>"partnerIncome",
            "columns[3][searchable]"=>false,
            "columns[3][orderable]"=>true,
            "columns[3][search][value]",
            "columns[3][search][regex]"=>false,
            "columns[4][data]"=>"expectedPartnerIncome",
            "columns[4][name]"=>"expectedPartnerIncome",
            "columns[4][searchable]"=>false,
            "columns[4][orderable]"=>true,
            "columns[4][search][value]"=>"",
            "columns[4][search][regex]"=>false,
            "order[0][column]"=>1,
            "order[0][dir]"=>"desc",
            "start"=>0,
            "length"=>-1,
            "search[value]"=>"",
            "search[regex]"=>false,
            "id"=>$outer_id,
            "group"=>"Token2",
            "range"=>"Today",
            "from"=>"",
            "to"=>"",
            "accountId"=>0,
        );

        $data = json_decode($this->sendRequest($url,$post));
        return $data->data;
    }

    /**
     * Статистика site_id по компании
     *
     * @param int $outer_id
     * @return mixed
     */
    public function getSiteStat($outer_id){
        $url = 'https://navitrinu.com/Statistics/StatisticsGridDataAsync';
        $post = array(
            "draw"=>2,
            "columns[0][data]"=>"groupValue",
            "columns[0][name]"=>"groupValue",
            "columns[0][searchable]"=>false,
            "columns[0][orderable]"=>true,
            "columns[0][search][value]"=>"",
            "columns[0][search][regex]"=>false,
            "columns[1][data]"=>"groupTitle",
            "columns[1][name]"=>"groupTitle",
            "columns[1][searchable]"=>false,
            "columns[1][orderable]"=>true,
            "columns[1][search][value]",
            "columns[1][search][regex]"=>false,
            "columns[2][data]"=>"clicks",
            "columns[2][name]"=>"clicks",
            "columns[2][searchable]"=>false,
            "columns[2][orderable]"=>true,
            "columns[2][search][value]"=>"",
            "columns[2][search][regex]"=>false,
            "columns[3][data]"=>"partnerIncome",
            "columns[3][name]"=>"partnerIncome",
            "columns[3][searchable]"=>false,
            "columns[3][orderable]"=>true,
            "columns[3][search][value]",
            "columns[3][search][regex]"=>false,
            "columns[4][data]"=>"expectedPartnerIncome",
            "columns[4][name]"=>"expectedPartnerIncome",
            "columns[4][searchable]"=>false,
            "columns[4][orderable]"=>true,
            "columns[4][search][value]"=>"",
            "columns[4][search][regex]"=>false,
            "order[0][column]"=>1,
            "order[0][dir]"=>"desc",
            "start"=>0,
            "length"=>-1,
            "search[value]"=>"",
            "search[regex]"=>false,
            "id"=>$outer_id,
            "group"=>"Token1",
            "range"=>"Today",
            "from"=>"",
            "to"=>"",
            "accountId"=>0,
        );

        $data = json_decode($this->sendRequest($url,$post));
        return $data->data;
    }

}
