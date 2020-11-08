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
    update(state, quizzes) {
      state.count = quizzes.count;
      state.data = quizzes.data;
    }
  },
  actions: {
    fetch({commit}) {
      axios.get("/api/quizzes").then(({data : quizzes}) => {
        commit('update', quizzes)
      }).catch(err => {
        console.log('Unable to fetch quizzes!');
      })
    },
    created(context) {
      context.dispatch('fetch')
    }
  },
}

/**
 * Listen quiz
 * - Refresh when created...
 */
