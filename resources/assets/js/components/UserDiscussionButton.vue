<!--forum/show.blade.php 中用户关注讨论的 Vue.js 组件-->
<template>
    <div>
        <button class="btn" :class="vclass" @click="userDiscussionFollow()">
            <span class="glyphicon " :class="vglyphicon"></span>
            <span v-text="vtext"></span>
        </button>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component user-discussion-button Component mounted.')
        },
        data:function () {
            return{
                userDiscussion:0
            }
        },
        created:function () {
            this.userDiscussion = this.hasUserDiscussionFollow();
        },
        methods:{
            hasUserDiscussionFollow:function () {
                var vm = this; // 这里需要指定是 Vue.js 的 this 不是 JavaScript 的 this
                var href = location.href.split('/');
                var discussion_id = href[href.length-1];
                $.ajax({
                    type:'GET',
                    url:'/follow/hasUserDiscussionFollow/' + discussion_id,
                    dataType:'json',
                    success:function (data) {
                        vm.userDiscussion = data.userDiscussion;
                    },
                    error:function(jqXHR){
                        console.log("出现错误：" +jqXHR.status);
                    }
                });
            },
            userDiscussionFollow:function () {
                var vm = this;
                var href = location.href.split('/');
                var discussion_id = href[href.length-1];
                $.ajax({
                    type:'GET',
                    url:'/follow/userDiscussionFollow/' + discussion_id,
                    dataType:'json',
                    success:function (data) {
                        vm.userDiscussion = data.userDiscussion;
                    },
                    error:function(jqXHR){
                        console.log("出现错误：" +jqXHR.status);
                    }
                });
            }
        },
        computed:{
            vclass:function () {
                return this.userDiscussion ? 'btn-success' : 'btn-danger';
            },
            vglyphicon:function () {
                return this.userDiscussion ? 'glyphicon-star' : 'glyphicon-star-empty';
            },
            vtext:function () {
                return this.userDiscussion ? '已关注' : '关注该讨论';
            }
        }
    }
</script>