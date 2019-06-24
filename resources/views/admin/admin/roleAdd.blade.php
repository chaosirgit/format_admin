@extends('admin._construct')
@section('body')
    <form class="layui-form col-lg-5">
        <div class="layui-form-item">
            <label class="layui-form-label">角色名称</label>
            <div class="layui-input-inline">
                <input type="text" name="name" autocomplete="off" class="layui-input" value="{{ $result->name }}" placeholder="">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">超级管理员</label>
            <div class="layui-input-block">
                <input type="radio" name="is_super" value="1" title="是" {{$result->is_super ? 'checked':''}}>
                <input type="radio" name="is_super" value="0" title="否" {{!$result->is_super ? 'checked':''}}>
            </div>
        </div>


        <input type="hidden" name="id" value="{{$result->id}}">
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="adminrole_submit">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>

        layui.use(['element','form','layer'], function(){
            var element = layui.element;
            var $ = layui.jquery;
            var form = layui.form;
            var layer = layui.layer;
            //监听提交
            form.on('submit(adminrole_submit)', function(data){
                var data = data.field;

                $.ajax({
                    url:'{{url('admin/admin_role_add')}}'
                   ,type:'post'
                   ,dataType:'json'
                   ,data: data
                   ,success:function (res) {
                        layer.msg(res.data);
                        if(res.code == 200) {
                            var index = parent.layer.getFrameIndex(window.name);
                            setTimeout(function(){
                                parent.layer.close(index);
                                parent.window.location.reload();
                            },1500);

                        }else{
                            return false;
                        }
                    }
                });
                return false;

            });

        });
    </script>
@endsection