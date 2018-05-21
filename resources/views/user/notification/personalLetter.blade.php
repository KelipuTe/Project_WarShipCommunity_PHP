@extends('user.notification.app')
@section('notification')
    <div class="panel-heading">私信</div>
    <div class="panel-body">
        <div id="personal-letter">
            <personal-letter></personal-letter>
        </div>
    </div>
    <template id="template-personal-letter">
        <div>
            <div v-for="contact in contacts">
                <div class="col-md-4" style="padding: 0 20px">
                    <div class="panel panel-primary" v-if="self_id==contact.to_user_id">
                        <div class="panel-heading">
                            @{{ contact.updated_at }}
                            <button type="button" class="btn btn-success btn-xs pull-right" :class="vbtnclass(contact)"
                                    data-toggle="modal" data-target="#letterModal" @click="getMessage(contact.from_user_id)">
                                <span class="fa fa-paper-plane-o fa-lg"></span>
                                <span v-text="vbtntext(contact)"></span>
                            </button>
                        </div>
                        <!-- Button trigger modal -->
                        <div class="panel-body">
                            <img src="" :src="contact.from_user_info.avatar" class="img-circle img-avatar-small" alt="50x50">
                            @{{ contact.from_user_info.username }}
                        </div>
                    </div>
                    <div class="panel panel-primary" v-if="self_id==contact.from_user_id">
                        <div class="panel-heading">
                            @{{ contact.updated_at }}
                            <button type="button" class="btn btn-xs pull-right" :class="vbtnclass(contact)"
                                    data-toggle="modal" data-target="#letterModal" @click="getMessage(contact.to_user_id)">
                                <span class="fa fa-paper-plane-o fa-lg"></span>
                                <span v-text="vbtntext(contact)"></span>
                            </button>
                        </div>
                        <div class="panel-body">
                            <img src="" :src="contact.to_user_info.avatar" class="img-circle img-avatar-small" alt="50x50">
                            @{{ contact.to_user_info.username }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="letterModal" tabindex="-1" role="dialog" aria-labelledby="letterModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="letterModalLabel">私信记录</h4>
                        </div>
                        <div class="modal-body personal-letter-container">
                            <div v-for="letter in personalLetters" class="row">
                                <div class="panel personal-letter" :class="vletterclass(letter)">
                                    <div class="panel-heading">@{{ letter.body }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group">
                                <div class="col-md-10">
                                    <textarea class="form-control" id="letter-body" rows="1" style="resize: none"></textarea>
                                </div>
                                <div class="col-md-2">
                                    <button id="message-submit" type="button" class="btn btn-primary" @click="sendMessage">
                                        <span class="fa fa-paper-plane-o fa-lg"></span> 发送</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <script>
        Vue.component('personal-letter',{
            template:"#template-personal-letter",
            data:function () {
                return{
                    self_id: '', to_user_id: '',
                    contacts: '',
                    personalLetters: ''
                }
            },
            created:function () {
                this.getContacts();
            },
            methods:{
                getContacts:function(){
                    var vm = this;
                    $.ajax({
                        type:'get',
                        url:'/notification/getContacts',
                        dataType:'json',
                        success:function (data) {
                            vm.self_id = data.self_id;
                            vm.contacts =  data.contacts;
                        }
                    });
                },
                getMessage:function(id){
                    var vm = this;
                    vm.to_user_id = id;
                    $.ajax({
                        type:'get',
                        url:'/notification/getPersonalLetters',
                        data:{'other_user_id':id},
                        dataType:'json',
                        success:function (data) {
                            vm.personalLetters = data.personalLetters.data;
                            vm.personalLetters =  vm.personalLetters.reverse();
                        }
                    });
                },
                sendMessage:function(){
                    var vm = this;
                    $.ajax({
                        type:'post',
                        url:'/notification/personalLetterStore',
                        data:{
                            'to_user_id':vm.to_user_id,
                            'body':$('#letter-body').val()
                        },
                        dataType:'json',
                        success:function (data) {
                            if(data.status == 1) {
                                vm.personalLetters.push(data.personalLetter);
                                $('#letter-body').val('');
                            } else {
                                alert(data.message);
                            }
                        }
                    });
                },
                vletterclass:function(letter){
                    return letter.from_user_id != this.self_id ? 'panel-success pull-left' : 'panel-primary pull-right';
                },
                vbtnclass:function(letter){
                    return (letter.read_at == null || letter.read_at == '') ? 'btn-danger' : 'btn-success'
                },
                vbtntext:function(letter){
                    return (letter.read_at == null || letter.read_at == '') ? '未读' : '详细'
                }
            }
        });
        new Vue({el:"#personal-letter"});
    </script>
@stop