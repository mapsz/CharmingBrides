
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

window.moment = require('moment');

window.VueNotifications = require ('vue-notifications');
window.miniToastr = require ('mini-toastr');

import VueNotifications from 'vue-notifications'
import miniToastr from 'mini-toastr'// https://github.com/se-panfilov/mini-toastr

miniToastr.init()

function toast ({title, message, type, timeout, cb}) {
  return miniToastr[type](message, title, timeout, cb)
}

const options = {
  success: toast,
  error: toast,
  info: toast,
  warn: toast
}

Vue.use(VueNotifications, options);


//Componens
Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('loading', require('./components/partials/loading.vue'));


// Chat
Vue.component('admin-chat', require('./components/partials/adminChat.vue'));
Vue.component('chat', require('./components/Chat.vue'));

//Girls
Vue.component('girls-special-ladies-component', require('./components/girls/specialLadies.vue'));
Vue.component('girl-component', require('./components/girls/girl.vue'));


// Admin panel
Vue.component('men-component', require('./components/admin/men.vue'));
Vue.component('memberships-component', require('./components/admin/memberships.vue'));
Vue.component('create-membership-component', require('./components/admin/createMembership.vue'));
Vue.component('chat-history-component', require('./components/admin/chatHistory.vue'));
//girls
Vue.component('girls-list-component', require('./components/admin/girls/list.vue'));
Vue.component('admin-girls-special-ladies-component', require('./components/admin/girls/specialLadies.vue'));
//Messages
Vue.component('message-send-component', require('./components/letters/send.vue'));

// _admin Panel
Vue.component('admin-panel-list-component', require('./components/_adminPanel/listComponent.vue'));
Vue.component('admin-panel-create-component', require('./components/_adminPanel/createComponent.vue'));
Vue.component('admin-panel-create-wrapper-component', require('./components/_adminPanel/createWrapperComponent.vue'));

const app = new Vue({
    el: '#app'
});