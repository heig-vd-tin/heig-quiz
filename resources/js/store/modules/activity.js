export default {
  namespaced: true,

  state() {
    return {
      data: [],
      count: 0,
      selected: null
    }
  },
  getters: {

  },
  mutations: {
    update(state, data) {
      state.count = data.count;
      state.data = data.data;
    }
  },
  actions: {
    fetch({commit}) {
      axios.get('/api/activities').then(({data: data}) => {
        commit('update', data)
      }).catch(err => {
        console.log(err)
      })
    },
    created(context) {
      context.dispatch('fetch'),
      window.Echo.private('activity').listen("ActivityUpdated", (e) => {
        context.dispatch('fetch');
      })
    }
  },
}


