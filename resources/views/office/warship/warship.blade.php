@extends('office.warship.app')
@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1" role="main">
            <table class="table table-bordered table-hover text-center">
                <thead>
                <tr class="success" >
                    <th class="text-center">ID</th>
                    <th class="text-center">Classes</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">No</th>
                    <th class="text-center">Type</th>
                    <th class="text-center">Country</th>
                    <th class="text-center">Picture</th>
                    <th class="text-center">
                        <a href="/office/warship/create" class="btn btn-primary">新增</a>
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($warships as $warship)
                    <tr class="info">
                        <th scope="row" class="text-center"><div class="warship-table-margin">{{$warship->id}}</div></th>
                        <td ><div class="warship-table-margin">{{$warship->classes}}</div></td>
                        <td><div class="warship-table-margin">{{$warship->name}}</div></td>
                        <td><div class="warship-table-margin">{{$warship->no}}</div></td>
                        <td><div class="warship-table-margin">{{$warship->type}}</div></td>
                        <td><div class="warship-table-margin">{{$warship->country}}</div></td>
                        <td><img src="{{$warship->pictureUrl}}" class="warship-picture-small"></td>
                        <td>
                            <div class="warship-table-margin">
                                <a href="/office/warship/{{$warship->id}}/edit" class="btn btn-success">修改</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop