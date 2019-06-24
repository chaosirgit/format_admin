@extends('admin._construct')
@section('body')
    <form class="layui-form col-lg-5">
        <div class="layui-form-item">
            <label class="layui-form-label">用户账号</label>
            <div class="layui-input-inline">
                <input type="text" name="mobile" autocomplete="off" class="layui-input" value="{{ $result->mobile }}" placeholder="" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户昵称</label>
            <div class="layui-input-inline">
                <input type="text" name="nickname" autocomplete="off" class="layui-input" value="{{ $result->nickname }}" placeholder="" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">调节账户</label>
            <div class="layui-input-block">
                <select name="type" lay-verify="required">
                    <option value=""></option>
                    <option value="1">余额</option>
                    {{--<option value="2">法币交易锁定余额</option>--}}
                    <option value="3">积分</option>
                    {{--<option value="4">币币交易锁定余额</option>--}}
                    {{--<option value="5">杠杆交易余额</option>--}}
                    {{--<option value="6">杠杆交易锁定余额</option>--}}

                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">调节方式</label>
            <div class="layui-input-block">
                <input type="radio" name="way" value="increment" title="增加"  checked>
                <input type="radio" name="way" value="decrement" title="减少">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">调节值</label>
            <div class="layui-input-block">
                <input type="text" name="conf_value" required  lay-verify="required" placeholder="请输入需要调节的数值" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">调节备注</label>
            <div class="layui-input-block">
                <textarea name="info" placeholder="请输入内容" class="layui-textarea"></textarea>
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
                // data.avatar = $('#avatar').attr('src');
                // console.log(data);
                $.ajax({
                    url:'{{url('admin/user_conf')}}'
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