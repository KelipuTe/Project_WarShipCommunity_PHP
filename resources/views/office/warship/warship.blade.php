@extends('office.warship.app')
@section('content')
    <div class="col-md-12">
        <table class="table table-bordered table-hover">
            <thead>
            <tr class="success" >
                <th>ID</th>
                <th>Classes</th><th>Name</th><th>No</th>
                <th>Type</th><th>Country</th>
                <th>Picture</th>
                <th>
                    <a href="/office/warship/create" class="btn btn-primary">新增</a>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($warships as $warship)
                <tr class="info">
                    <th scope="row"><div class="warship-table-margin">{{$warship->id}}</div></th>
                    <td><div class="warship-table-margin">{{$warship->classes}}</div></td>
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
@stop