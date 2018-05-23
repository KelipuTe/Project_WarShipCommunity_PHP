@extends('office.blacklist.app')
@section('content')
    <div class="col-md-10 col-md-offset-1" role="main">
        <div id="blacklist-list">
            <blacklist-list></blacklist-list>
        </div>
        <template id="template-blacklist-list">
            <div>
                <table class="table table-bordered table-hover text-center">
                    <thead>
                    <tr class="success" >
                        <th>用户</th><th>查阅</th><th>处理决定</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="info" v-for="blacklist in blacklists">
                        <td> @{{ blacklist.relatedInfo.target_username }} </td>
                        <td>
                            <button type="button" class="btn btn-primary" @click="locking(blacklist)"
                                    data-toggle="modal" :data-target="setModal(blacklist.type)">查阅</button>
                        </td>
                        <td> @{{ blacklist.admin_opinion }} </td>
                    </tr>
                    </tbody>
                </table>
                <div class="text-center">
                    <ul id="page-list" class="pagination"></ul>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="discussionModal" tabindex="-1" role="dialog" aria-labelledby="discussionModalLabel">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="discussionModalLabel">讨论</h4>
                            </div>
                            <div class="modal-body">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <div class="media">
                                            <div class="media-left">
                                                <img class="media-object img-circle img-avatar-small" src="" :src="target_relatedInfo.avatar">
                                            </div>
                                            <div class="media-body">
                                                <h4 class="media-heading">@{{target_relatedInfo.username}}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body" v-html="target.body"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="commentModalLabel">回复</h4>
                            </div>
                            <div class="modal-body">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <div class="media">
                                            <div class="media-left">
                                                <img class="media-object img-circle img-avatar-small" src="" :src="target_relatedInfo.avatar">
                                            </div>
                                            <div class="media-body">
                                                <h4 class="media-heading">@{{target_relatedInfo.username}}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body" v-html="target.body"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <script>
            Vue.component('blacklist-list',{
                template:"#template-blacklist-list",
                data:function () {
                    return {
                        blacklists:'',
                        target: '' ,target_relatedInfo: ''
                    };
                },
                created:function () {
                    this.getDoneBlacklists();
                },
                methods:{
                    getDoneBlacklists:function(){
                        var vm = this;
                        var url = '/office/blacklist/getDoneBlacklists';
                        var href = location.href;
                        if( href.indexOf('?') != -1 ){
                            href = location.href.split('=');
                            url = '/office/blacklist/getDoneBlacklists?page='+ href[href.length-1];
                        }
                        $.ajax({
                            type:'GET',
                            url:url,
                            dataType:'json',
                            success:function (data) {
                                vm.blacklists = data.blacklists.data;
                                pageList(data.blacklists,'http://localhost/office/blacklist/notice');
                            }
                        });
                    },
                    locking:function(blacklist){
                        var vm = this;
                        $.ajax({
                            type:'post',
                            url:'/office/blacklist/locking',
                            data:{'id': blacklist.id},
                            dataType:'json',
                            success:function (data) {
                                vm.blacklist = data.blacklist;
                                vm.blacklist_relatedInfo = vm.blacklist.relatedInfo;
                                if(data.target != null) {
                                    vm.target = data.target;
                                    vm.target_relatedInfo = vm.target.relatedInfo;
                                }
                            }
                        });
                    },
                    setModal:function(type){
                        var data_target = '';
                        switch(type){
                            case 'discussion':
                                data_target = '#discussionModal';break;
                            case 'comment':
                                data_target = '#commentModal';break;
                            default:
                                break
                        }
                        return data_target;
                    }
                }
            });
            new Vue({ el:"#blacklist-list" });
        </script>
    </div>
@stop