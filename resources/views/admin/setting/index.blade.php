@extends('admin._construct')
@section('body')
<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">短信宝账号</label>
        <div class="layui-input-inline">
            <input type="text" name="smsBao_username" required  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{\App\Setting::getValueByKey('smsBao_username','chaosir')}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">短信包密码</label>
        <div class="layui-input-inline">
            <input type="text" name="password" required  lay-verify="required" placeholder="" autocomplete="off" class="layui-input" value="{{\App\Setting::getValueByKey('password','haha123')}}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">首页banner</label>
        <div class="layui-input-block">
            <button class="layui-btn" type="button" id="img_product_btn">选择图片</button>
            <div class="layui-form-mid layui-word-aux">请上传 867*306 的图片</div>
            <br>
            <div style="width: 100%;float: left;margin-top: 10px;" id="img_product">
                @if(!empty(\App\Setting::getValueByKey('banner_img')))
                    @foreach(explode('|',\App\Setting::getValueByKey('banner_img')) as $key => $i)
                        <div class="img_product">
                            <img src="{{$i}}" style="width:375px;height: 132px;">
                            <input class="layui-input-block" name="banner_link" value="{{explode('|',\App\Setting::getValueByKey('banner_link'))[$key]}}" style="margin-left:10px;width: 200px;">
                            <button class="layui-btn img_product_delete" type="button">删除</button>
                            <input type="hidden" name="banner_img" value="{{$i}}">
                        </div>

                    @endforeach
                @endif
            </div>
        </div>
    </div>
    {{----}}
    {{--<div class="layui-form-item">--}}
        {{--<label class="layui-form-label">选择框</label>--}}
        {{--<div class="layui-input-block">--}}
            {{--<select name="city" lay-verify="required">--}}
                {{--<option value=""></option>--}}
                {{--<option value="0">北京</option>--}}
                {{--<option value="1">上海</option>--}}
                {{--<option value="2">广州</option>--}}
                {{--<option value="3">深圳</option>--}}
                {{--<option value="4">杭州</option>--}}
            {{--</select>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div class="layui-form-item">--}}
        {{--<label class="layui-form-label">复选框</label>--}}
        {{--<div class="layui-input-block">--}}
            {{--<input type="checkbox" name="like[write]" title="写作">--}}
            {{--<input type="checkbox" name="like[read]" title="阅读" checked>--}}
            {{--<input type="checkbox" name="like[dai]" title="发呆">--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div class="layui-form-item">--}}
        {{--<label class="layui-form-label">开关</label>--}}
        {{--<div class="layui-input-block">--}}
            {{--<input type="checkbox" name="switch" lay-skin="switch">--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div class="layui-form-item">--}}
        {{--<label class="layui-form-label">单选框</label>--}}
        {{--<div class="layui-input-block">--}}
            {{--<input type="radio" name="sex" value="男" title="男">--}}
            {{--<input type="radio" name="sex" value="女" title="女" checked>--}}
        {{--</div>--}}
    {{--</div>--}}
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
@endsection
@section('script')
<script>
    uploadFiles('#img_product_btn',function (res) {
        var html = ""
        html = html + '<div class="img_product">'
        html = html + '<img src="'+res.data.src+'" style="width:375px;height: 132px;">'
        html = html + '<input class="layui-input-block" name="banner_link" style="margin-left:10px;width: 200px;">'
        html = html + '<button class="layui-btn img_product_delete" type="button" >删除</button>'
        html = html + '<input type="hidden" name="banner_img" value="'+res.data.src+'">'
        html = html + '</div>'
        $("#img_product").append(html)
    });
    $("#img_product").on("click",".img_product_delete",function(){
        $(this).parent().remove();
    });
    layui.use(['element','form','layer'], function(){
        var element = layui.element;
        var $ = layui.jquery;
        var form = layui.form;
        var layer = layui.layer;
        //监听提交
        form.on('submit(formDemo)', function(data){
            var data = data.field;
            var banner_img = "";
            var banner_link = "";
            $("input[name='banner_img']").each(function(){
                banner_img += $(this).val()+"|";
            });
            $("input[name='banner_link']").each(function () {
                banner_link += $(this).val() + '|';
            });
                data.banner_img = banner_img;
                data.banner_link = banner_link;
            $.ajax({
                url:'{{url('admin/setting')}}'
                ,type:'post'
                ,dataType:'json'
                ,data: data
                ,success:function (res) {
                    // layer.msg(res.data);
                    window.location.reload();
                }
            });
            return false;

        });

    });
</script>
@endsection