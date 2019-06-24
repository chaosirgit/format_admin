@extends('admin._construct')
@section('body')
    <form class="layui-form col-lg-5">
        @foreach($modules as $module)
            <div class="layui-form-item">
                <label class="layui-form-label">{{$module->name}}</label>
                <input type="checkbox" value="{{$module->id}}" lay-skin="primary" title="全选" lay-filter="allCh">
                <div class="layui-input-block">
                    @foreach($module->children as $action)
                        <input type="checkbox" name="permission[{{$module->id}}][]" @if(in_array($action->id,$results)) checked @endif title="{{$action->name}}" value="{{$action->id}}">
                    @endforeach
                </div>

            </div>
        @endforeach
        <input type="hidden" value="{{$role_id}}" name="id">
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="permission_submit">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>

        layui.use(['form','upload','layer'], function () {
            var layer = layui.layer;
            var form = layui.form;
            var $ = layui.jquery;
            form.on('submit(permission_submit)', function (data) {
                var data = data.field;
                $.ajax({
                    url: '{{url('admin/permission')}}',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    success: function (res) {
                        layer.msg(res.message);
                        if (res.code == 200) {
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                            parent.window.location.reload();
                        } else {
                            return false;
                        }
                    }
                });
                return false;
            });


            form.on('checkbox(allCh)', function (data) {
                var index = data.value;
                $("input[name='permission[" + index + "][]']").each(function (i, obj) {
                    obj.checked = data.elem.checked;
                });
                form.render('checkbox');
            });
        });
    </script>
@endsection