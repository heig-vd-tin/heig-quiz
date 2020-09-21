<template>
<div>
  <h2>Résultats</h2>
  <table style="border: solid black 1px; width: 100%" v-if="loaded">
    <tr>
      <th></th>
      <th v-for="question in questions" :key="question.index">{{question.id}}</th>
    </tr>
    <tr v-for="student in students" :key="student.index">
      <td>{{student.user.name}}</td>
      <th v-for="question in questions" :key="question.index">{{question.id}}</th>
    </tr>
  </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      activity_id: 1,
      students: [],
      questions: [],
      loaded: false
    };
  },
  methods: {
    loadQuizzes() {
      axios.get(`/api/activities/${this.activity_id}`).then((rep) => {
        this.students = rep.data.roster.students;
        this.questions = rep.data.quiz.questions;
        this.loaded = true;
        console.log(this.students[0].user)
      })
    },
  },
  mounted() {
    this.loadQuizzes()
  }
};
</script>
