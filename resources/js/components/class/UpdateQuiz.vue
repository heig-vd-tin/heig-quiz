<template>
  <div>
    <b-container fluid>
    <h2>Update Quiz</h2>

    <b-row class="my-3">
      <b-col sm="3">
        <label> Quiz : </label>
      </b-col>
      <b-col sm="9">
        <b-form-input v-model="quiz_name" @change="onQuizNameChange" list="quiz-list"></b-form-input>

        <datalist id="quiz-list">
          <option v-for="qu in quizzes" :key="qu.id">{{ qu.name }}</option>
        </datalist>
      </b-col>
    </b-row>

    <b-row v-if="state > 0" class="my-3">
      <b-col sm="3">
        <label> Keywords : </label>
      </b-col>
      <b-col sm="9">
        <b-form-input v-model="keyword" list="keyword-list" @change="onKeywordChange"></b-form-input>

        <datalist id="keyword-list">
          <option>all</option>
          <option v-for="k in keywords" :key="k.id">{{ k.name }}</option>
        </datalist>
      </b-col>
    </b-row>

    <b-row v-if="state > 1" class="my-3">
      <b-col sm="3">
        <label> Questions : </label>
      </b-col>
      <b-col sm="9">
        <b-form-input v-model="question_name" list="question-list"></b-form-input>

        <datalist id="question-list">
          <option v-for="q in questions" :key="q.id">{{ q.name }}</option>
        </datalist>

        <div class="my-2" v-if="state > 1 && question_name">{{ questionContent }}</div>

        <b-button v-if="state > 1" class="my-3" variant="success" @click="addQuestion">Add question</b-button>
      </b-col>
    </b-row>

    <b-table v-if="state > 1" striped hover :items="questions_quiz" :fields="fields">
      <template #cell(Actions)="row">
        <b-button size="sm" @click="onDelete(row.item, row.index, $event.target)" class="mr-1">
          Delete question
        </b-button>
      </template>
    </b-table>

    </b-container>
  </div>
</template>
<script>

export default {
  data() {
    return {
        keywords:[],
        keyword: '',
        questions: [],
        question: null,
        question_name: '',
        quiz: null,
        quiz_name: '',
        quizzes: [],
        questions_quiz: [],
        fields: ['name', 'content', 'Actions'],
        state: 0
    }
  },

  props:{
  },

  computed: {
    questionContent: function () {
      return this.getQuestion(this.question_name).content
    }
  },

  methods: {
    getQuestion: function(name) {
        return this.questions.find(o => { return o.name === name })
    },

    getQuiz: function(name) {
        return this.quizzes.find(o => { return o.name === name })
    },

    loadquizzes : function() {
      axios
      .get('api/quizzes')
      .then(resp => {
          this.quizzes = resp.data.data
      })
    },

    loadkeywords : function() {
      axios
      .get('api/keywords')
      .then(resp => {
          this.keywords = resp.data.keywords
      })
    },

    loadQuestions : function() {
      axios
      .get(`api/questions/${this.keyword}`)
      .then(resp => {
          this.questions = resp.data
      })
    },

    loadQuizQuestions : function() {
      axios
      .get(`api/quizzes/${this.quiz.id}/questions`)
      .then(resp => {
          this.questions_quiz = resp.data.questions
      })
    },
    
    onQuizNameChange: function() {
      this.loadkeywords()
      this.quiz = this.getQuiz(this.quiz_name)
      this.state = 1;
    },

    onKeywordChange: function() {
      this.loadQuestions()
      this.loadQuizQuestions()
      this.state = 2
    },

    onDelete: function(question, index, button){
      var data = {
          'quiz_id': this.quiz.id,
          'question_id': question.id
      }

      const vm = this
      axios({
          method: 'delete',
          url: 'api/quizzes/delete',
          data: data
      })
      .then(function (resp) {
        vm.questions_quiz = resp.data.questions
      })
      .catch(function (erreur) {
          console.log(erreur)
      });
    },

    addQuestion: function() {
      this.question = this.getQuestion(this.question_name)
      const vm = this
      axios({
          method: 'post',
          url: 'api/quizzes/add',
          data: {
            'question_id': this.question.id,
            'quiz_id': this.quiz.id
          }
      })
      .then(function (resp) {
          vm.questions_quiz = resp.data.questions
      })
      .catch(function (erreur) {
          console.log(erreur)
      });
    }
  }, 

  mounted: function() {
      this.loadquizzes()
  }
}
</script>