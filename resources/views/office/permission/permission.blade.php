@extends('office.permission.app')
@section('content')
    <div id="roles">
        <roles></roles>
    </div>
    <template id="template-roles">
        <div>
            <div class="col-md-4" v-for="role in roles">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="#" :href="['/office/permission/role/'+role.id]"> @{{ role.label }} </a>
                    </div>
                    <div class="panel-body">
                        <ul>
                            <li v-for="permission in role.permissions">@{{ permission.name }} : @{{ permission.label }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addRoleModal">
                新建角色
            </button>
            <!-- Modal -->
            <div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="addRoleModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="addRoleModalLabel"> 新建角色 </h4>
                        </div>
                        <div class="modal-body">
                            <label for="role-name">角色名称：</label>
                            <input name="role-name" class="form-control" v-model="roleName" />
                            <label for="role-label">角色标签：</label>
                            <input name="role-label" class="form-control" v-model="roleLabel" />
                        </div>
                        <div class="modal-footer">
                            <div class="form-group">
                                <button id="build-submit" type="button" class="btn btn-primary" @click="addRole">
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
        Vue.component('roles',{
            template:"#template-roles",
            data:function () {
                return {
                    roles: '',
                    roleName:'',
                    roleLabel:''
                };
            },
            created:function () {
                this.init();
            },
            methods:{
                init:function(){
                    var vm = this;
                    $.ajax({
                        type:'GET',
                        url:'/office/permission/getRoles',
                        dataType:'json',
                        success:function (data) {
                            vm.roles = data.roles;
                        },
                        error:function(jqXHR){
                            console.log("出现错误：" +jqXHR.status);
                        }
                    });
                },
                addRole:function(){
                    var vm = this;
                    $.ajax({
                        type:'post',
                        url:'/office/permission/addRole',
                        data:{
                            'name':vm.roleName,
                            'label':vm.roleLabel
                        },
                        dataType:'json',
                        success:function (data) {
                            vm.roles = data.roles;
                            $('#addRoleModal').modal('toggle');
                        },
                        error:function(jqXHR){
                            console.log("出现错误：" +jqXHR.status);
                        }
                    });
                }
            }
        });
        new Vue({
            el:"#roles"
        });
    </script>
@stop