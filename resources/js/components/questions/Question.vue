<template>
  <div>
    <component class="mb-4" :is="component" v-bind="question"></component>

    <b-alert show variant="info" v-if="explanation">
      <markdown-it-vue mb-2 :content="explanation" />
    </b-alert>
  </div>
</template>
<script>

import Code from './Code';
import FillInTheGaps from './FillInTheGaps';
import MultipleChoice from './MultipleChoice';
import MultipleChoiceWithAnswer from './MultipleChoiceWithAnswer.vue';
import ShortAnswer from './ShortAnswer';

export default {
  data() {
    return {
      component: null,
      question: {
        content: '',
        options: null
      }
    }
  },
  props: {
    content: String,
    explanation: String
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
          return 'q-multiple-choice';
          break;
        case 'multiple-choice-with-answer':
          return 'q-multiple-choice-with-answer';
          break;
      }
      return null;
    }
  },
  mounted() {
    console.log("From Question", this.question)
    this.component = this.getComponent(this.question);
  }
};
</script>
