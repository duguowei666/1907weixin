<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Curl;
class IndexController extends Controller
{
    public function index(){
        return view('admin.index');
    }

    public function wechat(){
        $city = \request('city');
        echo $city;
        //天气接口
        $url = 'http://api.k780.com/?app=weather.future&weaid=' . $city . '&appkey=47857&sign=910252adfa683f0f3cf862bea6212adf&format=json';
//        dd($url);
        $res = Curl::Get($url);
        return $res;
//        dd($res);
//        return view('admin.wechat');
    }
}
