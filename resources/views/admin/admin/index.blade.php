@extends('admin._construct')
@section('body')
    <div style="margin: 10px;">
        <div style="margin-top: 10px;width: 100%;margin-left: 10px;">
            <button class="layui-btn layui-btn-normal layui-btn-radius" onclick="layer_show('添加管理员','{{url('admin/admin_add')}}')">添加管理员</button>
        </div>

        <table id="demo" lay-filter="test"></table>
    </div>

    <script type="text/html" id="barDemo">
        {{--<a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看预定</a>--}}
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
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
                elem: '#demo'
                ,url: '{{url('admin/admin_user_list')}}' //数据接口
                ,page: true //开启分页
                ,id:'mobileSearch'
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', minWidth:80, align: 'center', sort: true, fixed: 'left'}
                    ,{field: 'username', title: '用户名', minWidth:80, align: 'center', fixed: 'left'}
                    ,{field: 'role_name', title: '角色', minWidth:80, align: 'center', fixed: 'left'}
                    // ,{field: 'mobile', title: '手机号', minWidth:80}
                    ,{title:'操作',minWidth:100, align: 'center', toolbar: '#barDemo'}

                ]]
            });
            //监听热卖操作
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

            table.on('tool(test)', function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    layer.confirm('真的删除行么', function(index){
                        $.ajax({
                            url:'{{url('admin/admin_del')}}',
                            type:'post',
                            dataType:'json',
                            data:{id:data.id},
                            success:function (res) {
                                layer.msg(res.data);
                                layer.close(index);
                                if(res.code == 200){
                                    obj.del();
                                }
                            }
                        });


                    });
                } else if(obj.event === 'edit'){
                    layer_show('编辑','{{url('admin/admin_add')}}?id='+data.id);
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