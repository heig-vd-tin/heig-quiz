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
          <label>Points: </label>
        </b-col>
        <b-col sm="6">
          <b-form-input v-model="question.points" type="number"></b-form-input>
        </b-col>
        <b-col sm="3">
          <label class="help">Number of points</label>
        </b-col>
      </b-row>

      <b-row class="question_line">
        <b-col sm="2">
          <label>Hint: </label>
        </b-col>
        <b-col sm="6">
          <b-form-input v-model="question.hint" type="text"></b-form-input>
        </b-col>
        <b-col sm="3">
          <label class="help">Aide pour l'étudiant</label>
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

      <component class="question_line" :is="component_options" 
                              :options="question.options"
                              v-on:onOptionsChange="optionsChange">       
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

    <h2 v-if="can_preview && question.type != 'multiple-choice' && question.type != 'multiple-choice-with-answer'">Answer</h2> 
    <b-container v-if="can_preview && question.type != 'multiple-choice' && question.type != 'multiple-choice-with-answer'" fluid class="question_line">
      <b-row class="my-4">
        <b-col sm="2">
          <label>Answer: </label>
        </b-col>

        <b-col sm="6" v-if="question.type != 'code'">
          <b-form-textarea v-model="question.answer" rows="3" max-rows="6"></b-form-textarea>
        </b-col>

        <b-col sm="6" v-else>
          <codemirror ref="myCm"
            rows="3" 
            max-rows="6"
            v-model="question.answer"
            :value="value" 
            :options="cmOptions"
          >
          </codemirror>
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
import MultipleChoiceWithAnswer from './MultipleChoiceWithAnswer.vue';

import ValidationMultiple from './validation/ValidationMultipleChoice'
import ValidationShort from './validation/ValidationShortAnswer'
import ValidationCode from './validation/ValidationCode'
import ValidationFillInTheGaps from './validation/ValidationFillInTheGaps.vue'
import ValidationMultipleAnswer from './validation/ValidationMultipleChoiceWithAnswer.vue';

import OptionsFillInThegaps from './options/OptionsFillInTheGaps.vue';
import OptionsCode from './options/OptionsCode.vue';

// require component
import { codemirror } from 'vue-codemirror'

// require styles
import 'codemirror/lib/codemirror.css'
// language js
import 'codemirror/mode/javascript/javascript.js'
// theme css
import 'codemirror/theme/base16-dark.css'

export default {
  components: { 
    'q-code': Code,
    'q-fill-in-the-gaps': FillInTheGaps,
    'q-multiple-choice': MultipleChoice,
    'q-short-answer': ShortAnswer, 
    'q-multiple-choice-with-answer': MultipleChoiceWithAnswer,

    'q-valid-multiple': ValidationMultiple,
    'q-valid-short': ValidationShort,
    'q-valid-code': ValidationCode,
    'q-valid-fill': ValidationFillInTheGaps,
    'q-valid-multiple-answer': ValidationMultipleAnswer,

    'q-options-fill': OptionsFillInThegaps,
    'q-options-code': OptionsCode,

    'q-keyword' : Keyword,
    codemirror,
  },

  data() {
    return {
      cmOptions: {
        // codemirror options
        tabSize: 2,
        mode: 'text/javascript',
        theme: 'base16-dark',
        lineNumbers: true,
        line: true,
      },
      cpt_info_w: 1,
      cpt_info_e: 2,
      cpt_info_o: 3,
      component_nonce: 0,
      question:{
        'user_id': 0,
        'content': null,
        'name':'',
        'points': 0,
        'type': null,
        'difficulty': null,
        'validation': null,
        'answer': '',
        'answer_ok': null,
        'hint': null,
        'options': null,
        keywords: []
      },
      component_question: null,
      component_validation: null,
      component_options: null,
      question_type_options:[
        { value: null, text: 'Please select an option' },
        { value: 'short-answer', text: 'Short-answer' },
        { value: 'multiple-choice', text: 'Multiple choice' },
        { value: 'code', text: 'Code' },
        { value: 'fill-in-the-gaps', text: 'Fill in the gaps'},
        { value: 'multiple-choice-with-answer', text: 'Multiple choice with answer'}
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

    optionsChange(val){
      console.log(val) 
      this.question.options = val
    },

    setComponent() {

      this.component_options = {}
      this.question.options = {}

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
            this.question.validation = "";
          }
          this.question.options = {}
          this.component_validation = 'q-valid-code'
          this.component_question = 'q-code'
          this.component_options = 'q-options-code'
          break;
        case 'multiple-choice':
          if( this.question.validation === null ||
              !Array.isArray(this.question.validation) ){
            this.question.validation = []
          }
          this.component_validation = 'q-valid-multiple'
          this.component_question = 'q-multiple-choice'
          break;
        case 'fill-in-the-gaps':
          if( this.question.validation === null ||
              !Array.isArray(this.question.validation)){
            this.question.validation = []
          }
          this.question.options = {}
          this.component_validation = 'q-valid-fill'
          this.component_question = 'q-fill-in-the-gaps'
          this.component_options = 'q-options-fill'
          break;
          
        case 'multiple-choice-with-answer':
          this.question.validation = []
          this.component_validation = 'q-valid-multiple-answer'
          this.component_question = 'q-multiple-choice-with-answer'
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