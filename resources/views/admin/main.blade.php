@extends('admin._construct')
@section('head')
    <script src="https://cdn.bootcss.com/echarts/4.2.1/echarts.min.js"></script>
@endsection
@section('body')
    {{--<div class="layui-inline layui-row layui-form layui-form-item" style="margin-top: 10px;">--}}
        {{--<div class="layui-inline">--}}
            {{--<label class="layui-form-label">管理员</label>--}}
            {{--<div class="layui-input-inline">--}}
                {{--<select name="search_admin" lay-filter="">--}}
                    {{--<option value="">请选择管理员</option>--}}
                {{--</select>--}}
            {{--</div>--}}
            {{--<label class="layui-form-label">商家名</label>--}}
            {{--<div class="layui-input-inline">--}}
            {{--<input type="text" name="search_seller" placeholder="商家名" autocomplete="off" class="layui-input" value="">--}}
            {{--</div>--}}
            {{--<label class="layui-form-label">订单状态</label>--}}
            {{--<div class="layui-input-inline">--}}
            {{--<select name="search_status" lay-filter="">--}}
            {{--<option value=""></option>--}}
            {{--<option value="1">待付款</option>--}}
            {{--<option value="2">已付款</option>--}}
            {{--<option value="3">已发货</option>--}}
            {{--<option value="4">已收货</option>--}}
            {{--<option value="5">已评价</option>--}}
            {{--</select>--}}
            {{--</div>--}}
            {{--<label class="layui-form-label">操作</label>--}}
            {{--<div class="layui-input-inline">--}}
                {{--<select name="search_action" lay-filter="" lay-search>--}}
                    {{--<option value="">请选择操作</option>--}}
                {{--</select>--}}
            {{--</div>--}}

            {{--<label class="layui-form-label">时间</label>--}}
            {{--<div class="layui-input-inline">--}}
                {{--<input type="text" class="layui-input" id="date" name="search_time">--}}
            {{--</div>--}}
            {{--<div class="layui-input-inline">--}}
                {{--<button class="layui-btn btn-search" id="mobile_search" lay-submit lay-filter="mobile_search"> <i class="layui-icon">&#xe615;</i> </button>--}}
            {{--</div>--}}
        {{--</div>--}}

    {{--</div>--}}
    <div class="layui-row">
        <div class="layui-col-md6">
            <div id="one" style="width: 100%;height: 500px"></div>
        </div>
        <div class="layui-col-md6">
            <div id="two" style="width: 100%;height: 500px"></div>
        </div>
    </div>
    <div class="layui-row">
        <div class="layui-col-md6">
            <div id="three" style="width: 100%;height: 500px"></div>
        </div>
        <div class="layui-col-md6">
            <div id="four" style="width: 100%;height: 500px"></div>
        </div>
    </div>

@endsection

@section('script')

    <script>
        var oneChart = echarts.init(document.getElementById('one'));
        var twoChart = echarts.init(document.getElementById('two'));
        var threeChart = echarts.init(document.getElementById('three'));
        var fourChart = echarts.init(document.getElementById('four'));
        // 指定图表的配置项和数据

        var oneOption = {
            title : {
                text: '平台PB收益',
                subtext: '总收益为{{$pb_income['sum']}}',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                left: 'left',
                data: ['自营订单','入驻商家','上传产品','上传图片','上传视频','交易抽点']
            },
            series : [
                {
                    name: 'PB 收益',
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data:[
                        {value:'{{$pb_income['order']}}', name:'自营订单'},
                        {value:'{{$pb_income['seller_add']}}', name:'入驻商家'},
                        {value:'{{$pb_income['pay_product']}}', name:'上传产品'},
                        {value:'{{$pb_income['pay_ad_img']}}', name:'上传图片'},
                        {value:'{{$pb_income['pay_ad_video']}}', name:'上传视频'},
                        {value:'{{$pb_income['sure_express']}}', name:'交易抽点'},
                        {{--{value:'{{$pb_income['admin']}}', name:'后台调节'},--}}

                    ],
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        };


        var twoOption = {
            title: {
                text:'积分堆叠图'
            },
            tooltip : {
                trigger: 'axis',
                axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                    type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                },
                formatter: ""
            },
            legend: {
                data: ['送用户', '送商家','送推荐人','平台抽取','积分订单','自营收入']
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis:  {
                type: 'value'
            },
            yAxis: {
                type: 'category',
                data: ['送出','收入'],
            },
            series: [
                {
                    name: '送用户',
                    type: 'bar',
                    stack: '积分',
                    label: {
                        normal: {
                            show: true,
                            position: 'insideRight'
                        }
                    },
                    data: [{{$integral_income['gift_user']}},0]
                },
                {
                    name: '送商家',
                    type: 'bar',
                    stack: '积分',
                    label: {
                        normal: {
                            show: true,
                            position: 'insideRight'
                        }
                    },
                    data: [{{$integral_income['gift_seller']}},0]
                },
                {
                    name: '送推荐人',
                    type: 'bar',
                    stack: '积分',
                    label: {
                        normal: {
                            show: true,
                            position: 'insideRight'
                        }
                    },
                    data: [{{$integral_income['gift_parent']}},0]
                },
                {
                    name: '平台抽取',
                    type: 'bar',
                    stack: '积分',
                    label: {
                        normal: {
                            show: true,
                            position: 'insideRight'
                        }
                    },
                    data: [0,{{$integral_income['sub']}}]
                },
                {
                    name: '积分订单',
                    type: 'bar',
                    stack: '积分',
                    label: {
                        normal: {
                            show: true,
                            position: 'insideRight'
                        }
                    },
                    data: [0,{{$integral_income['self_income']}}]
                },
                {
                    name: '自营收入',
                    type: 'bar',
                    stack: '积分',
                    label: {
                        normal: {
                            show: true,
                            position: 'insideRight'
                        }
                    },
                    data: [0,{{$integral_income['system_seller']}}]
                }
            ]
        };

        var threeOption = {
            title: {
                text: '会员增速折线图'
            },
            tooltip: {},
            legend: {
                data:['会员个数']
            },
            xAxis: {
                type: 'category',
                data: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月']
            },
            yAxis: {type: 'value'},
            series: [{
                data: [{{$user_counts}}],
                type: 'line'
            }]
        };


        var fourOption = {
            title : {
                text: '其他数据统计',
                subtext: '',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                left: 'left',
                data: ['总订单数','总产品数','总图片广告','总视频广告','总会员','总商家']
            },
            series : [
                {
                    name: '数量统计',
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data:[
                        {value:'{{$other_count['order_count']}}', name:'总订单数'},
                        {value:'{{$other_count['product_count']}}', name:'总产品数'},
                        {value:'{{$other_count['ad_img_count']}}', name:'总图片广告'},
                        {value:'{{$other_count['ad_video_count']}}', name:'总视频广告'},
                        {value:'{{$other_count['user_count']}}', name:'总会员'},
                        {value:'{{$other_count['seller_count']}}', name:'总商家'}
                    ],
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        };





        // 使用刚指定的配置项和数据显示图表。
        oneChart.setOption(oneOption);
        twoChart.setOption(twoOption);
        threeChart.setOption(threeOption);
        fourChart.setOption(fourOption);

        layui.use(['table','form','laydate'], function(){
            var table = layui.table;
            var $ = layui.jquery;
            var form = layui.form;
            var laydate = layui.laydate;

            laydate.render({
                elem:'#date',
                type:'datetime'
            });


            //监听提交
            form.on('submit(mobile_search)', function(data){

                var search_admin = data.field.search_admin;
                var search_action = data.field.search_action;
                var search_time = data.field.search_time;
                //var search_pay = data.field.search_pay;

                return false;
            });

        });
    </script>
@endsection