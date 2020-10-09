import Vue from 'vue'
import Vuex from 'vuex'
import Pusher from "pusher-js"
import Echo from "laravel-echo"
import axios from 'axios';

import roster from './modules/roster'
import activity from './modules/activity'

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
let store = new Vuex.Store({
  strict: process.env.NODE_ENV !== 'production',
  modules: {
    activity,
    roster
  },
  state: {
    connected: [],  // Connected users
    user: {
      name: 'Anonymous',
      role: 'student'
    }
  },
  getters: {

  },
  mutations: {
    add_connected_user(state, user) {
      state.connected.push(user)
    },
    login(state, user) {
      state.user.name = `${user.firstname} ${user.lastname}`;
      state.user.role = user.affiliation == 'member;staff' ? 'teacher' : 'student';
    }
  },
  actions: {
    initialize({commit}) {
      commit('login', this._vm.$user);
      this.dispatch('roster/created');
      this.dispatch('activity/created');
    }
  }
})

export default store;
