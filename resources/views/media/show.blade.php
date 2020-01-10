@extends('layouts.admin')

@section('title', '素材管理--添加素材')

@section('content')

    <h3>素材展示</h3>
    <table class="table table-bordered table-hover table-condensed">
        <tr>
            <td>id</td>
            <td>素材名称</td>
            <td>素材类型</td>
            <td>素材格式</td>
            <td>展示</td>
            <td>操作</td>
        </tr>
        @foreach($data as $v)
        <tr>
            <td>{{$v->media_id}}</td>
            <td>{{$v->media_name}}</td>
            <td>{{$v->media_type==1?'临时':'永久'}}</td>
            <td>{{$v->media_format}}</td>
            <td>
                @if($v->media_format == 'image')
                    <img src="\{{$v->media_url}}" alt="" width="100px">
                @elseif($v->media_format == 'voice')
                    <audio src="\{{$v->media_url}}" controls="controls" width="100px"></audio>
                @elseif($v->media_format == 'video')
                    <video src="\{{$v->media_url}}" controls="controls" width="100px"></video>
                @endif
            </td>
            <td><a href="">删除</a></td>
        </tr>
        @endforeach
    </table>

@endsection