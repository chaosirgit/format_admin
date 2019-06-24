<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{$project_name}}后台管理系统</title>
    <link rel="stylesheet" href="./plugins/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="./plugins/font-awesome/css/font-awesome.min.css" media="all">
    <link rel="stylesheet" href="./src/css/app.css" media="all">
</head>

<body>
<div class="layui-layout layui-layout-admin kit-layout-admin">
    <div class="layui-header">
        <div class="layui-logo">{{$project_name}}后台管理系统</div>
        <div class="layui-logo kit-logo-mobile">CC</div>
        <ul class="layui-nav layui-layout-left kit-nav" kit-one-level>
            {{--<li class="layui-nav-item"><a href="javascript:;">控制台</a></li>--}}
            {{--<li class="layui-nav-item"><a href="javascript:;">商品管理</a></li>--}}
        </ul>
        <ul class="layui-nav layui-layout-right kit-nav">
            {{--<li class="layui-nav-item"><a href="javascript:;" id="pay"><i class="fa fa-gratipay" aria-hidden="true"></i> 捐赠我</a></li>--}}
            {{--<li class="layui-nav-item">--}}
            {{--<a href="javascript:;">--}}
            {{--<img src="http://m.zhengjinfan.cn/images/0.jpg" class="layui-nav-img"> Van--}}
            {{--</a>--}}
            {{--<dl class="layui-nav-child">--}}
            {{--<dd><a href="javascript:;">基本资料</a></dd>--}}
            {{--<dd><a href="javascript:;">安全设置</a></dd>--}}
            {{--</dl>--}}
            {{--</li>--}}
            <li class="layui-nav-item"><a href="{{url('admin/logout')}}"><i class="fa fa-sign-out" aria-hidden="true"></i> 注销</a></li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black kit-side">
        <div class="layui-side-scroll">
            <div class="kit-side-fold"><i class="fa fa-navicon" aria-hidden="true"></i></div>
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree" lay-filter="kitNavbar" kit-navbar>
                @if(in_array('admin_user',$action_arr) || in_array('admin_role',$action_arr))
                    <li class="layui-nav-item">
                        <a class="" href="javascript:;"><i class="fa fa-plug" aria-hidden="true"></i>
                            <span> 管理权限</span></a>
                        <dl class="layui-nav-child">
                            @if(in_array('admin_user',$action_arr))
                                <dd>
                                    <a href="javascript:;" kit-target data-options="{url:'admin_user',icon:'&#xe6c6;',title:'管理员',id:'1'}">
                                        <i class="layui-icon">&#xe6c6;</i><span> 管理员</span></a>
                                </dd>
                            @endif
                            @if(in_array('admin_role',$action_arr))
                                <dd>
                                    <a href="javascript:;" kit-target data-options="{url:'admin_role',icon:'&#xe6c6;',title:'角色管理',id:'2'}">
                                        <i class="layui-icon">&#xe6c6;</i><span> 角色管理</span></a>
                                </dd>
                            @endif
                            @if(in_array('admin_operate',$action_arr))
                                <dd>
                                    <a href="javascript:;" kit-target data-options="{url:'admin_operate',icon:'&#xe6c6;',title:'操作日志',id:'41'}">
                                    <i class="layui-icon">&#xe6c6;</i><span> 操作日志</span></a>
                                </dd>
                            @endif
                        </dl>
                    </li>
                @endif
                    @if(in_array('user',$action_arr) || in_array('user_log',$action_arr))
                <li class="layui-nav-item">
                    <a class="" href="javascript:;"><i class="fa fa-plug" aria-hidden="true"></i>
                        <span> 用户管理</span></a>
                    <dl class="layui-nav-child">
                        @if(in_array("user",$action_arr))
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'user',icon:'&#xe6c6;',title:'用户列表',id:'3'}">
                                <i class="layui-icon">&#xe6c6;</i><span> 用户列表</span></a>
                        </dd>
                        @endif
                        @if(in_array("user_real",$action_arr))
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'user_real',icon:'&#xe6c6;',title:'实名认证',id:'20'}">
                                <i class="layui-icon">&#xe6c6;</i><span> 实名认证</span></a>
                        </dd>
                        @endif
                        @if(in_array('user_log',$action_arr))
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'user_log',icon:'&#xe6c6;',title:'用户日志',id:'4'}">
                                <i class="layui-icon">&#xe6c6;</i><span> 用户日志</span></a>
                        </dd>
                            @endif
                    </dl>
                </li>
                    @endif
                    @if(in_array('setting',$action_arr) || in_array('count',$action_arr))
                <li class="layui-nav-item">
                    <a class="" href="javascript:;"><i class="fa fa-plug" aria-hidden="true"></i>
                        <span> 基础数据管理</span></a>
                    <dl class="layui-nav-child">
                        @if(in_array("setting",$action_arr))
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'setting',icon:'&#xe6c6;',title:'基础设置',id:'5'}">
                                <i class="layui-icon">&#xe6c6;</i><span> 基础设置</span></a>
                        </dd>
                        @endif
                        @if(in_array("count",$action_arr))
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'count',icon:'&#xe6c6;',title:'商城统计',id:'325'}">
                                <i class="layui-icon">&#xe6c6;</i><span> 商城统计</span></a>
                        </dd>
                        @endif
                    </dl>
                </li>
                    @endif
                    @if(in_array('news_category',$action_arr) || in_array('news',$action_arr))
                <li class="layui-nav-item">
                    <a class="" href="javascript:;"><i class="fa fa-plug" aria-hidden="true"></i>
                        <span> 新闻管理</span></a>
                    <dl class="layui-nav-child">
                        @if(in_array('news_category',$action_arr))
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'news_category',icon:'&#xe6c6;',title:'新闻分类',id:'6'}">
                                <i class="layui-icon">&#xe6c6;</i><span> 新闻分类</span></a>
                        </dd>
                        @endif
                        @if(in_array('news',$action_arr))
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'news',icon:'&#xe6c6;',title:'新闻管理',id:'7'}">
                                <i class="layui-icon">&#xe6c6;</i><span> 新闻管理</span></a>
                        </dd>
                            @endif
                    </dl>
                </li>
                    @endif
                    @if(in_array('seller',$action_arr))
                <li class="layui-nav-item">
                    <a class="" href="javascript:;"><i class="fa fa-plug" aria-hidden="true"></i>
                        <span> 商家管理</span></a>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'seller',icon:'&#xe6c6;',title:'商家管理',id:'8'}">
                                <i class="layui-icon">&#xe6c6;</i><span> 商家管理</span></a>
                        </dd>
                    </dl>
                </li>
                    @endif
                    @if(in_array('category',$action_arr) || in_array('brand',$action_arr) || in_array('product_model',$action_arr) || in_array('product',$action_arr))

                <li class="layui-nav-item">
                    <a class="" href="javascript:;"><i class="fa fa-plug" aria-hidden="true"></i>
                        <span> 产品相关</span></a>
                    <dl class="layui-nav-child">
                        @if(in_array('category',$action_arr))
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'category',icon:'&#xe6c6;',title:'产品分类',id:'9'}">
                                <i class="layui-icon">&#xe6c6;</i><span> 产品分类</span></a>
                        </dd>
                        @endif
                            @if(in_array('brand',$action_arr))
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'brand',icon:'&#xe6c6;',title:'品牌管理',id:'10'}">
                                <i class="layui-icon">&#xe6c6;</i><span> 品牌管理</span></a>
                        </dd>
                            @endif
                            @if(in_array('product_model',$action_arr))

                            <dd>
                            <a href="javascript:;" kit-target data-options="{url:'product_model',icon:'&#xe6c6;',title:'模型管理',id:'11'}">
                                <i class="layui-icon">&#xe6c6;</i><span> 模型管理</span></a>
                        </dd>
                                @endif
                            @if(in_array('product_model',$action_arr))

                                <dd>
                                    <a href="javascript:;" kit-target data-options="{url:'spe_name',icon:'&#xe6c6;',title:'规格名',id:'44'}">
                                        <i class="layui-icon">&#xe6c6;</i><span> 规格名</span></a>
                                </dd>
                            @endif
                            @if(in_array('product',$action_arr))

                            <dd>
                            <a href="javascript:;" kit-target data-options="{url:'product',icon:'&#xe6c6;',title:'产品管理',id:'12'}">
                                <i class="layui-icon">&#xe6c6;</i><span> 产品管理</span></a>
                        </dd>
                                @endif
                    </dl>
                </li>
                @endif
                    @if(in_array('order',$action_arr))

                    <li class="layui-nav-item">
                    <a class="" href="javascript:;"><i class="fa fa-plug" aria-hidden="true"></i>
                        <span> 订单管理</span></a>
                    <dl class="layui-nav-child">
                        {{--@if(in_array("admin_index",$admin->role_array))--}}
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'order',icon:'&#xe6c6;',title:'订单管理',id:'13'}">
                                <i class="layui-icon">&#xe6c6;</i><span> 订单管理</span></a>
                        </dd>
                        {{--@endif--}}

                        {{--@if(in_array("admin_log",$admin->role_array))--}}
                        {{--<dd>--}}
                            {{--<a href="javascript:;" kit-target data-options="{url:'user_log',icon:'&#xe6c6;',title:'用户操作日志',id:'9'}">--}}
                                {{--<i class="layui-icon">&#xe6c6;</i><span> 操作日志</span></a>--}}
                        {{--</dd>--}}
                        {{--@endif--}}
                    </dl>
                </li>
                    @endif

                    @if(in_array('advertising',$action_arr))

                <li class="layui-nav-item">
                    <a class="" href="javascript:;"><i class="fa fa-plug" aria-hidden="true"></i>
                        <span> 广告管理</span></a>
                    <dl class="layui-nav-child">
                        @if(in_array('advertising',$action_arr))
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'advertising',icon:'&#xe6c6;',title:'广告管理',id:'14'}">
                                <i class="layui-icon">&#xe6c6;</i><span> 广告管理</span></a>
                        </dd>
                            @endif
                            @if(in_array('ad_look',$action_arr))
                                <dd>
                                    <a href="javascript:;" kit-target data-options="{url:'ad_look',icon:'&#xe6c6;',title:'广告观看',id:'31'}">
                                        <i class="layui-icon">&#xe6c6;</i><span> 广告观看</span></a>
                                </dd>
                            @endif
                    </dl>
                </li>
                        @endif






            </ul>
        </div>
    </div>
    <div class="layui-body" id="container">
        <!-- 内容主体区域 -->
        <div style="padding: 15px;">主体内容加载中,请稍等...</div>
    </div>

    <div class="layui-footer">
        <!-- 底部固定区域 -->
        2018 &copy;
        <a href="https://www.memestech.com.cn">河南模因科技</a>

    </div>
</div>

<script src="./plugins/layui/layui.js"></script>
<script>
    var message;
    layui.config({
        base: 'src/js/'
    }).use(['app', 'message'], function() {
        var app = layui.app,
            $ = layui.jquery,
            layer = layui.layer;
        //将message设置为全局以便子页面调用
        message = layui.message;
        //主入口
        app.set({
            type: 'iframe'
        }).init();
        /*$('#pay').on('click', function() {
            layer.open({
                title: false,
                type: 1,
                content: '<img src="/build/images/pay.png" />',
                area: ['500px', '250px'],
                shadeClose: true
            });
        });*/
    });


</script>
</body>

</html>