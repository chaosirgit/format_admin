@extends('admin._construct')
@section('body')
    <form class="layui-form col-lg-5">
        <div class="layui-form-item">
            <label class="layui-form-label">新闻标题</label>
            <div class="layui-input-inline">
                <input type="text" name="title" autocomplete="off" class="layui-input" value="{{ $result->title }}" placeholder="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">选择分类</label>
            <div class="layui-input-inline">
                <select name="category_id" lay-verify="required">
                    <option value=""></option>
                    @foreach($categories as $category)
                    <option value="{{$category->id}}" @if($result->category_id == $category->id) selected @endif>{{$category->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">新闻内容</label>
            <div class="layui-input-block">
                <script id="editor" type="text/plain" style="width:1024px;height:500px;">
                    {!! $result->content !!}
                </script>
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
    <script type="text/javascript" charset="utf-8" src="../admin/plugins/ueditor/1.4.3/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="../admin/plugins/ueditor/1.4.3/ueditor.all.js"> </script>
    <script type="text/javascript" charset="utf-8" src="../admin/plugins/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>
    <script>

        layui.use(['element','form','layer','layedit','upload'], function(){
            var element = layui.element;
            var $ = layui.jquery;
            var form = layui.form;
            var layer = layui.layer;
            var upload = layui.upload;


            var ue = UE.getEditor('editor',{
                'zIndex':1,
            });
            UE.commands['customupload'] = {
                execCommand : function(){
                    var that = this;
                    uploadFiles('#edui148',function(res){

                        that.execCommand('insertHtml', '<img src="'+res.data.src+'">');
                    })
                    // return true;
                },
                queryCommandState:function(){

                }
            };

            //监听提交
            form.on('submit(adminrole_submit)', function(data){
                var data = data.field;
                data.content = ue.getContent();

                $.ajax({
                    url:'{{url('admin/news_add')}}'
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