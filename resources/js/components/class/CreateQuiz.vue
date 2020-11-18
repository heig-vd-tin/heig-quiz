<template>
  <div>
    <b-container fluid>
      <h2>Create Quiz</h2>

      <b-row class="my-1">
        <b-col sm="3">
          <label> Name : </label>
        </b-col>
        <b-col sm="9">
          <b-form-input v-model="quiz.name" type="text"></b-form-input>
        </b-col>
      </b-row>

      <b-button class="my-3" variant="success" @click="createQuiz">Create quiz</b-button>
    </b-container>
  </div>
</template>
<script>

export default {
  data() {
    return {
        quiz:{
          'name':''
        }
    }
  },

  methods: {
    createQuiz: function() {
      axios({
          method: 'post',
          url: 'api/quizzes/create',
          data: this.quiz
      })
      .then(function (reponse) {
          console.log("quiz created")
      })
      .catch(function (erreur) {
          console.log(erreur)
      });
    },
  }, 

  mounted: function() {
  }
}
</script>