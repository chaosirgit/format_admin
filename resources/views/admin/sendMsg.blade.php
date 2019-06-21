@extends('admin._construct')
@section('head')
    <style>
        .specList {
            cursor: pointer;
            background: #fff;
        }
        .specList ul li {
            padding: 10px;
            margin: 10px 0px;
            border-radius: 6px;
            border: 1px solid #eee;
        }

        .specList ul li dl dt {
            margin-bottom: 10px;
        }

        .specList ul {
            position: relative;
            width: calc(100% - 20px);
        }
        .operateBar {
            text-align: right;
            float: right;
        }

        .elem-select {
            display: block;
            padding: 0px;
            margin: 0px;
            width: 4px;
            height: 4px;
            border-top: 2px solid transparent;
            border-right: 2px solid red;
            border-bottom: 2px solid red;
            border-left: 2px solid transparent;
            position: relative;
            top: -5px;
            right: -10px;
        }

    </style>
@endsection
@section('body')
    <form class="layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">手机号</label>
            <div class="layui-input-inline">
                <input type="text" name="mobile" placeholder="请输入手机号" value="" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发送内容</label>
            <div class="layui-input-block">
                <textarea name="content" placeholder="请输入内容" class="layui-textarea"></textarea>
            </div>
        </div>
        {{--<div class="layui-form-item">--}}
        {{--<label class="layui-form-label">模型说明</label>--}}
        {{--<div class="layui-input-block">--}}
        {{--<textarea name="explain" placeholder="请输入模型说明" autocomplete="off" class="layui-textarea">@if(isset($model["explain"])){{$model["explain"]}}@endif</textarea>--}}
        {{--</div>--}}
        {{--</div>--}}

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="form_submit">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
@endsection
@section('script')
    <script>
        layui.use(['element', 'form', 'layer'], function() {
            var element = layui.element, form = layui.form, layer = layui.layer;
            form.on('submit(form_submit)', function (data) {
                var data = data.field;
                $.ajax({
                    url: '{{url('admin/sendMsg')}}',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    success: function (res) {
                        if(res.code == 200){
                            layer.msg("操作成功", {
                                icon: 1,
                                time: 500,
                                end: function() {
                                    var index = parent.layer.getFrameIndex(window.name);
                                    parent.layer.close(index);//关闭当前页
                                }
                            });
                        }else{
                            layer.msg(res.data);
                        }
                    }
                });
                return false;
            });
        });
    </script>
@endsection
