@extends('office.permission.app')
@section('content')
    <div id="role">
        <role></role>
    </div>
    <template id="template-role">
        <div>
            <div class="panel panel-default">
                <div class="panel-heading">角色：@{{ role.label }}</div>
                <div class="panel-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr class="success">
                            <th>权限ID</th><th>权限名称</th><th>权限说明</th>
                            <th>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary"
                                        data-toggle="modal" data-target="#addPermissionModal">新增权限</button>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="info" v-for="permission in permissions">
                            <th scope="row">@{{ permission.id }}</th>
                            <td>@{{ permission.name }}</td><td>@{{ permission.label }}</td>
                            <td>
                                <button type="button" class="btn btn-primary" :id="['permission-'+permission.id]"
                                        @click="togglePermission(permission.id)"
                                        v-if="hasPermission(permission.id)">授予权限</button>
                                <button type="button" class="btn btn-success" :id="['permission-'+permission.id]"
                                        @click="togglePermission(permission.id)"
                                        v-else="hasPermission(permission.id)">收回权限</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">拥有此角色的用户</div>
                <div class="panel-body">
                    <table class="table table-bordered table-hover text-center">
                        <thead>
                        <tr class="success">
                            <th>用户ID</th><th>用户名</th><th>移除角色</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="info" v-for="user in roleUsers">
                            <td>@{{ user.user_id }}</td><td>@{{ user.username }}</td>
                            <td>
                                <button type="button" class="btn btn-primary"
                                        @click="removeRoleFromUser(user.user_id)">收回权限</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer text-center">
                    <div class="form-inline">
                        <label for="userId" >用户ID：</label>
                        <input type="text" id="userId" class="form-control" v-model="userId" placeholder="二选一即可"/>
                        <label for="username">用户名：</label>
                        <input type="text" id="username" class="form-control" v-model="username" placeholder="二选一即可"/>
                        <button type="button" class="btn btn-primary" @click="giveRoleToUser">赋予权限</button>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="addPermissionModal" tabindex="-1" role="dialog" aria-labelledby="addPermissionModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="addPermissionModalLabel">新建权限</h4>
                        </div>
                        <div class="modal-body">
                            <label for="permission-name">权限名称：</label>
                            <input id="permission-name" class="form-control" v-model="permissionName"/>
                            <label for="permission-label">权限说明：</label>
                            <input id="permission-label" class="form-control" v-model="permissionLabel"/>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" @click="addPermission">新建权限</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <script>
        Vue.component('role',{
            template:"#template-role",
            data:function () {
                return {
                    role: '', rolePermissionIds: '', permissions: '', roleUsers: '',
                    permissionName: '', permissionLabel: '',
                    userId: '', username: ''
                };
            },
            created:function () {
                this.init();
            },
            methods:{
                init:function(){
                    var vm = this;
                    var href = location.href.split('/');
                    var roleId = href[href.length-1];
                    $.ajax({
                        type:'get',
                        url:'/office/permission/getRole',
                        data:{ 'roleId':roleId },
                        dataType:'json',
                        success:function (data) {
                            vm.role = data.role;
                            vm.rolePermissionIds = data.rolePermissionIds;
                            vm.permissions = data.permissions;
                            vm.roleUsers = data.roleUsers;
                        }
                    });
                },
                addPermission:function(){
                    var vm = this;
                    $.ajax({
                        type:'post',
                        url:'/office/permission/addPermission',
                        data:{
                            'name':vm.permissionName, 'label':vm.permissionLabel
                        },
                        dataType:'json',
                        success:function (data) {
                            vm.permissions = data.permissions;
                            $('#addPermissionModal').modal('toggle');
                        }
                    });
                },
                togglePermission:function(permissionId){
                    var vm = this;
                    var href = location.href.split('/');
                    var roleId = href[href.length-1];
                    $.ajax({
                        type:'post',
                        url:'/office/permission/togglePermission',
                        data:{
                            'roleId':roleId,
                            'permissionId':permissionId
                        },
                        dataType:'json',
                        success:function (data) {
                            vm.rolePermissionIds = data.rolePermissionIds;
                            vm.permissions = data.permissions;
                        }
                    });
                },
                giveRoleToUser:function(){
                    var vm = this;
                    var href = location.href.split('/');
                    var roleId = href[href.length-1];
                    $.ajax({
                        type:'post',
                        url:'/office/permission/giveRoleToUser',
                        data:{
                            'roleId': roleId,
                            'userId': vm.userId, 'username': vm.username
                        },
                        dataType:'json',
                        success:function (data) {
                            vm.roleUsers = data.roleUsers;
                        }
                    });
                },
                removeRoleFromUser:function(userId){
                    var vm = this;
                    var href = location.href.split('/');
                    var roleId = href[href.length-1];
                    $.ajax({
                        type:'post',
                        url:'/office/permission/giveRoleToUser',
                        data:{
                            'roleId': roleId,
                            'userId': userId
                        },
                        dataType:'json',
                        success:function (data) {
                            vm.roleUsers = data.roleUsers;
                        }
                    });
                },
                hasPermission:function(id){
                    var vm = this;
                    return ($.inArray(id, vm.rolePermissionIds) == -1);
                }
            }
        });
        new Vue({ el:"#role" });
    </script>
@stop