import Vue from 'vue';

export default {
  namespaced: true,

  state() {
    return {
      data: [],
      classrooms: {},
      count: 0,
      current_activity: null
    }
  },
  getters: {
    students(state) {
      if (!state.current_activity) return []
      if (!state.classrooms[state.current_activity_id]) return []

      return Object.values(
        state.classrooms[state.current_activity_id]).filter(user => user.type == 'student')
    },
    activity(state) {
      return state.current_activity;
    }
  },
  mutations: {
    update(state, data) {
      state.count = data.count;
      state.data = data.data;
    },
    updateCurrentActivity(state, activity) {
      state.current_activity = activity;
    },
    userJoin(state, {activity_id, user}) {
      console.log("User", user)
      if (!state.classrooms[activity_id])
        Vue.set(state.classrooms, activity_id, {})
      Vue.set(state.classrooms[activity_id], user.id, user);
    },
    userLeave(state, {activity_id, user}) {
      console.log("Leaving", activity_id, user)
      Vue.delete(state.classrooms[activity_id], user.id);
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
    fetchActivity({commit}) {

    },
    fetchQuestions(context) {
      // if (!context.current_activity) return;
      // let id = context.current_activity.id;
      // axios.get(`/api/activities/${context.state.activity_id}/questions/${this.question_id}`).then(rep => {
      //   this.question = rep.data;
      //   this.answered = this.question.answered;
      //   this.setComponent();
      // });
    },
    created(context) {
      context.dispatch('fetch'),
      window.Echo.private('activity').listen("ActivityUpdated", (e) => {
        context.dispatch('fetch');
      })
    },
    create(context, form) {
      console.log(form);
      return new Promise((resolve, reject) => {
        axios.post('/api/activities/create', form)
        .then(response => {
          context.dispatch('fetch')
          resolve(response)
        })
        .catch(error => reject);
      })
    },
    joinActivity({commit}, activity_id) {
      console.log("JoinActivity ", activity_id)
      axios.get(`/api/activities/${activity_id}`).then(({ data: activity }) => {
        commit('updateCurrentActivity', activity);
        //if (activity.status == 'running')
          //commit('updateCurrentQuestions', this.loadQuestion();
      });

      window.Echo.join(`activity.${activity_id}`)
        .here(users => users.forEach(user => commit('userJoin', {activity_id, user})))
        .joining(user => commit('userJoin', {activity_id, user}))
        .leaving(user => commit('userLeave', {activity_id, user}))
        .listen('ActivityUpdated', e => {
          console.log("Updated")
          loadActivity();
        });
    },
    leaveActivity({commit}, activity_id) {
      window.Echo.leave(`activity.${activity_id}`)
    }
  },
}


