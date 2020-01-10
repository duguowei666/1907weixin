@extends('layouts.admin')

@section('title', '渠道管理--渠道展示')

@section('content')
    <script src="https://code.highcharts.com.cn/highcharts/highcharts.js"></script>
    <script src="https://code.highcharts.com.cn/highcharts/highcharts-more.js"></script>
    <script src="https://code.highcharts.com.cn/highcharts/modules/exporting.js"></script>
    <script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
        <h3>天气图表</h3>
        城市名:<input type="text" name="city">
        <button id="city">搜索</button>(城市名可以为拼音或汉字)


        <script>
            $(document).on('click','#city',function(){
                var city = $('[name="city"]').val();
                if(city==''){
                    alert('请填写城市');
                }
                $.ajax({
                    url: "{{url('/admin/wechat')}}",//提交的路径
                    type:'GET',
                    data: {city:city},
                    dataType:'json',
                    success:function(res){
                        console.log(res)
                    }
                })
            })
        </script>
@endsection