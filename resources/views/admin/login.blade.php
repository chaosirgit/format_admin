
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>登入 - {{$project_name}}后台管理系统</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="./plugins/admin.css" media="all">
    <link rel="stylesheet" href="./plugins/login.css" media="all">
    <link rel="stylesheet" href="./plugins/layui/css/layui.css" media="all">

</head>
<body>

<div class="layadmin-user-login layadmin-user-display-show" id="login" style="display: none;">

    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-header">
            <h2>{{$project_name}}</h2>
            <p>后台管理系统</p>
        </div>
        <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="mobile"></label>
                <input type="text" name="mobile" id="mobile" lay-verify="required" placeholder="用户名" class="layui-input">
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="password"></label>
                <input type="password" name="password" id="password" lay-verify="required" placeholder="密码" class="layui-input">
            </div>

            <div class="layui-form-item">
                <button id="login" class="layui-btn layui-btn-fluid" lay-submit="" lay-filter="login_submit">登  入</button>
            </div>

        </div>
    </div>

    <div class="layui-trans layadmin-user-login-footer">

        <p>© 2019 <a href="https://www.memestech.com.cn/" target="_blank">河南模因科技</a></p>

    </div>



</div>

<script src="./plugins/layui/layui.js"></script>
<script>
    layui.use(['form','element', 'layer'],function(){
        var form = layui.form
            ,element = layui.element
            ,layer = layui.layer
            ,$=layui.jquery;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //监听提交
        form.on('submit(login_submit)', function(data){
            var mobile = data.field.mobile
                ,password = data.field.password;
            $.ajax({
                url:"{{url('admin/login')}}",
                type:'post',
                dataType:'json',
                data:{mobile:mobile,password:password},
                success:function(res){
                    if(res.code == 400){
                        layer.msg(res.data);
                    }else{
                        self.location.href="{{url('admin/index')}}";
                    }
                }
            });
            return false;
        });
    });
</script>
</body>
</html>