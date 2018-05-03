@extends('office.warship.app')
@section('head')
    @parent
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery.form/4.2.2/jquery.form.js"></script>
@stop
@section('breadCrumb')
    @parent
    <li><a href="">修改舰船条目</a></li>
@stop
@section('content')
    <div class="col-md-6 col-md-offset-1">
        @include('master.errorList')
        {!! Form::model($warship,['method'=>'PATCH','url'=>'/office/warship/'.$warship->id.'/update']) !!}
        <div class="form-group warship-form-line">
            {!! Form::label('classes','Classes:',['class'=>'col-md-3 warship-form-label-line']) !!}
            <div class="col-md-9">
                {!! Form::text('classes',null,['class'=>'form-control']) !!}
            </div>
        </div>
        <div class="form-group warship-form-line">
            {!! Form::label('name','Name:',['class'=>'col-md-3 warship-form-label-line']) !!}
            <div class="col-md-9">
                {!! Form::text('name',null,['class'=>'form-control','id'=>'name']) !!}
            </div>
        </div>
        <div class="form-group warship-form-line">
            {!! Form::label('no','No:',['class'=>'col-md-3 warship-form-label-line']) !!}
            <div class="col-md-9">
                {!! Form::text('no',null,['class'=>'form-control']) !!}
            </div>
        </div>
        <div class="form-group warship-form-line">
            {!! Form::label('type','Type:',['class'=>'col-md-3 warship-form-label-line']) !!}
            <div class="col-md-9">
                {!! Form::text('type',null,['class'=>'form-control']) !!}
            </div>
        </div>
        <div class="form-group warship-form-line">
            {!! Form::label('country','Country:',['class'=>'col-md-3 warship-form-label-line']) !!}
            <div class="col-md-9">
                {!! Form::text('country',null,['class'=>'form-control']) !!}
            </div>
        </div>
        <div class="form-group warship-form-line">
            {!! Form::label('pictureUrl','PictureUrl:',['class'=>'col-md-3 warship-form-label-line']) !!}
            <div class="col-md-9">
                {!! Form::text('pictureUrl',null,['class'=>'form-control','id'=>'picture-url','readonly']) !!}
            </div>
        </div>
        <div class="form-group warship-form-line">
            {!! Form::submit('提交',['class'=>'form-control btn btn-primary','id'=>'warship-submit']) !!}
        </div>
        {!! Form::close() !!}
    </div>
    <div class="col-md-4">
        <div class="text-center warship-form-line">
            <div class="thumbnail warship-form-line">
                <img src="{{$warship->pictureUrl}}" class="warship-picture-meddle" id="warship-picture" alt="300 x 480">
            </div>
            <div id="validation-errors"></div>
            {!! Form::open(['url'=>'/office/warship/picture','files'=>true,'id'=>'picture']) !!}
            <div class="text-center">
                <button type="button" class="btn btn-success picture-button" id="upload-picture">上传新的立绘</button>
            </div>
            {!! Form::file('picture',['class'=>'picture','id'=>'image']) !!}
            {!! Form::close() !!}
            <div class="span5">
                <div id="output" style="display:none"></div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                if($('#name').val() == "" || $('#name').val() == null){console.log(1);
                    $('#warship-submit').attr('disabled',true);
                    $('#picture').attr('disabled',true);
                    $('#image').attr('disabled',true);
                }
                $('#name').blur(function () {

                    if($('#name').val() != "" && $('#name').val() != null){console.log(2);
                        $('#warship-submit').attr('disabled',false);
                        $('#picture').attr('disabled',false);
                        $('#image').attr('disabled',false);
                    } else {console.log(3);
                        $('#warship-submit').attr('disabled',true);
                        $('#picture').attr('disabled',true);
                        $('#image').attr('disabled',true);
                    }
                });

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

            function showResponse(response) {
                if(response.success == false){
                    var responseErrors = response.errors;
                    $.each(responseErrors, function(index, value){
                        if (value.length != 0){
                            $("#validation-errors").append('<div class="alert alert-error"><strong>'+ value +'</strong><div>');
                        }
                    });
                    $("#validation-errors").show();
                } else {
                    $('#warship-picture').attr('src',response.picture);
                    $('#picture-url').val(response.picture_url);
                    $('#upload-picture').html('更换新的立绘');
                }
            }
        </script>
    </div>
@stop