/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import { BIconTypeStrikethrough, BootstrapVue, BootstrapVueIcons, IconsPlugin } from 'bootstrap-vue'

require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue'
Vue.config.devtools = true

Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)

import router from './router.js'

require('./fontawesome');


/**
 * Vue components
 */
Vue.component('app', require('./layouts/dashboard.vue').default);

Vue.prototype.$user = JSON.parse(document.querySelector("meta[name='user']").getAttribute('content'));

/**
 * Sidebar
 */
import VueSidebarMenu from 'vue-sidebar-menu'
import 'vue-sidebar-menu/dist/vue-sidebar-menu.css'
Vue.use(VueSidebarMenu)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    router,
    el: '#app',
});
