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
        $data = $this->sendRequest($url,$post);

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

}
