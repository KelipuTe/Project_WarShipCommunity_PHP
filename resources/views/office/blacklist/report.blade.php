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
                    <tr class="success">
                        <th>ID</th><th>类型</th><th>目标 ID</th>
                        <th>举报用户 ID</th><th>举报时间</th>
                        <th>查阅</th><th>处理状态</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="info" v-for="blacklist in blacklists">
                        <th scope="row"> @{{ blacklist.id }} </th>
                        <td> @{{ blacklist.type }} </td>
                        <td> @{{ blacklist.target }} </td>
                        <td> @{{ blacklist.user_id }} </td>
                        <td> @{{ blacklist.created_at }} </td>
                        <td>
                            <button type="button" class="btn btn-primary" @click="locking(blacklist)"
                                    data-toggle="modal" :data-target="setModal(blacklist.type)">查阅</button>
                        </td>
                        <td> @{{ blacklist.admin_opinion }}</td>
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
                                <div class="panel panel-danger">
                                    <div class="panel-heading">
                                        <h4 class="media-heading">举报理由 by @{{ blacklist_relatedInfo.username }}</h4>
                                    </div>
                                    <div class="panel-body">@{{ blacklist.explain }}</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" @click="handel('agree')">同意</button>
                                <button type="button" class="btn btn-primary" @click="handel('disagree')">不同意</button>
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
                                <div class="panel panel-danger">
                                    <div class="panel-heading">
                                        <h4 class="media-heading">举报理由 by @{{ blacklist_relatedInfo.username }}</h4>
                                    </div>
                                    <div class="panel-body">@{{ blacklist.explain }}</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" @click="handel('agree')">同意</button>
                                <button type="button" class="btn btn-primary" @click="handel('disagree')">不同意</button>
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
                        blacklists:'', blacklist: '', blacklist_relatedInfo: '',
                        target: '' ,target_relatedInfo: ''
                    };
                },
                created:function () {
                    this.getBlacklists();
                },
                methods:{
                    getBlacklists:function(){
                        var vm = this;
                        var url = '/office/blacklist/getBlacklists';
                        var href = location.href;
                        if( href.indexOf('?') != -1 ){
                            href = location.href.split('=');
                            url = '/office/blacklist/getBlacklists?page='+ href[href.length-1];
                        }
                        $.ajax({
                            type:'get',
                            url:url,
                            dataType:'json',
                            success:function (data) {
                                vm.blacklists = data.blacklists.data;
                                pageList(data.blacklists,'http://localhost/office/blacklist/archives');
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
                    handel:function(opinion){
                        var vm = this;
                        $.ajax({
                            type:'post',
                            url:'/office/blacklist/handel',
                            data:{
                                'id': vm.blacklist.id,
                                'opinion' : opinion
                            },
                            dataType:'json',
                            success:function (data) {
                                if(data.status == 1) {
                                    $('#page-list').empty();
                                    vm.getBlacklists();
                                    $('#commentModal').modal('hide');
                                    $('#discussionModal').modal('hide');
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