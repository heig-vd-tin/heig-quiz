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

      <b-row class="my-1" v-if="alert == 1">
        <b-col sm="3">
          <label> {{msg}} </label>
        </b-col>
      </b-row>
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
        },
        alert: 0,
        msg: ''
    }
  },

  methods: {
    clearInput() {
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
      .then((reponse) => {

        if(this.quiz.is_exam) {
          this.msg = 'Examen créé'
        } else {
          this.msg = 'Quiz créé'
        }

        this.clearInput();
        this.alert = 1;
      })
      .catch((erreur) => {
        this.alert = 1;
        console.log(erreur)
        this.msg = 'Erreur à la création : ' + erreur;
      });
    },
  }, 

  mounted: function() {
  }
}
</script>