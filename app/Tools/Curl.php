<?php

namespace App\Tools;

use Illuminate\Database\Eloquent\Model;

class Curl
{
    //curl GET方式发送请求
    public static function Get($url){
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);      //设置请求地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);      //关闭https验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);      //关闭https验证
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);      //返回数据格式
        //curl_setopt($curl, CURLOPT_TIMEOUT,60);       //设置时间

        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    //Curl   POST方式发送请求
    public static function Post($url,$postData){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url); //设置请求地址
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 返回数据格式
        curl_setopt($curl, CURLOPT_POST, 1);  //设置以post发送
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);   //设置post发送的数据
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //关闭https验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);//关闭https验证
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}
