@extends('layouts.admin')

@section('title', '渠道管理--渠道展示')

@section('content')

    <h3>渠道展示</h3>
    <table class="table table-bordered table-hover table-condensed">
        <tr>
            <td>id</td>
            <td>渠道名称</td>
            <td>渠道标识</td>
            <td>渠道二维码</td>
            <td>关注人数</td>
        </tr>
        @foreach($data as $v)
            <tr>
                <td>{{$v->id}}</td>
                <td>{{$v->name}}</td>
                <td>{{$v->sign}}</td>
                <td>
                    <img src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={{$v->code}}" alt="" width="100px">
                </td>
                <td>{{$v->number}}</td>
            </tr>
        @endforeach
    </table>

@endsection