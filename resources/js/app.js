
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

// Vue.config.devtools = false
// Vue.config.debug = false
// Vue.config.silent = true

//Modules
// var VueChatScroll = require('vue-chat-scroll');
// Vue.component('VueChatScroll', require('vue-chat-scroll'));

 // Websockets 
import _echo from 'laravel-echo';
window._echo    = _echo;
import _pusher from 'pusher-js';
window._pusher  = _pusher;

window.d3 = require('d3-timer');

window.moment = require('moment');

window.VueNotifications = require ('vue-notifications');
window.miniToastr = require ('mini-toastr');

import VueNotifications from 'vue-notifications'
import less from 'less'
import miniToastr from 'mini-toastr'// https://github.com/se-panfilov/mini-toastr
window.queryString = require('query-string');
import Paginate from 'vuejs-paginate'
Vue.component('paginate', Paginate)


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

//Statistic
Vue.component('statistic', require('./components/admin/statistic/statistic.vue'));
Vue.component('statistic-agents', require('./components/admin/statistic/partials/agents.vue'));
Vue.component('statistic-memberships', require('./components/admin/statistic/partials/memberships.vue'));
Vue.component('statistic-categories', require('./components/admin/statistic/partials/categories.vue'));

//Mailer
Vue.component('mailer', require('./components/admin/mailer/mailer.vue'));
Vue.component('mailer-list', require('./components/admin/mailer/mailer_list.vue'));


//pages
Vue.component('home', require('./components/pages/home.vue'));
Vue.component('news', require('./components/pages/news.vue'));
Vue.component('about', require('./components/pages/about.vue'));
Vue.component('antiscam', require('./components/pages/anti_scam.vue'));
Vue.component('branches', require('./components/pages/branches.vue'));
Vue.component('our-couples', require('./components/pages/our_couples.vue'));
Vue.component('romantic-tour', require('./components/pages/romantic_tour.vue'));
Vue.component('contacts', require('./components/pages/contacts.vue'));
Vue.component('faq', require('./components/pages/faq.vue'));
Vue.component('holidays', require('./components/pages/holidays.vue'));
Vue.component('how-to-start', require('./components/pages/how_to_start.vue'));
Vue.component('ukraine', require('./components/pages/ukraine.vue'));

//partials
Vue.component('service-blocks', require('./components/partials/serviceblocks.vue'));
Vue.component('carusel', require('./components/partials/carusel.vue'));
Vue.component('card', require('./components/partials/card.vue'));
Vue.component('header-component', require('./components/partials/header.vue'));
Vue.component('coming-soon', require('./components/partials/comingsoon.vue'));

//Partials
Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('loading', require('./components/partials/loading.vue'));
Vue.component('errors', require('./components/partials/errors.vue'));


// Chat
Vue.component('chat', require('./components/chat/chat.vue'));
Vue.component('recent-chat', require('./components/chat/partials/recent.vue'));
Vue.component('online-chat', require('./components/chat/partials/online.vue'));
Vue.component('admin-chat', require('./components/chat/partials/adminChat.vue'));
Vue.component('admin-online-girls', require('./components/chat/partials/adminOnlineGirls.vue'));

//Girls
Vue.component('girls-special-ladies-component', require('./components/girls/specialLadies.vue'));
Vue.component('girl-component', require('./components/girls/girl.vue'));
Vue.component('girl-search', require('./components/girls/girlsearch.vue'));
Vue.component('girls', require('./components/girls/girls.vue'));

//Letter
Vue.component('letter1-component', require('./components/letters/letter_list.vue'));
Vue.component('letter-component', require('./components/letters/letters.vue'));
Vue.component('letter-companion-info', require('./components/letters/partials/letter_companion_info.vue'));
Vue.component('letter-admin-component', require('./components/letters/letterAdmin.vue'));
Vue.component('letter-companions-component', require('./components/letters/partials/letterCompanions.vue'));
Vue.component('letter-list-component', require('./components/letters/partials/letterList.vue'));
Vue.component('message-send-component', require('./components/letters/letter_send.vue'));
Vue.component('letter-buy-component', require('./components/letters/letter_buy.vue'));
//Letter admin
Vue.component('long-letter-config-component', require('./components/admin/letters/long_letter_config.vue'));
Vue.component('admin-letter-user-component', require('./components/admin/letters/user.vue'));
Vue.component('admin-letters-link', require('./components/admin/letters/letters_link.vue'));
Vue.component('admin-letter-user', require('./components/admin/letters/letters_user.vue'));
Vue.component('admin-letter-agent', require('./components/admin/letters/letters_agent.vue'));
//letter history admin
Vue.component('admin-letter-history', require('./components/admin/letters/admin_letter_history.vue'));

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
Vue.component('admin-girl-search', require('./components/admin/girls/search.vue'));
//Men
Vue.component('admin-man-login', require('./components/admin/man/login.vue'));

//men
Vue.component('men-membership', require('./components/men/menMembership.vue'));
Vue.component('men-registration', require('./components/men/menRegistration.vue'));
Vue.component('man-component', require('./components/men/man.vue'));
Vue.component('man-profile', require('./components/men/manProfile.vue'));
Vue.component('man-profile-membership', require('./components/men/manProfileMembership.vue'));
Vue.component('man-edit', require('./components/men/manEdit.vue'));
Vue.component('man-control', require('./components/men/partials/manControl.vue'));
Vue.component('man-to-control', require('./components/men/partials/manToControl.vue'));
Vue.component('man-admin', require('./components/admin/man/adminman.vue'));
Vue.component('favorite-girls', require('./components/men/favoritegirls.vue'));
Vue.component('men-notifications', require('./components/men/notifications.vue'));
Vue.component('men-notysetup', require('./components/men/partials/notysetup.vue'));
Vue.component('men-history', require('./components/men/manHistory.vue'));

//Membership
Vue.component('memberships', require('./components/membership/membership.vue'));


// _admin Panel
Vue.component('admin-panel-main-component', require('./components/_adminPanel/mainComponent.vue'));
Vue.component('admin-panel-create-wrapper-component', require('./components/_adminPanel/createWrapperComponent.vue'));
//patials
Vue.component('juge-create', require('./components/_adminPanel/partials/create/create.vue'));
Vue.component('juge-edit', require('./components/_adminPanel/partials/create/edit.vue'));
Vue.component('juge-input', require('./components/_adminPanel/partials/create/input.vue'));
Vue.component('juge-password-edit-input', require('./components/_adminPanel/partials/create/passwordEditInput.vue'));
Vue.component('juge-errors', require('./components/_adminPanel/partials/create/errors.vue'));
Vue.component('juge-required-text', require('./components/_adminPanel/partials/create/requiredText.vue'));
Vue.component('juge-search', require('./components/_adminPanel/partials/search.vue'));
Vue.component('modal-component', require('./components/_adminPanel/partials/modalComponent.vue'));
Vue.component('list-component', require('./components/_adminPanel/partials/listComponent.vue'));
Vue.component('attach-component', require('./components/_adminPanel/partials/attachComponent.vue'));
Vue.component('file-upload-component', require('./components/_adminPanel/partials/fileUpload.vue'));
Vue.component('pages', require('./components/_adminPanel/partials/pages.vue'));

//  Pay
Vue.component('juge-paypal', require('./components/pay/paypal.vue'));
Vue.component('order', require('./components/pay/order.vue'));

//Signs
Vue.component('matched', require('./components/signs/matched.vue'));
Vue.component('likedyou', require('./components/signs/likedyou.vue'));
Vue.component('admin-signs', require('./components/admin/signs.vue'));

Vue.component('pre-register', require('./components/registration/preregister.vue'));

//admin
Vue.component('admin-email', require('./components/admin/email.vue'));
Vue.component('admin-mailer-man-search', require('./components/admin/mailer/partials/mansearch.vue'));

//Service
Vue.component('service', require('./components/service/service.vue'));
Vue.component('all-services', require('./components/service/all_services.vue'));
Vue.component('service-cart', require('./components/service/partials/cart.vue'));
Vue.component('service-category', require('./components/service/category.vue'));



const app = new Vue({
    el: '#app'
});