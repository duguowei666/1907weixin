<?php

namespace App\Http\Controllers\WeiXin;

use App\Http\Controllers\Controller;
use App\Model\XinWenModel;
use App\Tools\Curl;
use Illuminate\Http\Request;
use App\Tools\WeiXin;
use App\Model\ChannelModel;
use App\Model\CuserModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
class WxController extends Controller
{
    private $student = ['商业兴', '刘清原', '高泽东'];

    protected $access_token;

    public function __construct()
    {
        $this->access_token = WeiXin::getAccessToken();
    }
    public function index()
    {
        $echostr = \request()->echostr;
        if (!empty($echostr)) {
            echo $echostr;
        }

        //接入后 微信服务器接以POST形式接收xml数据，发送到配置得url路径
        $xml = file_get_contents("php://input");

        //写入文件
        $res = file_put_contents("log.txt",".\n.". $xml."\n", FILE_APPEND);    //FILE_APPEND 在日志后面追加写
        //方便处理 把xml数据转化为对象
        $xml_obj = simplexml_load_string($xml);
        if ($xml_obj->MsgType == 'event' && $xml_obj->Event == 'subscribe') {         //如果是关注   回复
            $data = WeiXin::getUserInfoByOpenId($xml_obj->FromUserName);        //获取用户信息
            //得到渠道的标识
            $sign = $data['qr_scene_str'];
            $where = [
                ['sign','=',$sign]
            ];
            //关注人数自增
            ChannelModel::where($where)->increment('number');

            //查询用户信息表
            $cuserInfo = CuserModel::where(['openid'=>$xml_obj->FromUserName])->first();
            if($cuserInfo){
                CuserModel::where(['openid'=>$xml_obj->FromUserName])->update(['is_del'=>1,'sign'=>$sign]);
                WeiXin::response($xml_obj, '欢迎回来');
            }else{
                //存入用户信息--渠道标识
                CuserModel::create([
                    'openid'     => $data['openid'],
                    'nickname'  => $data['nickname'],
                    'city'      => $data['city'],
                    'sign'      => $sign
                ]);
                $nickname = $data['nickname'];      //获取用户名称
                $sex = $data['sex']==1?'男士':'女士';
                $msg = "谢谢".$nickname.$sex."关注";
                WeiXin::response($xml_obj, $msg);
            }

        }
        if($xml_obj->MsgType == 'event' && $xml_obj->Event == 'unsubscribe'){       //用户取消关注
            $where = [
                ['openid','=',$xml_obj->FromUserName]
            ];
            CuserModel::where($where)->update(['is_del'=>2]);       //用户表基本信息 修改状态

            $res1 = CuserModel::where($where)->first()->toArray();       //获取标识

            $channelWhere = [
                ['sign','=',$res1['sign']]
            ];
            ChannelModel::Where($channelWhere)->decrement('number');        //关注人数自减
        }
        if ($xml_obj->MsgType == 'text') {          //如果是文本      回复
            $content = trim($xml_obj->Content);
            if ($content == '1') {              //文本内容是1 回复
                $msg = implode(',', $this->student);               //获取全部名字
                WeiXin::response($xml_obj, $msg);
            } else if ($content == '2') {                //文本内容是2 回复
                shuffle( $this->student);
                $msg =  $this->student[0];
                WeiXin::response($xml_obj, $msg);
            } else if ($content == '3') {                //文本内容是3回复
                WeiXin::response($xml_obj, "333");
            } else if (mb_strpos($content, '天气') !== false) {
                $city = rtrim($content, '天气');
                if (empty($city)) {
                    $city = '北京';
                }
                //天气接口
                $api = 'http://api.k780.com/?app=weather.future&weaid=' . $city . '&appkey=47857&sign=910252adfa683f0f3cf862bea6212adf&format=json';
                //请求天气接口
                $wechat = file_get_contents($api);
                //将json字符串转化为数组
                $wechat_arr = json_decode($wechat, true);
                $str = '';
                //查询一周得天气
                foreach ($wechat_arr['result'] as $k => $v) {
                    //拼接
                    $str .= $v['days'] . '' . $v['week'] . '' . $v['citynm'] . '' . $v['temperature'] . '' . $v['weather'] . "\n";
                }
                WeiXin::response($xml_obj, $str);
            } else if(trim($content == '最新新闻')){
                $res = XinWenModel::orderBy('id','desc')->first();
                $new = $res['content'];
                WeiXin::response($xml_obj,$new);
            } else if(mb_strpos($content, '新闻') !== false){
                $res = rtrim($content,'新闻');
                $where = [];
                if($res){
                    $where[] = ['title','like',"%$res%"];
                }
                $xinwenInfo = XinWenModel::where($where)->get()->toArray();

                if(!$xinwenInfo){
                    WeiXin::response($xml_obj,'无新闻内容');die;
                }
                $str1= '';
                foreach ($xinwenInfo as $k => $v){
                    XinWenModel::where('id',$v['id'])->increment('visit');
                    $str1 .= '标题：'.$v['title'].'内容：'.$v['content'];
                }
                WeiXin::response($xml_obj,$str1);
            }
            //文本内容回复
            WeiXin::response($xml_obj, $content);
        }
        $openid = $xml_obj->FromUserName;
        $media_id = $xml_obj->MediaId;
        if($xml_obj->MsgType == 'image'){       //图片
            //下载图片
            $this->getImg($media_id);
        }elseif ($xml_obj->MsgType == 'video'){     //视频
            //下载视频
            $this->getVideo($media_id);
        }elseif ($xml_obj->MsgType =  'voice'){        //语音
            $this->getVoice($media_id);
        }
    }
    //创建字定义菜单
    public function createMenu(){
        $access_token = WeiXin::getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
        $menu = [
            "button"    =>  [
                [
                    'type'  => 'view',
                    'name'  => '签到',
                    'url'   => 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxc59861663d03edd7&redirect_uri=http%3A%2F%2F1905duguowei.comcto.com%2Fwx%2Fauth&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect',
                ],
                [
                    'type'  => 'view',
                    'name'  => '框架',
                    'url'   => 'http://120.27.245.133/',
                ],
                [
                    'name'  => '二级菜单',
                    "sub_button"   => [
                        [
                            "type"  => "scancode_waitmsg",
                            "name"  => "扫码",
                            "key"   => "rselfmenu_0_0",
                        ]
                    ]
                ]
            ]
        ];
        $menu = json_encode($menu,JSON_UNESCAPED_UNICODE);
        $res = Curl::Post($url,$menu);
        var_dump($res);
    }
    //下载图片
    protected function getImg($media_id){
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->access_token.'&media_id='.$media_id;
        $img = file_get_contents($url);
        //保存图片
        file_put_contents('img',$img);
    }
    //下载视频
    protected function getVideo($media_id){
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->access_token.'&media_id='.$media_id;
        $video = file_get_contents($url);
        $file_name = date('YmdHis').rand(1111,9999).'.mp4';
        $res = file_put_contents($file_name,$video);
        var_dump($res);
    }
    //下载语音
    protected function getVoice($media_id){
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->access_token.'&media_id='.$media_id;
        $voice = file_get_contents($url);
        $res = file_put_contents('voice',$voice);
        var_dump($voice);
    }
    //微信群发
    public function sendMsg(){
        $data = CuserModel::all()->toArray();
        $openid_list = array_column($data,'openid');
        $msg = date('Y-m-d H:i:s').'hello world';
        $json_data = [
            'touser'      => $openid_list,
            'msgtype'   => 'text',
            'text'      => [
                'content'   => $msg
            ]
        ];
        $json_data = json_encode($json_data,JSON_UNESCAPED_UNICODE);
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$this->access_token;
        $res = Curl::Post($url,$json_data);
        $res = json_decode($res,true);

        if($res['errcode']>0){
            echo '错误信息：'. $res['errmsg'];
        }else{
            echo '成功';
        }
    }
    //网页授权
    public function test(){
        $redirect_url = urlencode(env('REDIRECT_URI'));
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('APPID').'&redirect_uri='.$redirect_url.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect ';
        echo $url;
    }
    //接收网页授权code
    public function auth(){
        //接收code
        $code = $_GET['code'];
        //根据code获取access_token
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('APPID').'&secret='.env('APPSEC').'&code='.$code.'&grant_type=authorization_code';
        $json_data = file_get_contents($url);
        $arr = json_decode($json_data,true);

        //获取用户信息
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$arr['access_token'].'&openid='.$arr['openid'].'&lang=zh_CN';
        $jsonInfo = file_get_contents($url);
        $arr1 = json_decode($jsonInfo,true);
        print_r($arr1);
        echo '<br>';

        //将用户信息存入reids哈希中
        $key = 'h:userInfo:'.$arr1['openid'];
        Redis::hMset($key,$arr1);

        //实现签到功能
        $redis_key = 'checkin'.date('Y-m-d');
        Redis::Zadd($redis_key,time(),$arr1['openid']);     //将openid加入有序集合
        echo $arr1['nickname'].'签到成功'.date('Y-m-d H:i:s');
        echo '<br>';
        $user_list = Redis::zrange($redis_key,0,-1);
        foreach ($user_list as $k => $v){
            $key = 'h:userInfo:'.$v;
            $u = Redis::hGetAll($key);
            if(empty($u)){
                continue;
            }
        echo "<img src='".$u['headimgurl']."'>";
        }
    }
    //刷新access_token
    public function AccessToken(){
        $appid = 'wxc59861663d03edd7';
        $secret = '4467a4f0dcd161b26e8f921f049c5434';
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;
            $data = file_get_contents($url);        //发送请求
            $data = json_decode($data,true);
            $access_token = $data['access_token'];      //获取access_token
        return $access_token;       //如果为access_token有值返回
    }
}
