@extends('admin._construct')

@section('body')
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">选择统计类型</label>
            <div class="layui-input-inline">
                <select name="type" lay-filter="type" lay-verify="required" lay-search>
                    <option value=""></option>
                    @foreach($types as $type => $name)
                    <option value="{{$type}}">{{$name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">统计开始时间</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="start_date" name="start_date" lay-verify="required" value="{{$start_date}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">统计结束时间</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="end_date" name="end_date" lay-verify="required" value="{{$end_date}}">
            </div>
        </div>


        {{--<div class="layui-form-item" id="input_account" style="display:none;">--}}
            {{--<label class="layui-form-label">用户账号</label>--}}
            {{--<div class="layui-input-inline">--}}
                {{--<input type="text" class="layui-input" name="account_number" placeholder="">--}}
            {{--</div>--}}
        {{--</div>--}}




        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>

    <table id="result"></table>
    <div class="layui-form-item total" style="display: none;">
        <label class="layui-form-label">合计</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" id="total" disabled>
        </div>
    </div>

@endsection

@section('script')
    <script>


        layui.use(['form','laydate','table'],function () {
            var form = layui.form
                ,$ = layui.jquery
                ,laydate = layui.laydate
                ,table = layui.table
                ,index = parent.layer.getFrameIndex(window.name);

            //常规用法
            laydate.render({
                elem: '#start_date'
            });
            //常规用法
            laydate.render({
                elem: '#end_date'
            });
            //监听下拉
            form.on('select(type)',function (data) {
                console.log(data);
                $('#result').hide();
                var type = data.value;
                if (type == 'children'){
                    $('#select_currency').hide();
                    $('#input_account').show();
                }else if(type == 'currency_data'){
                    $('#input_account').hide();
                    $('#select_currency').show();
                }else if(type == 'user_count'){
                    $('#input_account').hide();
                    $('#select_currency').hide();
                }else if(type == 'paybal'){
                    $('#input_account').hide();
                    $('#select_currency').hide();
                }

            });
            //监听提交
            form.on('submit(demo1)', function(data){
                var data = data.field;
                table.render({
                    elem: '#result'
                    ,url: '{{url('admin/count/list')}}?type='+data.type+'&start_date='+data.start_date+'&end_date='+data.end_date //数据接口
                    ,page: true //开启分页
                    ,id:'mobileSearch'
                    ,cols: [[
                            {field: 'info', title: '信息', minWidth:80}
                            ,{field: 'value', title: '值', minWidth:80}
                            ,{field: 'create_time', title: '时间', minWidth:80}
                    //
                        ]]
                    ,done:function (res) {
                        $('.total').show();
                        $('#total').val(res.total)
                    }
                });
                return false;
            });
        });
    </script>

@endsection