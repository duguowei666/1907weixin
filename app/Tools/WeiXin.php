<?php

namespace App\Tools;

use DemeterChain\C;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use phpDocumentor\Reflection\Types\Self_;
use App\Tools\Curl;
class WeiXin
{
    //回复xml数据
    public static function response($xml_obj,$msg){
        echo "<xml>
                          <ToUserName><![CDATA[".$xml_obj->FromUserName."]]></ToUserName>
                          <FromUserName><![CDATA[".$xml_obj->ToUserName."]]></FromUserName>
                          <CreateTime>'.time().'</CreateTime>
                          <MsgType><![CDATA[text]]></MsgType>
                          <Content><![CDATA[".$msg."]]></Content>
                </xml>";die;
    }
    const appid = 'wxc59861663d03edd7';
    const secret = '4467a4f0dcd161b26e8f921f049c5434';
    //获取access_token
    public static function getAccessToken(){
         $access_token = Cache::get('access_token');
         if(empty($access_token)){      //如果为access_token为空  获取access_token
             //获取access_token微信接口调用凭证
             $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.Self::appid.'&secret='.Self::secret;
             $data = file_get_contents($url);        //发送请求
             $data = json_decode($data,true);
             $access_token = $data['access_token'];      //获取access_token
             Cache::put('access_token',$access_token,7200);      //存两个小时
         }
        return $access_token;       //如果为access_token有值返回
    }
    //获取用户的基本信息
    public static function getUserInfoByOpenId($openid){
        $access_token = Self::getAccessToken();
        //获取用户基本信息
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $data = file_get_contents($url);        //发送请求
        $data = json_decode($data,true);
        return $data;
    }
    //上传素材
    public static function uploadMedia($data,$filePath){
        $access_token = Self::getAccessToken();
        $type = $data['media_format'];
        $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$access_token.'&type='.$type;
        $filePathObj = new \CURLFile(public_path().'/'.$filePath);       //curl 发送文件先通过CURLFile类处理
        $postData = ['media'=>$filePathObj];
        $res = Curl::Post($url,$postData);
        $res = json_decode($res,true);
        return $res;
    }
    //获取的二维码ticket，此ticket可以在有效时间内换取二维码。
    public static function ticket($data){
        //获取token
        $access_token = Self::getAccessToken();

        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
        //发送urlpost请求
        $postData = [
            'expire_seconds'    => '2592000',
            'action_name'       => 'QR_STR_SCENE',
            'action_info'       => [
                'scene'     => [
                    'scene_str' => $data['sign']
                ],
            ],
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $res = Curl::Post($url,$postData);
        $res = json_decode($res,true);
        //获取ticket
        $ticket = $res['ticket'];
        return $ticket;
    }
}
