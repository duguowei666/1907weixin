<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Curl;
use App\Tools\WeiXin;
use App\Model\MediaModel;
class MediaController extends Controller
{
    //添加素材
    public function add(){
        return view('media.add');
    }

    //执行添加展示
    public function do_add(request $request){
        $data = $request->except('_token');
        //1、laravel文件上传
        $file = $request->file;
        if (!$request->hasFile('file')) {
            echo '文件上传失败';
        }
        $ext = $file->getClientOriginalExtension();     //得到文件后缀名
        $filename = md5(uniqid()).".".$ext;

        $filePath = $file->storeAs('images',$filename);

        //2、调用微信上传素材接口   把图片上传到服务器
        $res = WeiXin::uploadMedia($data,$filePath);
        if(isset($res['media_id'])){
            $media_id = $res['media_id'];       //微信返回的素材id
            $mediaInfo = MediaModel::create([
                'media_name'    => $data['media_name'],
                'media_format'  => $data['media_format'],
                'media_type'    => $data['media_type'],
                'media_url'     => $filePath,
                'wx_media_id'   => $media_id,
                'add_time'      => time(),
            ]);
            if($mediaInfo){
                echo "<script>alert('上传成功');location='/admin/media_show'</script>";
            }else{
                echo "<script>alert('上传成功');location='/admin/media_add'</script>";
            }
        }

    }

    //展示
    public function show(){
        $data = MediaModel::all();
        return view('media.show',['data'=>$data]);
    }
}
