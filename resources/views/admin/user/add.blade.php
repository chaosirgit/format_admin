@extends('admin._construct')
@section('body')
    <form class="layui-form col-lg-5">
        <div class="layui-form-item">
            <label class="layui-form-label">用户账号</label>
            <div class="layui-input-inline">
                <input type="text" name="mobile" autocomplete="off" class="layui-input" value="{{ $result->mobile }}" placeholder="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户昵称</label>
            <div class="layui-input-inline">
                <input type="text" name="nickname" autocomplete="off" class="layui-input" value="{{ $result->nickname }}" placeholder="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户密码</label>
            <div class="layui-input-inline">
                <input type="password" name="password" autocomplete="off" class="layui-input" placeholder="">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">用户头像</label>
            <div class="layui-upload layui-input-inline">
                <button type="button" class="layui-btn" id="avatar_btn">上传图片</button>
                <div class="layui-upload-list" style="width: 400px;height: 200px;">
                    <img class="layui-upload-img" id="avatar" style="width: 400px;height: 200px;" src="{{$result->avatar}}">
                    {{--<p id="demoText"></p>--}}
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">推荐人手机号</label>
            <div class="layui-input-inline">
                <input type="text" name="parent_mobile" autocomplete="off" class="layui-input" value="{{ $result->parent_mobile }}" placeholder="">
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

            uploadFiles('#avatar_btn',function(res){
                console.log(res);
                $('#avatar').attr('src', res.data.src);
            });

            //监听提交
            form.on('submit(adminrole_submit)', function(data){
                var data = data.field;
                data.avatar = $('#avatar').attr('src');
                console.log(data);
                $.ajax({
                    url:'{{url('admin/user_edit')}}'
                    ,type:'post'
                    ,dataType:'json'
                    ,data: data
                    ,success:function (res) {
                        layer.msg(res.data);
                        if(res.code == 200) {
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                            parent.window.location.reload();
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