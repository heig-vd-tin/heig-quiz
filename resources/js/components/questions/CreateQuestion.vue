<template>
  <div class="ml-4 mt-4">
    <b-container fluid class="card p-3">
      <h2>Create Question</h2>

      <b-row class="my-4">
        <b-col sm="2">
          <label>Name: </label>
        </b-col>
        <b-col sm="6">
          <b-form-input v-model="question.name" type="text"></b-form-input>
        </b-col>
        <b-col sm="3">
          <label class="help">Name of the question</label>
        </b-col>
      </b-row>

      <b-row class="my-4">
        <b-col sm="2">
          <label>Content: </label>
        </b-col>
        <b-col sm="6">
          <b-form-textarea v-model="question.content" rows="3" max-rows="6"></b-form-textarea>
        </b-col>
        <b-col sm="3">
          <label textWrap="true" class="help">Markdown content<br><br>Multiple choice : ##1 ##2 ...</label>
        </b-col>
      </b-row>

      <b-row class="my-4">
        <b-col sm="2">
          <label>Difficulty: </label>
        </b-col>
        <b-col sm="6">
          <b-form-select v-model="question.difficulty">
            <b-form-select-option :value="null">Please select an option</b-form-select-option>
            <b-form-select-option value="easy">Easy</b-form-select-option>
            <b-form-select-option value="medium">Medium</b-form-select-option>
            <b-form-select-option value="hard">Hard</b-form-select-option>
            <b-form-select-option value="insane">Insane</b-form-select-option>
          </b-form-select>
        </b-col>
      </b-row>

      <b-row class="my-4">
        <b-col sm="2">
          <label>Type : </label>
        </b-col>
        <b-col sm="6">
          <b-form-select v-model="question_type" :options="question_type_options"></b-form-select>
        </b-col>
      </b-row>
      
      <component class="my-4" :is="component_validation" 
                              :validation="question.validation"
                              v-on:onValidationChange="validationChange">
      </component>
    </b-container>

    <b-container v-if="can_preview" fluid class="my-5 p-3 card">
      <h3>Preview</h3>
      <component class="mb-4" :key="component_nonce" :is="component_question" v-bind="question"></component>
    </b-container>

    <b-container v-if="can_preview && question.type != 'multiple-choice'" fluid class="my-5 p-3 card">
      <h3>Answer</h3>      
      <b-row class="my-4">
        <b-col sm="2">
          <label>Answer: </label>
        </b-col>
        <b-col sm="6">
          <b-form-textarea v-model="question.answered" rows="3" max-rows="6"></b-form-textarea>
        </b-col>
        <b-col sm="3">
          <label textWrap="true" class="help">You can test an answer here</label>
        </b-col>
      </b-row>
      <b-button class="my-4" variant="success" @click="testQuestion">Test question</b-button>
    </b-container>
    
    <b-button class="my-4" variant="success" @click="createQuestion">Create question</b-button>
  
  </div>
</template>
<script>

import Code from './Code';
import FillInTheGaps from './FillInTheGaps';
import MultipleChoice from './MultipleChoice';
import ShortAnswer from './ShortAnswer';

import ValidationMultiple from './validation/ValidationMultipleChoice'
import ValidationShort from './validation/ValidationShortAnswer'
import ValidationCode from './validation/ValidationCode'

export default {
  components: { 
    'q-code': Code,
    'q-fill-in-the-gaps': FillInTheGaps,
    'q-multiple-choice': MultipleChoice,
    'q-short-answer': ShortAnswer, 
    'q-valid-multiple': ValidationMultiple,
    'q-valid-short': ValidationShort,
    'q-valid-code': ValidationCode
  },

  data() {
    return {
        component_nonce: 0,
        question:{
          'content': null,
          'name':'',
          'type': null,
          'difficulty': null,
          'validation': null,
          'answer': ''
        },
        component_question: null,
        component_validation: null,
        question_type_options:[
          { value: null, text: 'Please select an option' },
          { value: 'short-answer', text: 'Short-answer' },
          { value: 'multiple-choice', text: 'Multiple choice' },
          { value: 'code', text: 'Code' }
        ]
    }
  },

  watch: {
  },

  computed: {
    can_preview:{
      get: function() {
        return this.question.content !== null && 
               this.question.content !== '' && 
               this.question.type !== null &&
               this.question.validation !== null
      }
    },

    question_type: {
      get: function () {
        return this.question.type
      },
      set: function (newValue) {
         this.question.type = newValue
         this.setComponent()
      }
    }
  },

  methods: {
    validationChange(val){
      console.log(val) 
      this.question.validation = val
    },

    setComponent() {
      switch (this.question.type) {
        case 'short-answer':
          this.question.validation = {}
          this.component_validation = 'q-valid-short'
          this.component_question = 'q-short-answer'
          break;
        case 'code':
          this.question.validation = {}
          this.component_validation = 'q-code'
          this.component_question = 'q-valid-code'
          break;
        case 'multiple-choice':
          this.question.validation = []
          this.component_validation = 'q-valid-multiple'
          this.component_question = 'q-multiple-choice'
          break;
      }
      this.component_nonce += 1
    },

    testQuestion: function() {

    },

    previewQuestion: function() {
      this.setComponent()
    },

    createQuestion: function() {
      axios({
          method: 'post',
          url: 'api/queston/create',
          data: this.question
      })
      .then(function (reponse) {
          console.log("question created")
      })
      .catch(function (erreur) {
          console.log(erreur)
      });
    },
  }, 

  mounted() {
    console.log("From Question", this.question)
  }
}
</script>

<style scoped>
  .help {
    font-size: 0.9em;
    font-style: italic;
  }

  .card {
    border-color: rgb(223, 223, 223);
    border-radius: 5px;
    border-width: 2px;
    background-color: rgb(245, 245, 245);
  }
</style>