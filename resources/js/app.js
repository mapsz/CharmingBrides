
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

 //Assets
 window.assets = '';

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

//Mixins
// import moreAxios from './components/_adminPanel/lib/moreAxios';
// Vue.mixin(moreAxios);      

window.mMoreAxios       = require ('./components/_adminPanel/lib/moreAxios');
window.mDebug           = require ('./components/_adminPanel/lib/debug');
window.mNotifications   = require ('./components/_adminPanel/lib/notifications');
window.mLoading         = require ('./components/_adminPanel/lib/loading');

//Partials
Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('loading', require('./components/partials/loading.vue'));
Vue.component('errors', require('./components/partials/errors.vue'));


// Chat
Vue.component('admin-chat', require('./components/partials/adminChat.vue'));
Vue.component('chat', require('./components/Chat.vue'));

//Girls
Vue.component('girls-special-ladies-component', require('./components/girls/specialLadies.vue'));
Vue.component('girl-component', require('./components/girls/girl.vue'));

//Letter
Vue.component('letter1-component', require('./components/letters/letter_list.vue'));
Vue.component('letter-component', require('./components/letters/letters.vue'));
Vue.component('letter-admin-component', require('./components/letters/letterAdmin.vue'));
Vue.component('letter-companions-component', require('./components/letters/partials/letterCompanions.vue'));
Vue.component('letter-list-component', require('./components/letters/partials/letterList.vue'));
Vue.component('message-send-component', require('./components/letters/letter_send.vue'));
Vue.component('letter-buy-component', require('./components/letters/letter_buy.vue'));
//Letter admin
Vue.component('long-letter-config-component', require('./components/admin/letters/long_letter_config.vue'));

// Admin panel
Vue.component('men-component', require('./components/admin/men.vue'));
Vue.component('memberships-component', require('./components/admin/memberships.vue'));
Vue.component('create-membership-component', require('./components/admin/createMembership.vue'));
//patials
Vue.component('chat-history-component', require('./components/admin/chatHistory.vue'));
//girls
Vue.component('girls-list-component', require('./components/admin/girls/list.vue'));
Vue.component('admin-girls-special-ladies-component', require('./components/admin/girls/specialLadies.vue'));
Vue.component('admin-girl-confirm-component', require('./components/admin/girls/confirm.vue'));
//Letters
Vue.component('admin-letter-user-component', require('./components/admin/letters/user.vue'));


//men
Vue.component('men-membership', require('./components/men/menMembership.vue'));
Vue.component('men-registration', require('./components/men/menRegistration.vue'));

// _admin Panel
Vue.component('admin-panel-main-component', require('./components/_adminPanel/mainComponent.vue'));
Vue.component('admin-panel-create-wrapper-component', require('./components/_adminPanel/createWrapperComponent.vue'));
//patials
Vue.component('juge-create', require('./components/_adminPanel/partials/create/create.vue'));
Vue.component('juge-input', require('./components/_adminPanel/partials/create/input.vue'));
Vue.component('modal-component', require('./components/_adminPanel/partials/modalComponent.vue'));
Vue.component('list-component', require('./components/_adminPanel/partials/listComponent.vue'));
Vue.component('attach-component', require('./components/_adminPanel/partials/attachComponent.vue'));
Vue.component('file-upload-component', require('./components/_adminPanel/partials/fileUpload.vue'));


const app = new Vue({
    el: '#app'
});