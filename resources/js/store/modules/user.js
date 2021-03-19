export default {
  namespaced: true,

  state() {
    return {
      name: '',
      role: 'student',
      connected: [] // Connected users
    }
  },
  getters: {
    isTeacher(state) {
      return state.role == 'teacher'
    },
    isStudent(state) {
      return state.role == 'student'
    }
  },
  mutations: {
    update(state, user) {
      state.name = `${user.firstname} ${user.lastname}`;
      state.role = user.affiliation.includes('member') && 
		    user.affiliation.includes('staff') ? 'teacher' : 'student';
    }
  },
  actions: {
    created({commit}) {
      commit('update', this._vm.$user);
    }
  },
}
