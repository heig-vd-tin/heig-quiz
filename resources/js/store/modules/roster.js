import axios from 'axios';

export default {
  namespaced: true,

  state() {
    return {
      count: 0,
      selected: null,
      data: {}
    }
  },
  getters: {

  },
  mutations: {
    update(state, rosters) {
      state.count = rosters.count;
      state.data = rosters.data;
    }
  },
  actions: {
    fetch({commit}) {
      axios.get("/api/user/rosters").then(({data : rosters}) => {
        commit('update', rosters)
      }).catch(err => {
        console.log('Unable to fetch rosters!');
      })
    },
    created(context) {
      context.dispatch('fetch')
    }
  },
}
