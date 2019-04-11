
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Modules
var VueChatScroll = require('vue-chat-scroll');
Vue.component('VueChatScroll', require('vue-chat-scroll'));

window.d3 = require('d3-timer');



//Componens
Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('loading', require('./components/partials/loading.vue'));


// Chat
Vue.component('admin-chat', require('./components/partials/adminChat.vue'));
Vue.component('chat', require('./components/Chat.vue'));


// Admin panel
Vue.component('men-component', require('./components/admin/men.vue'));
Vue.component('girls-component', require('./components/admin/girls.vue'));
Vue.component('memberships-component', require('./components/admin/memberships.vue'));
Vue.component('create-membership-component', require('./components/admin/createMembership.vue'));


const app = new Vue({
    el: '#app'
});