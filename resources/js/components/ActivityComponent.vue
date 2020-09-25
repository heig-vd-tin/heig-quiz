<template>
  <component :is="currentComponent"></component>
</template>

<script>
import axios from "axios";

import Quiz from './questions/Idle'
import Quiz from './QuizComponent'
import WaitingRoom from './questions/Waiting'

export default {
  components: [
    Idle,
    Quiz,
    WaitingRoom
  ],
  data() {
    return {
      currentComponent = 'Idle',
      activity_id = null,
      activity = {},
      students = null
    };
  },
  methods: {
    // Start the quiz
    start() {
      this.currentComponent = 'Quiz'
    }
  },
  mounted() {
    window.Echo.join(`activity.${this.activity_id}`)
      .here((users) => {
        this.students = users
        console.log(users);
      })
      .joining((user) => {
        this.students.here++;
        console.log(user.name);
      })
      .leaving((user) => {
        this.students.here--;
        console.log(user.name);
      });

    axios.get(`/api/activities/${this.activity_id}`).then((rep) => {
      this.activity = rep.data
    });
  },
};
</script>
