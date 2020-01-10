@extends('layouts.admin')

@section('title', '素材管理--添加素材')

@section('content')

    <h3>素材添加</h3>
    <form action="{{url('/admin/do_add')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="exampleInputEmail1">素材名称</label>
            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="素材名称" name="media_name">
        </div>
        <div class="form-group">
            <label for="exampleInputFile">素材上传</label>
            <input type="file" id="exampleInputFile" name="file">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">素材类型</label>
            <select name="media_type" class="form-control">
                <option value="1">临时</option>
                <option value="2">永久</option>
            </select>
        </div>

        <div class="form-group">
            <label for="exampleInputEmail1">素材格式</label>
            <select name="media_format" class="form-control">
                <option value="image">图片</option>
                <option value="voice">语音</option>
                <option value="video">视频</option>
            </select>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>

@endsection