@extends('admin._construct')
@section('body')
    <div class="layui-inline layui-row layui-form" style="margin-top: 10px;">
        <label class="layui-form-label">用户帐号</label>
        <div class="layui-input-inline">
            <input type="text" name="search_value" placeholder="用户手机号" autocomplete="off" class="layui-input" value="">
        </div>
        <button class="layui-btn btn-search" id="mobile_search" lay-submit lay-filter="mobile_search"> <i class="layui-icon">&#xe615;</i> </button>
    </div>
    <div style="margin: 10px;">
        {{--<div style="margin-top: 10px;width: 100%;margin-left: 10px;">
            <button class="layui-btn layui-btn-normal layui-btn-radius" onclick="layer_show('添加管理员','{{url('admin/admin_add')}}')">添加管理员</button>
        </div>--}}

        <table id="user" lay-filter="user"></table>
    </div>

    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="conf">调节账户</a>
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        {{--<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>--}}
    </script>
@endsection

@section('script')
    <script>

        layui.use(['table','form'], function(){
            var table = layui.table;
            var $ = layui.jquery;
            var form = layui.form;
            //第一个实例
            table.render({
                elem: '#user'
                ,url: '{{url('admin/user_list')}}' //数据接口
                ,page: true //开启分页
                ,id:'mobileSearch'
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', minWidth:80, align: 'center', sort: true, fixed: 'left'}
                    ,{field: 'mobile', title: '账号', minWidth:80, align: 'center', fixed: 'left'}
                    ,{field: 'nickname', title: '昵称', minWidth:80, align: 'center', fixed: 'left'}
                    ,{field: 'balance', title: '余额', minWidth:80, align: 'center', fixed: 'left'}
                    ,{field: 'integral', title: '积分', minWidth:80, align: 'center', fixed: 'left'}
                    ,{field: 'create_time', title: '注册时间', minWidth:80, align: 'center', fixed: 'left'}

                    ,{title:'操作',minWidth:100, align: 'center', toolbar: '#barDemo'}

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
                } else if(obj.event === 'edit'){
                    layer_show('用户编辑','{{url('admin/user_add')}}?id='+data.id);
                } else if(obj.event === 'conf'){
                    layer_show('调节账户','{{url('admin/user_conf')}}?id='+data.id,800,600);
                }
            });

            //监听提交
            form.on('submit(mobile_search)', function(data){

                var search_value = data.field.search_value;

                table.reload('mobileSearch',{
                    where:{search_value:search_value},
                    page: {curr: 1}         //重新从第一页开始
                });
                return false;
            });

        });
    </script>
@endsection