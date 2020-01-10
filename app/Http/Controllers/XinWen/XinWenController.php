<?php

namespace App\Http\Controllers\XinWen;

use App\Http\Controllers\Controller;
use App\Tools\Curl;
use Illuminate\Http\Request;
use App\Model\XinWenModel;
use App\Tools\WeiXin;
class XinWenController extends Controller
{
    public function add(){
        return view('xinwen.add');
    }

    public function do_add(){
        $data = \request()->except('_token');
        $data['add_time'] = time();
        $res = XinWenModel::create($data);
        if($res){
            echo "<script>alert('添加成功');location='/xinwen/show'</script>";
        }else{
            echo "<script>alert('添加失败');location='/xinwen/add'</script>";
        }
    }

    public function show(){
        $title = \request()->title;
        $author = \request()->author;
        $where = [];
        if($title){
            $where[] = ['title','like',"%$title%"];
        }
        if($author){
            $where[] = ['author','like',"%$author%"];
        }
        $data = XinWenModel::where($where)->paginate(2);
        $query = \request()->all();
        return view('xinwen.show',['data'=>$data,'query'=>$query]);
    }

    public function del(){
        $id = \request()->id;
        $res = XinWenModel::where('id',$id)->delete();
        if($res){
            echo "<script>alert('删除成功');location='/xinwen/show'</script>";
        }else{
            echo "<script>alert('删除失败');location='/xinwen/show'</script>";
        }
    }
    public function update(){
        $id = \request()->id;
        $res = XinWenModel::where('id',$id)->first();
        return view('xinwen.update',['data'=>$res]);
    }
    public function do_update(){
        $id = \request()->id;
        $data = \request()->except('_token');
        $res = XinWenModel::where('id',$id)->update($data);
        if($res){
            echo "<script>alert('修改成功');location='/xinwen/show'</script>";
        }else{
            echo "<script>alert('修改失败');location='/xinwen/show'</script>";
        }
    }
    //带参数的二维码
    public function code(){
        $access_token = WeiXin::getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;

        $postData = '{"expire_seconds": 604800, "action_name": "QR_STR_SCENE", "action_info": {"scene": {"scene_str": "1907"}}}';

        $res = Curl::Post($url,$postData);
        $info = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$res;
        echo $info;die;
    }
}
