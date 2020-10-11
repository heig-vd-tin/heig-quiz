import Vue from 'vue'
import PortalVue from 'portal-vue'
import App from './App'
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'

import router from './router.js'
import store from './store'

Vue.config.devtools = true
Vue.config.productionTip = false;

// TODO: Remove if not used
//window._ = require('lodash');
//window.$ = window.jQuery = require('jquery');
//window.Vue = require('vue');

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Popper = require('popper.js').default;

Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)

/**
 * Authenticated user (from Laravel)
 */
let meta_user = document.querySelector("meta[name='user']").getAttribute('content')
// if (!meta_user)
//   window.location.replace('/');
Vue.prototype.$user = JSON.parse(meta_user);

/**
 * Filters
 */
Vue.filter('capitalize', function (value) {
  if (!value) return ''
  value = value.toString()
  return value.charAt(0).toUpperCase() + value.slice(1)
})

/**
 * Mixins
 */
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


Vue.use(PortalVue)


const app = new Vue({
  store,
  router,
  el: '#app',
  components: {
    'app': App
  },
  created() {
    this.$store.dispatch('initialize')
  },
  mounted() {

  }
});

import Activity from './controllers/activity'
const activity = new Activity(app);
