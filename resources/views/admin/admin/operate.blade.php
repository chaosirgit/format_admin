@extends('admin._construct')
@section('body')
    <div class="layui-inline layui-row layui-form layui-form-item" style="margin-top: 10px;">
        <div class="layui-inline">
            <label class="layui-form-label">管理员</label>
            <div class="layui-input-inline">
                <select name="search_admin" lay-filter="">
                    <option value="">请选择管理员</option>
                    @foreach($admins as $admin)
                        <option value="{{$admin->id}}">{{$admin->username}}</option>
                    @endforeach
                </select>
            </div>
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
            <label class="layui-form-label">操作</label>
            <div class="layui-input-inline">
                <select name="search_action" lay-filter="" lay-search>
                    <option value="">请选择操作</option>
                    @foreach($actions as $action)
                        <option value="{{$action->id}}">{{$action->name}}</option>
                    @endforeach
                </select>
            </div>

            <label class="layui-form-label">时间</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="date" name="search_time">
            </div>
            <div class="layui-input-inline">
                <button class="layui-btn btn-search" id="mobile_search" lay-submit lay-filter="mobile_search"> <i class="layui-icon">&#xe615;</i> </button>
            </div>
        </div>

    </div>
    <div style="margin: 10px;">
        {{--<div style="margin-top: 10px;width: 100%;margin-left: 10px;">
            <button class="layui-btn layui-btn-normal layui-btn-radius" onclick="layer_show('添加管理员','{{url('admin/admin_add')}}')">添加管理员</button>
        </div>--}}

        <table id="user" lay-filter="user"></table>
    </div>


    <script type="text/html" id="statusName">
        <span class="@{{ d.status == 1 ? 'layui-badge' : d.status == 2 ? 'layui-badge layui-bg-orange' : d.status == 3 ? 'layui-badge layui-bg-blue' : d.status == 4 ? 'layui-badge layui-bg-cyan' : d.status == 5 ? 'layui-badge layui-bg-green' : '' }}">@{{ d.status_name }}</span>
    </script>


    <script type="text/html" id="payName">
        <span class="@{{ d.pay_code == 0 ? 'layui-badge' : d.pay_code == 1 ? 'layui-badge layui-bg-blue' : d.pay_code == 2 ? 'layui-badge layui-bg-green' : d.pay_code == 3 ? 'layui-badge layui-bg-orange' : '' }}">@{{ d.pay_name }}</span>
    </script>

    <script type="text/html" id="barDemo">
        {{--<a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看预定</a>--}}
        <a class="layui-btn layui-btn-xs" lay-event="show">查看</a>
        {{--<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>--}}
    </script>
@endsection

@section('script')
    <script>

        layui.use(['table','form','laydate'], function(){
            var table = layui.table;
            var $ = layui.jquery;
            var form = layui.form;
            var laydate = layui.laydate;

            laydate.render({
                elem:'#date',
                type:'datetime'
            });
            //第一个实例
            table.render({
                elem: '#user'
                ,url: '{{url('admin/admin_operate_list')}}' //数据接口
                ,page: true //开启分页
                ,id:'mobileSearch'
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', minWidth:80, align: 'center', sort: true, fixed: 'left'}
                    ,{field: 'admin_name', title: '管理员', minWidth:80, align: 'center', fixed: 'left'}
                    ,{field: 'role_name', title: '所属角色', minWidth:80, align: 'center', fixed: 'left'}
                    ,{field: 'action_name', title: '操作', minWidth:80, align: 'center', fixed: 'left'}
                    ,{field: 'result_msg', title: '操作结果', minWidth:80, align: 'center', fixed: 'left'}
                    // ,{field: 'info', title: '信息', minWidth:80, align: 'center', fixed: 'left',templet: '#statusName'}
                    //,{field: 'info', title: '信息', minWidth:80, align: 'center', fixed: 'left'}
                    //,{field: 'type_name', title: '类型', minWidth:80, align: 'center', fixed: 'left'}
                    ,{field: 'create_time', title: '时间', minWidth:80, align: 'center', fixed: 'left'}
                    // ,{field: 'pay_time', title: '支付时间', minWidth:80, align: 'center', fixed: 'left'}
                    // ,{field: 'done_time', title: '完成时间', minWidth:80, align: 'center', fixed: 'left'}
                    // ,{title:'操作',minWidth:100, align: 'center', toolbar: '#barDemo'}

                ]]
            });


            form.on('switch(sexDemo)', function(obj){
                var id = this.value;
                $.ajax({
                    url:'{{url('admin/product_hot')}}',
                    type:'post',
                    dataType:'json',
                    data:{id:id},
                    success:function (res) {
                        if(res.error != 0){
                            layer.msg(res.msg);
                        }
                    }
                });
            });

            table.on('tool(user)', function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    layer.confirm('真的删除行么', function(index){
                        $.ajax({
                            url:'{{url('admin/u')}}',
                            type:'post',
                            dataType:'json',
                            data:{id:data.id},
                            success:function (res) {
                                if(res.type == 'error'){
                                    layer.msg(res.message);
                                }else{
                                    obj.del();
                                    layer.close(index);
                                }
                            }
                        });
                    });
                } else if(obj.event === 'show'){
                    layer_show('查看订单','{{url('admin/order_show')}}?id='+data.id,800,600);
                }
            });

            //监听提交
            form.on('submit(mobile_search)', function(data){

                var search_admin = data.field.search_admin;
                var search_action = data.field.search_action;
                var search_time = data.field.search_time;
                //var search_pay = data.field.search_pay;

                table.reload('mobileSearch',{
                    where:{search_admin,search_action,search_time},
                    page: {curr: 1}         //重新从第一页开始
                });
                return false;
            });

        });
    </script>
@endsection