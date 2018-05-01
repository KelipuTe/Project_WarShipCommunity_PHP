@extends('office.permission.app')
@section('content')
    <div id="role">
        <role></role>
    </div>
    <template id="template-role">
        <div>
            <div class="panel panel-default">
                <div class="panel-heading"> @{{ role.label }} </div>
                <div class="panel-body">
                    <table class="table table-bordered table-hover text-center">
                        <thead>
                        <tr class="success">
                            <th class="text-center"> ID </th>
                            <th class="text-center"> name </th>
                            <th class="text-center"> label </th>
                            <th class="text-center">
                                <button type="button" class="btn btn-primary"
                                        data-toggle="modal" data-target="#addPermissionModal">新增权限
                                </button>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="info" v-for="permission in permissions">
                            <th scope="row" class="text-center"> @{{ permission.id }} </th>
                            <td> @{{ permission.name }} </td>
                            <td> @{{ permission.label }} </td>
                            <td>
                                <button type="button" class="btn btn-primary" @click="togglePermission(permission.id)"
                                        v-if="hasPermission(permission.id)">授予权限</button>
                                <button type="button" class="btn btn-success" @click="togglePermission(permission.id)"
                                        v-else="hasPermission(permission.id)">收回权限</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"> 拥有此角色的用户 </div>
                <div class="panel-body">
                    <table class="table table-bordered table-hover text-center">
                        <thead>
                        <tr class="success">
                            <th class="text-center"> userID </th>
                            <th class="text-center"> username </th>
                            <th class="text-center">移除角色</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="info" v-for="user in roleUsers">
                            <td> @{{ user.user_id }} </td>
                            <td> @{{ user.username }} </td>
                            <td>
                                <button id="build-submit" type="button" class="btn btn-primary" @click="removeRoleFromUser(user.user_id)">
                                    <span class="fa fa-cog fa-lg"></span> 创建
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <label for="userId">用户ID：</label>
                    <input name="userId" v-model="userId"/>
                    <label for="username">用户名：</label>
                    <input name="username" v-model="username"/>
                    <button id="build-submit" type="button" class="btn btn-primary" @click="giveRoleToUser">
                        <span class="fa fa-cog fa-lg"></span> 创建
                    </button>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="addPermissionModal" tabindex="-1" role="dialog" aria-labelledby="addPermissionModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="addPermissionModalLabel"> 新建角色 </h4>
                        </div>
                        <div class="modal-body">
                            <label for="permission-name">角色名称：</label>
                            <input name="permission-name" class="form-control" v-model="permissionName"/>
                            <label for="permission-label">角色标签：</label>
                            <input name="permission-label" class="form-control" v-model="permissionLabel"/>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group">
                                <button id="build-submit" type="button" class="btn btn-primary" @click="addPermission">
                                    <span class="fa fa-cog fa-lg"></span> 创建
                                </button>
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
                        type:'GET',
                        url:'/office/permission/getRole',
                        data:{ 'id':roleId },
                        dataType:'json',
                        success:function (data) {
                            vm.role = data.role;
                            vm.rolePermissionIds = data.rolePermissionIds;
                            vm.permissions = data.permissions;
                            vm.roleUsers = data.roleUsers;
                        },
                        error:function(jqXHR){
                            console.log("出现错误：" +jqXHR.status);
                        }
                    });
                },
                addPermission:function(){
                    var vm = this;
                    $.ajax({
                        type:'post',
                        url:'/office/permission/addPermission',
                        data:{
                            'name':vm.permissionName,
                            'label':vm.permissionLabel
                        },
                        dataType:'json',
                        success:function (data) {
                            vm.permissions = data.permissions;
                            $('#addPermissionModal').modal('toggle');
                        },
                        error:function(jqXHR){
                            console.log("出现错误：" +jqXHR.status);
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

                        },
                        error:function(jqXHR){
                            console.log("出现错误：" +jqXHR.status);
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
                            'userId': vm.userId,
                            'username': vm.username
                        },
                        dataType:'json',
                        success:function (data) {

                        },
                        error:function(jqXHR){
                            console.log("出现错误：" +jqXHR.status);
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

                        },
                        error:function(jqXHR){
                            console.log("出现错误：" +jqXHR.status);
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