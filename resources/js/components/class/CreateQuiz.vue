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
        <b-col sm="9">
          <b-form-checkbox id="is_exam" v-model="quiz.is_exam" name="is_exam">
          Examen ?
        </b-form-checkbox>
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
          'name':'',
          'is_exam': false
        }
    }
  },

  methods: {
    clearInput: function () {
      this.quiz = {
        'name': '',
        'is_exam': false
      };
    },

    createQuiz: function() {
      axios({
          method: 'post',
          url: 'api/quizzes/create',
          data: this.quiz
      })
      .then(function (reponse) {
          console.log("quiz created");
          clearInput();
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