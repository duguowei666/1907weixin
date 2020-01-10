@extends('layouts.admin')

@section('title', '新闻展示')

@section('content')
<form>
    <input type="text" name="title" placeholder="新闻标题" value="{{$query['title']??''}}">
    <label for="exampleInputEmail1">新闻作者</label>
    <select name="author" id="">
        <option value="1" {{$query['author']??''==1?'selected':''}}>张三</option>
        <option value="2" {{$query['author']??''==2?'selected':''}}>李四</option>
    </select>
    <button>搜索</button>
</form>
<table  class="table table-hover  table-condensed table-bordered table-striped">
    <tr>
        <td>id</td>
        <td>新闻标题</td>
        <td>新闻内容</td>
        <td>新闻作者</td>
        <td>时间</td>
        <td>操作</td>
    </tr>
    @foreach($data as $v)
        <tr>
            <td>{{$v->id}}</td>
            <td>{{$v->title}}</td>
            <td>{{$v->content}}</td>
            <td>{{$v->author==1?'张三':'李四'}}</td>
            <td>{{date('Y-m-d H:i:s',$v->add_time)}}</td>
            <td>
                <a href="{{url('xinwen/del/'.$v->id)}}">删除</a>
                <a href="{{url('xinwen/update/'.$v->id)}}">修改</a>
            </td>
        </tr>
    @endforeach
</table>
{{$data->appends($query)->links()}}
@endsection