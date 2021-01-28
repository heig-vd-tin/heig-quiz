<template>
  <div>
    <b-container fluid>
    <h2 class="my-3">Update Question</h2>

    <q-question class="mb-5" :key="cpt" v-if="question" @questionsaved="onQuestionSave" :edit_question="question" ></q-question>

    <h3>Questions list</h3>
    
    <b-row class="my-4">
      <b-col sm="1">
        <label>Search: </label>
      </b-col>
      <b-col sm="6">
        <b-form-input v-model="filter" type="text"></b-form-input>
      </b-col>
    </b-row>
    
    <b-table v-if="state > 0" 
        striped hover 
        :items="questions" 
        :fields="fields"
        :filter="filter"
        sort-icon-left
        sort-desc
        sort-by="id">
      <template #cell(Actions)="row">
        <b-button size="sm" @click="onEdit(row.item, row.index, $event.target)" class="mr-1">
          Edit question
        </b-button>
      </template>
    </b-table>

    </b-container>
  </div>
</template>
<script>

import Question from './EditQuestion'

export default {
  components: { 
    'q-question': Question,
  },

  data() {
    return {
        cpt: 0,
        filter:null,
        questions: [],
        question: null,
        fields: [
          {key: 'id', sortable: true},
          {key: 'name', sortable: true},
          {key: 'content', sortable: true},
          {key: 'Actions'}
        ],
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
    loadQuestions : function() {
      axios
      .get(`api/questions/all`)
      .then(resp => {
          this.questions = resp.data
          this.state++;
      })
    },

    onQuestionSave: function() {
      this.question = null
    },

    onEdit: function(question, index, button){
      this.question = question
      this.cpt++
    }
  },

  mounted: function() {
      this.loadQuestions()
  }
}
</script>