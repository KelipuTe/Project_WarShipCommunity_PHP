@extends('forum.app')
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="">发起新的讨论</a></li>
@stop
@section('content')
    @include('vendor.ueditor.assets')
    <div class="row">
        <div class="col-md-10 col-md-offset-1" role="main">
            <div class="form-group">
                <label for="title" class="col-md-2">讨论标题：</label>
                <input type="text" class="col-md-6" id="title" />
                <label for="published_at" class="col-md-2">发布时间：</label>
                <input type="date" class="col-md-2" id="published_at" />
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                {{--编辑器容器--}}
                <script id="ue-container" type="text/plain"></script>
            </div>
            <div class="form-group">
                <button id="submit" class="btn btn-success form-control">提交</button>
            </div>
            {{--实例化编辑器--}}
            <script type="text/javascript">
                var ue = UE.getEditor('ue-container');
                ue.ready(function() {
                    ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');/* 设置CSRFtoken */
                });
            </script>
            {{--可关闭的警告框--}}
            <div class="master-alert">
                <div id="master-alert-container" class="col-md-4 col-md-offset-4"></div>
            </div>
            <script>
                $(document).ready(function () {
                    /* 提交按钮 */
                    $('#submit').on('click', function () {
                        $('#submit').text('');
                        $('#submit').append('<span class="fa fa-spinner fa-pulse"></span>');
                        $.ajax({
                            type: 'post',
                            url: '/forum/store',
                            data: {
                                'title': $('#title').val(),
                                'published_at': $('#published_at').val(),
                                'body': UE.getEditor('ue-container').getContent()
                            },
                            dataType: 'json',
                            success: function (data) {
                                $('#submit').empty();
                                $('#submit').text('提交');
                                if(data.status == 1){
                                    window.location.href = "/forum/show/"+ data.discussion_id;
                                } else if(data.status == 0){
                                    makeAlertBox('danger',data.message);
                                } else {
                                    makeAlertBox('danger','很抱歉，遇到未知错误，请重试！！！');
                                }
                            },
                            error: function (jqXHR) {
                                $('#submit').empty();
                                $('#submit').text('提交');
                                if(jqXHR.status == 422){
                                    // 遍历被 Laravel Request 拦截后返回的错误提示
                                    $.each(jqXHR.responseJSON.errors,function (index,value) {
                                        makeAlertBox('danger',value);
                                    });
                                }
                            }
                        });
                    });
                });
            </script>
            {{--<form action="/forum/store" method="post">
                Laravel框架为了防止跨域请求攻击（CSRF）而为用户生成的随机令牌，
                post请求如果没有验证token，就出现报错信息，
                在form表单中添加一个隐藏域，携带token参数即可
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="form-group">
                    <label for="title">标题：</label>
                    <input type="text" id="title" name="title" style="width: 80%">
                </div>
                <div class="form-group">
                    <!-- 编辑器容器 -->
                    <script id="container" name="body" type="text/plain"></script>
                </div>
                <div>
                    <input type="submit" value="提交" class="form-control btn btn-primary">
                </div>
            </form>--}}
        </div>
    </div>
@stop