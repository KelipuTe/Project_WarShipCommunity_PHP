@extends('office.master')
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="">新人报道</a></li>
@stop
@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2" role="main">
            <div class="form-group">
                <label for="title">新人报道标题：</label>
                <input name="title" type="text" class="form-control" id="title" />
            </div>
            <div class="form-group">
                <label for="body">新人报道内容：</label>
                <textarea name="body" class="form-control" id="body" rows="10" style="resize: none"></textarea>
            </div>
            <div class="form-group">
                <button id="submit" class="btn btn-success form-control">提交</button>
            </div>
        </div>
        {{--可关闭的警告框--}}
        <div class="master-alert">
            <div id="master-alert-container" class="col-md-4 col-md-offset-4"></div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            /* 提交按钮 */
            $('#submit').on('click', function () {
                $('#submit').text('');
                $('#submit').append('<span class="fa fa-spinner fa-pulse"></span>');
                $.ajax({
                    type: 'post',
                    url: '/office/store',
                    data: {
                        'title': $('#title').val(),
                        'body': $('#body').val()
                    },
                    dataType: 'json',
                    success: function (data) {
                        $('#submit').empty();
                        $('#submit').text('提交');
                        if(data.status == 1){
                            window.location.href = "/office/show/"+ data.introduction_id;
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
@stop