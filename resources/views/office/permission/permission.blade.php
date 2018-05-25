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
                        <a href="#" :href="['/office/permission/role/'+role.id]">角色：@{{ role.label }}</a>
                    </div>
                    <div class="panel-body">
                        <dl>
                            <dt v-for="permission in role.permissions">
                                @{{ permission.label }}
                            </dt>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary col-md-8 col-md-offset-2"
                        data-toggle="modal" data-target="#addRoleModal">新建角色</button>
                <!-- Modal -->
                <div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="addRoleModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="addRoleModalLabel">新建角色</h4>
                            </div>
                            <div class="modal-body">
                                <label for="role-name">角色名称：</label>
                                <input type="text" id="role-name" class="form-control" v-model="roleName" />
                                <label for="role-label">角色标签：</label>
                                <input type="text" id="role-label" class="form-control" v-model="roleLabel" />
                            </div>
                            <div class="modal-footer">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" @click="addRole">新建角色</button>
                                </div>
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
                    roleName: '', roleLabel: ''
                };
            },
            created:function () {
                this.init();
            },
            methods:{
                init:function(){
                    var vm = this;
                    $.ajax({
                        type:'get',
                        url:'/office/permission/getRoles',
                        dataType:'json',
                        success:function (data) {
                            vm.roles = data.roles;
                        }
                    });
                },
                addRole:function(){
                    var vm = this;
                    $.ajax({
                        type:'post',
                        url:'/office/permission/addRole',
                        data:{
                            'name':vm.roleName, 'label':vm.roleLabel
                        },
                        dataType:'json',
                        success:function (data) {
                            vm.roles = data.roles;
                            $('#addRoleModal').modal('toggle');
                        }
                    });
                }
            }
        });
        new Vue({ el:"#roles" });
    </script>
@stop