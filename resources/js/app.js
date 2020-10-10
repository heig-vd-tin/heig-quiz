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
Vue.config.productionTip = false;

Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)

import router from './router.js'

require('./fontawesome');


/**
 * Vue components
 */
Vue.component('app', require('./App').default);

Vue.prototype.$user = JSON.parse(document.querySelector("meta[name='user']").getAttribute('content'));

/**
 * Global components
 */
import NavBar from './layouts/navbar'
Vue.component('navbar', NavBar)

/**
 * Filters
 */
Vue.filter('capitalize', function (value) {
  if (!value) return ''
  value = value.toString()
  return value.charAt(0).toUpperCase() + value.slice(1)
})

/**
 * Markdown-it
 */
import MarkdownIt from 'markdown-it-vue'
Vue.component('markdown-it-vue', MarkdownIt)

/**
 * Vuex
 */
import store from './store'

/**
 * Portal
 */
import PortalVue from 'portal-vue'
Vue.use(PortalVue)


Vue.mixin({
  methods: {
    error: function (msg) {
      this.$bvModal.msgBoxOk(msg, {
        centered: true,
        title: 'Oops !',
        okVariant: 'danger',
        noCloseOnEsc: true,
        noCloseOnBackdrop: true,
        okTitle: 'Fermer'
      })
      .then(value => {
        this.boxOne = value
      })
    },
  },
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
  store,
  router,
  el: '#app',
  created() {
    this.$store.dispatch('initialize')
  },
  mounted() {

  }
});

import Activity from './controllers/activity'
const activity = new Activity(app);
