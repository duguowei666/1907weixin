@extends('layouts.admin')

@section('title', '渠道管理--添加渠道')

@section('content')

    <h3>渠道添加</h3>
    <form action="{{url('/admin/channel_do_add')}}" method="post">
        @csrf
        <div class="form-group">
            <label for="exampleInputEmail1">渠道名称</label>
            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="渠道名称" name="name">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">渠道标识</label>
            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="渠道标识" name="sign">
        </div>
        <button type="submit" class="btn btn-default">添加</button>
    </form>

@endsection