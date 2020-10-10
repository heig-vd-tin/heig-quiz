import Vue from 'vue'
import Vuex from 'vuex'
import Pusher from "pusher-js"
import Echo from "laravel-echo"
import axios from 'axios';

import roster from './modules/roster'
import activity from './modules/activity'
import user from './modules/user'

Vue.use(Vuex)

/**
 * Use Pusher for all notifications from other clients and from the Backend.
 * Notifications are used to update the hereafter declared Vuex store.
 */
window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    cluster: 'eu',
    forceTLS: true
});

/**
 * Store: client side state storage.
 */
const store = new Vuex.Store({
  strict: process.env.NODE_ENV !== 'production',
  modules: {
    activity,
    roster,
    user
  },
  state: {

  },
  getters: {

  },
  mutations: {
  },
  actions: {
    initialize({commit}) {
      this.dispatch('user/created');
      this.dispatch('roster/created');
      this.dispatch('activity/created');
    }
  }
})

export default store;
