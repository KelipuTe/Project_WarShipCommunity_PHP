<!--用户等级的Vue.js组件-->
<template>
    <div>
        <span v-text="vliveness"></span>
        <span v-text="vlevel"></span>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component liveness mounted.')
        },
        data:function () {
            return{
                liveness:0,
                level:0
            }
        },
        created:function () {
            this.init();
        },
        methods:{
            init:function () {
                var vm = this;
                var discussion_id = $('#discussion-id').attr('name');
                $.ajax({
                    type:'GET',
                    url:'/account/getLiveness/' + discussion_id,
                    dataType:'json',
                    success:function (data) {
                        vm.liveness = data.liveness;
                        vm.level = data.level;
                    },
                    error:function(jqXHR){
                        console.log("出现错误：" +jqXHR.status);
                    }
                });
            }
        },
        computed:{
            vliveness:function () {
                return '活跃值：'+this.liveness;
            },
            vlevel:function () {
                return '等级：'+this.level;
            }
        }
    }
</script>