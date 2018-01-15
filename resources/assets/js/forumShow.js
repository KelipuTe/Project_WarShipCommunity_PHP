/**
 * Created by KelipuTe on 2017/11/22.
 */
window.Vue = require('vue');

/* 用户关注讨论的 Vue.js 组件 */
Vue.component('user-discussion-button', require('./components/UserDiscussionButton.vue'));

const forumUserDiscussion = new Vue({
    el: '#user-discussion'
});

/* 用户关注用户的 Vue.js 组件 */
Vue.component('user-user-button', require('./components/UserUserButton.vue'));

const forumUserUser = new Vue({
    el: '#user-user'
});

/* 用户等级的 Vue.js 组件 */
Vue.component('liveness', require('./components/Liveness.vue'));

const forumLiveness = new Vue({
    el: '#liveness'
});