@extends('office.app')
@section('breadCrumb')
    @parent
    <li><a href="">新人报道</a></li>
@stop
@section('content')
    <div class="col-md-8 col-md-offset-2">
        <div class="form-group">
            <label for="title">新人报道标题：</label>
            <input type="text" id="title" class="form-control" />
        </div>
        <div class="form-group">
            <label for="body">新人报道内容：</label>
            <textarea id="body" class="form-control" rows="10" style="resize: none"></textarea>
        </div>
        <div class="form-group">
            <button id="submit" class="btn btn-success form-control">提交</button>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            // 提交按钮
            $('#submit').on('click', function () {
                $('#submit').text('');
                $('#submit').append('<span class="fa fa-spinner fa-pulse"></span>');
                $.ajax({
                    type: 'post',
                    url: '/office/introductionStore',
                    data: {
                        'title': $('#title').val(), 'body': $('#body').val()
                    },
                    dataType: 'json',
                    success: function (data) {
                        $('#submit').empty();
                        $('#submit').text('提交');
                        if(data.status == 1){
                            window.location.href = "/office/show/"+ data.id;
                        } else {
                            makeAlertBox('danger',data.message);
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