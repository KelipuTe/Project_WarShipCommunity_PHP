@extends('office.warship.app')
@section('head')
    @parent
    {{--引入文件--}}
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery.form/4.2.2/jquery.form.js"></script>
@stop
@section('breadCrumb')
    {{--增加面包屑导航条目--}}
    @parent
    <li><a href="">新增舰船条目</a></li>
@stop
@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2" role="main">
            @include('error.errorList')
            {!! Form::open(['url'=>'/office/warship/store']) !!}
            <div class="form-group">
                {!! Form::label('classes','Classes:') !!}
                {!! Form::text('classes',null,['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('name','Name:') !!}
                {!! Form::text('name',null,['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('no','No:') !!}
                {!! Form::text('no',null,['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('type','Type:') !!}
                {!! Form::text('type',null,['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('country','Country:') !!}
                {!! Form::text('country',null,['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('pictureUrl','PictureUrl:') !!}
                {!! Form::text('pictureUrl',null,['class'=>'form-control']) !!}
            </div>
            <div>
                {!! Form::submit('提交',['class'=>'form-control btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}

            <div class="text-center">
                <div id="validation-errors"></div>
                <img src="" width="120" class="" id="warship-picture" alt="">
                {!! Form::open(['url'=>'/office/warship/picture','files'=>true,'id'=>'picture']) !!}
                <div class="text-center">
                    <button type="button" class="btn btn-success picture-button" id="upload-picture">上传新的头像</button>
                </div>
                {!! Form::file('picture',['class'=>'picture','id'=>'image']) !!}
                {!! Form::close() !!}
                <div class="span5">
                    <div id="output" style="display:none"></div>
                </div>
            </div>
            <script>
                $(document).ready(function() {
                    var options = {
                        beforeSubmit: showRequest,
                        success: showResponse,
                        dataType: 'json'
                    };
                    $('#image').on('change', function(){
                        $('#upload-picture').html('正在上传...');
                        $('#picture').ajaxForm(options).submit();
                    });
                });

                function showRequest() {
                    $("#validation-errors").hide().empty();
                    $("#output").css('display','none');
                    return true;
                }

                function showResponse(response)  {
                    if(response.success == false)
                    {
                        var responseErrors = response.errors;
                        $.each(responseErrors, function(index, value)
                        {
                            if (value.length != 0)
                            {
                                $("#validation-errors").append('<div class="alert alert-error"><strong>'+ value +'</strong><div>');
                            }
                        });
                        $("#validation-errors").show();
                    } else {
                        $('#warship-picture').attr('src',response.avatar);
                        $('#upload-picture').html('更换新的头像');
                    }
                }
            </script>
        </div>
    </div>
@stop