<template>
  <div class="my-3">
    <b-container class="p-0">
      <h2 class="mb-3">Question</h2>

      <b-row class="question_line">
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

      <b-row class="question_line">
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

      <q-keyword class="question_line" :keywords="question.keywords"></q-keyword>

      <b-row class="question_line">
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

      <b-row class="question_line">
        <b-col sm="2">
          <label>Type : </label>
        </b-col>
        <b-col sm="6">
          <b-form-select v-model="question_type" :options="question_type_options"></b-form-select>
        </b-col>
      </b-row>
      
      <component class="question_line" :is="component_validation" 
                              :validation="question.validation"
                              v-on:onValidationChange="validationChange">
      </component>

      <b-row class="question_line">
        <b-col sm="2">
          <label>Explanation: </label>
        </b-col>
        <b-col sm="6">
          <b-form-input v-model="question.explanation" type="text"></b-form-input>
        </b-col>
        <b-col sm="3">
          <label class="help">Explanation for the right answer</label>
        </b-col>
      </b-row>
    </b-container>

    <h2 v-if="can_preview">Preview</h2>
    <b-container v-if="can_preview" fluid class="question_line">
      <component class="mb-4" :key="component_nonce" :is="component_question" v-bind="question"></component>
    </b-container>

    <h2 v-if="can_preview && question.type != 'multiple-choice'">Answer</h2> 
    <b-container v-if="can_preview && question.type != 'multiple-choice'" fluid class="question_line">
      <b-row class="my-4">
        <b-col sm="2">
          <label>Answer: </label>
        </b-col>
        <b-col sm="6">
          <b-form-textarea v-model="question.answer" rows="3" max-rows="6"></b-form-textarea>
        </b-col>
        <b-col sm="3">
          <label textWrap="true" class="help">You can test an answer here</label>
        </b-col>
      </b-row>

      <b-row class="question_line">
        <b-col sm="3">
          <b-button class="p-2" variant="info" @click="testQuestion">Test question</b-button>
        </b-col>

        <b-col sm="9" v-if="question.type == 'code'">
          <b-form-textarea :key="cpt_info_w" plaintext :value="question.test_info_w" rows="3" max-rows="6"></b-form-textarea>
          <b-form-textarea :key="cpt_info_e" plaintext :value="question.test_info_e" rows="3" max-rows="6"></b-form-textarea>
          <b-form-textarea :key="cpt_info_o" plaintext :value="question.test_info_o" rows="3" max-rows="6"></b-form-textarea>
        </b-col>
        <b-col sm="3" v-else>
          <b-badge class="p-3" v-if="question.answer_ok == null" variant="info">Not tested</b-badge> 
          <b-badge class="p-3" v-else-if="question.answer_ok" variant="success">Good answer</b-badge> 
          <b-badge class="p-3" v-else  variant="danger">Bad answer</b-badge>
        </b-col>
      </b-row>
    </b-container>
    
    <b-button class="mt-3" variant="success" @click="saveQuestion">Save question</b-button>
  
  </div>
</template>
<script>

import Keyword from './EditKeyword'

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
    'q-valid-code': ValidationCode,
    'q-keyword' : Keyword
  },

  data() {
    return {
        cpt_info_w: 1,
        cpt_info_e: 2,
        cpt_info_o: 3,
        component_nonce: 0,
        question:{
          'content': null,
          'name':'',
          'type': null,
          'difficulty': null,
          'validation': null,
          'answer': '',
          'answer_ok': null,
          keywords: []
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

  props:{
    edit_question: Object
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
    complet_code(){
      return `#include <stdio.h>\n int main(){ ${this.question.answer} }` 
    },

    validationChange(val){
      console.log(val) 
      this.question.validation = val
    },

    setComponent() {
      switch (this.question.type) {
        case 'short-answer':
          if( this.question.validation === null ||
              Array.isArray(this.question.validation) ){
            this.question.validation = {}
          }
          this.component_validation = 'q-valid-short'
          this.component_question = 'q-short-answer'
          break;
        case 'code':
          if( this.question.validation === null ||
              Array.isArray(this.question.validation) ){
            this.question.validation = {}
          }
          this.component_validation = 'q-valid-code'
          this.component_question = 'q-code'
          break;
        case 'multiple-choice':
          if( this.question.validation === null ||
              !Array.isArray(this.question.validation) ){
            this.question.validation = []
          }
          this.component_validation = 'q-valid-multiple'
          this.component_question = 'q-multiple-choice'
          break;
      }
      this.component_nonce += 1
    },

    testQuestion: function() {
      let q = this.question
      let vm = this
      let data = Object.assign({}, this.question);
      if( this.question.type === 'code' ){
        data.answer = this.complet_code()
      }

        axios({
            method: 'post',
            url: 'api/questions/testquestion',
            data: data
        })
        .then(function (rep) {
            q.answer_ok = rep.data.answer_ok
            if( rep.data.test_info !== undefined ){
              let txt = rep.data.test_info.split(',"')
              q.test_info_w = txt[0]
              q.test_info_e = txt[1]
              q.test_info_o = txt[2]
              vm.cpt_info_w++;
              vm.cpt_info_e++;
              vm.cpt_info_o++;
            }
        })
        .catch(function (erreur) {
            console.log(erreur)
        });
    },

    previewQuestion: function() {
      this.setComponent()
    },

    saveQuestion: function() {
      let vm = this
      axios({
          method: 'post',
          url: 'api/questions/create',
          data: this.question
      })
      .then(function (reponse) {
          console.log("question saved")
          vm.$emit("questionsaved", vm.question)
      })
      .catch(function (erreur) {
          console.log(erreur)
      });
    },
  }, 

  mounted() {
    if( this.edit_question === undefined || this.edit_question == null )
      console.log("From Question", this.question)
    else{
      console.log("Edit question : ", this.edit_question)
      this.question = this.edit_question
      this.question_type = this.edit_question.type
    }
  }
}
</script>

<style scoped>
  h2{
    color:red
  }

  .help {
    font-size: 0.9em;
    font-style: italic;
  }

  .question_line {
    padding: 7px;
    margin: 0px;
    align-items: center;
    margin-bottom: 10px;
    border-color: rgb(223, 223, 223);
    border-radius: 5px;
    border-width: 2px;
    background-color: rgb(245, 245, 245);
  }

  .card {
    border-color: rgb(223, 223, 223);
    border-radius: 5px;
    border-width: 2px;
    background-color: rgb(245, 245, 245);
  }
</style>