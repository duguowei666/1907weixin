<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\CuserModel;
use Illuminate\Http\Request;
use App\Tools\WeiXin;
use App\Tools\Curl;
use App\Model\ChannelModel;
class ChannelController extends Controller
{
    //渠道添加视图
    public function add(){
        return view('channel.add');
    }
    //渠道添加
    public function do_add(){
        //接值
        $data = \request()->except('_token');
        //获取ticket
        $ticket = WeiXin::ticket($data);
        //入库
        $data['code'] = $ticket;
        $res = ChannelModel::create($data);
        if($res){
            echo "<script>alert('添加成功');location='/admin/channel_show'</script>";
        }else{
            echo "<script>alert('添加失败');location='/admin/channel_add'</script>";
        }
    }

    public function show(){
        $data = ChannelModel::all();
        return view('channel.show',['data'=>$data]);
    }

    //图表统计
    public function chart_show(){
        $data = ChannelModel::all()->toArray();
        $xstr = '';
        $ystr = '';
        foreach ($data as $k=>$v){
            $xstr .= '"'.$v['name'].'",';
            $ystr .= $v['number'].',';
        }
        $xstr = rtrim($xstr,',');
        $ystr = rtrim($ystr,',');
        return view('channel.chart',[
            'xstr'  => $xstr,
            'ystr'  => $ystr
        ]);
    }
}
