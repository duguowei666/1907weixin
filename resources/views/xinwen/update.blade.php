@extends('layouts.admin')

@section('title', '新闻修改')

@section('content')

<form action="{{url('/xinwen/do_update/'.$data->id)}}" method="post">
    @csrf
    <div class="form-group">
        <label for="exampleInputEmail1">新闻标题</label>
        <input type="text" class="form-control" placeholder="标题" name="title" value="{{$data['title']}}">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">新闻内容</label>
        <textarea name="content" id="" cols="30" rows="10">{{$data['content']}}</textarea>
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">新闻作者</label>
        <select name="author" id="">
            <option value="1" {{$data->author==1?'selected':''}}>张三</option>
            <option value="2" {{$data->author==2?'selected':''}}>李四</option>
        </select>
    </div>
    <button type="submit" class="btn btn-default">修改</button>
</form>
@endsection