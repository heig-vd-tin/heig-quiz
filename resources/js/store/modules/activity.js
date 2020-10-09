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
      console.log('activity/mutation/update')
      state.count = data.count;
      state.data = data.data;
    }
  },
  actions: {
    fetch({commit}) {
      console.log('activity/action/fetch')
      axios.get('/api/activities').then(({data: data}) => {
        console.log('activity/action/fetched')
        commit('update', data)
      }).catch(err => {
        console.log(err)
      })
    },
    created(context) {
      console.log('activity/action/created')
      context.dispatch('fetch'),
      window.Echo.private('activity').listen("ActivityUpdated", (e) => {
        context.dispatch('fetch');
      })
    }
  },
}


