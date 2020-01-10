@extends('layouts.admin')

@section('title', '新闻添加')

@section('content')

<form action="{{url('/xinwen/do_add')}}" method="post">
    @csrf
    <div class="form-group">
        <label for="exampleInputEmail1">新闻标题</label>
        <input type="text" class="form-control" placeholder="标题" name="title">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">新闻内容</label>
        <textarea name="content" id="" cols="30" rows="10"></textarea>
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">新闻作者</label>
        <select name="author" id="">
            <option value="1">张三</option>
            <option value="2">李四</option>
        </select>
    </div>
    <button type="submit" class="btn btn-default">添加</button>
</form>
@endsection