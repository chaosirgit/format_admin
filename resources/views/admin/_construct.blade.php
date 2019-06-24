<!DOCTYPE html>
<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>派好商城后台管理系统</title>
    <link rel="stylesheet" href="{{url('admin/plugins/layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{url('admin/plugins/font-awesome/css/font-awesome.min.css')}}" media="all">
    <link rel="stylesheet" href="{{url('admin/src/css/app.css')}}" media="all">
    <link rel="stylesheet" href="{{url('admin/chosen/chosen.min.css')}}" media="all">
    <style>
        #upload_img_list {
            margin: 10px 0 0 0
        }
        #upload_img_list dd {
            position: relative;
            margin: 0 10px 10px 0;
            float: left
        }
        #upload_img_list .operate {
            position: absolute;
            top: 0;
            right: 0;
            z-index: 1
        }
        #upload_img_list .operate i {
            cursor: pointer;
            background: #2F4056;
            padding: 2px;
            line-height: 15px;
            text-align: center;
            color: #fff;
            margin-left: 1px;
            float: left;
            filter: alpha(opacity=80);
            -moz-opacity: .8;
            -khtml-opacity: .8;
            opacity: .8
        }
        #upload_img_list dd .img {
            max-height: 150px;
            max-width: 500px
        }
    </style>
    @yield('head')
</head>

<body>
@yield('body')
<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
<script src="{{url('admin/plugins/layui/layui.js')}}"></script>
<script src="{{url('admin/chosen/chosen.jquery.min.js')}}"></script>
<script>
    layui.use(['element'],function () {
        var $ = layui.jquery;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });


    function uploadImgDel(elementId) {
        layui.use(['element'],function () {
            var $ = layui.jquery;
            $("#"+elementId).remove();
        });
    }

@if(!empty($qiniu_token))
    function uploadFiles(elem,callback) {
        layui.use(['upload'], function () {
            var $ = layui.jquery
                ,upload = layui.upload;
            //多图片上传
            upload.render({
                elem: elem
                , url: 'https://up-z2.qiniup.com/'
                , data: {'token': '{{$qiniu_token}}'}
                ,accept:'file'
                , multiple: true
                , done: function (res) {
                    $.ajax({
                        url:'{{url('api/save_upload')}}'
                        ,data:{'key':res.key,'ext':res.ext,'file_size':res.fsize,'file_name':res.fname,'user_id':'{{session('admin_id')}}','is_admin':1}
                        ,dataType:'json'
                        ,type:'post'
                        ,success:function (result) {
                            if (result.code != 200){
                                layer.msg(result.data);
                            } else {
                                callback(result)
                            }
                        }
                    });
                }
            });
        });
    }
    @else
    function uploadFiles(elem,callback) {
        layui.use(['upload'], function () {
            var $ = layui.jquery
                ,upload = layui.upload;
            //多图片上传
            upload.render({
                elem: elem
                , url: '{{url('local/upload')}}'
                // , data:
                ,accept:'file'
                , multiple: true
                , done: function (res) {
                    $.ajax({
                        url:'{{url('api/save_upload')}}'
                        ,data:{'key':res.data.key,'ext':res.data.ext,'file_size':res.data.fsize,'file_name':res.data.fname,'user_id':'{{session('admin_id')}}','is_admin':1}
                        ,dataType:'json'
                        ,type:'post'
                        ,success:function (result) {
                            if (result.code != 200){
                                layer.msg(result.data);
                            } else {
                                callback(result)
                            }
                        }
                    });
                }
            });
        });
    }
    @endif
    function layer_show(title,url,w,h) {
        var width = w || null;
        var height = h || null;
        var areaValue;
        if (width != null) {
            areaValue = width + 'px';
            if (height != null) {
                areaValue = [width + 'px', height + 'px'];
            }
        }else{
            areaValue = ['100%','100%'];
        }
        layui.use('layer', function () { //独立版的layer无需执行这一句
            var $ = layui.jquery, layer = layui.layer; //独立版的layer无需执行这一句
            layer.open({
                type: 2 //此处以iframe举例
                , title: title
                , area: areaValue
                , shade: 0
                , maxmin: true
                , content: url
                , offset: '10px'
            });
        });
    }


    /**
     * 关联下拉
     * @param url
     * @param DOM
     * @param k
     * @param v
     * @param defal 默认值
     */
    function assoc_down(url, DOM, k, v, defal) {
        layui.use(['form'], function(){
            var $ = layui.jquery,
                form = layui.form;
            var id = k || 'region_id';
            var name = v || 'region_name';
            var val = defal || null;
            $.get(url, function (res) {
                var data = '';
                $.each(res.data, function(key ,obj){
                    data += '<option value='+ obj[id] +'>'+obj[name]+'</option>';
                });
                DOM.html("<option value=''>请选择</option>" +data);
                DOM.val(val);
                form.render('select');
            });
        });
    }

    $('img').each(function () {
        $(this).click(function(){
            window.open($(this).attr('src'),'_blank');
        })
    });
</script>
@yield('script')
</body>
