<template>
  <div>
    <h2 v-if="activity.quiz">
      Quiz n°{{ activity.quiz.id }} {{ activity.quiz.name }}
    </h2>
    <h2>
        Étudiant id : {{ student_id }}
    </h2>

    <b-card class="mb-4 mt-1" v-for="(question, index) in questions" :key="question.id">

      <component class="mb-4" :is="question.component" v-bind="question"></component>
      <component class="mb-4" :is="answers[index].component" v-bind="answers[index]"></component>

      <b-alert show variant="info" v-if="question.explanation">
        <markdown-it-vue mb-2 :content="question.explanation" />
      </b-alert>
    </b-card>


  </div>
</template>

<script>
import Code from './questions/Code';
import FillInTheGaps from './questions/FillInTheGaps';
import MultipleChoice from './questions/MultipleChoice';
import ShortAnswer from './questions/ShortAnswer';

import Answers from './questions/Answer';

import MarkdownItVue from 'markdown-it-vue'
export default {
  components: {
    'q-code': Code,
    'q-fill-in-the-gaps': FillInTheGaps,
    'q-multiple-choice': MultipleChoice,
    'q-short-answer': ShortAnswer,
    'a-answer': Answers,

    MarkdownItVue
  },
  data() {
    return {
      questions: {},
      answers: {},
      activity: {}
    };
  },
  props: {
    activity_id: null,
    student_id: null
  },
  watch: {
    current_roster(roster) {
      let id = roster != null ? roster.id : null;

      // Hide roster column if one is selected
      let field = this.activities.fields.find(field => field.key == 'roster.name');
      field.class = id ? 'd-none' : '';

      this.loadActivities(id);
    }
  },
  methods: {
    getComponent(question) {
      switch (question.type) {
        case 'short-answer':
          return 'q-short-answer';
          break;
        case 'fill-in-the-gaps':
          return 'q-fill-in-the-gaps';
          break;
        case 'multiple-choice':
          return 'q-multiple-choice';
          break;
        case 'code':
          return 'q-code';
          break;
      }
      return null;
    },
    loadActivity() {
      axios.get(`/api/activities/${this.activity_id}`).then(({ data: activity }) => {
        this.activity = activity;
      });
    },
    loadQuestions() {
      axios.get(`/api/activities/${this.activity_id}/questions`).then(({ data: { data, count } }) => {
        this.questions = data;

        this.questions.forEach(question => {
          question.component = this.getComponent(question);
        });
        
      });
    },
    loadAnswers() {
      
      axios.get(`/api/activities/${this.activity_id}/studentResult/${this.student_id}`).then(({ data: answers }) => {
        
        this.answers = answers;
        
        this.answers.forEach(answer => {
          answer.component = 'a-answer';
        });
        
      });
    }
  },
  
  mounted() {
    this.loadActivity();
    this.loadQuestions();
    this.loadAnswers();
  }
};
</script>
<style scoped>
.opacity-50 {
  opacity: 0.5;
}

.pb {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  border-radius: 0;
  height: 0.5rem;
}
</style>
