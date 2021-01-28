<template>
  <div class="my-3">
    <b-container fluid>
    <h2 class="mb-3">Create Question</h2>

    <q-question :key="cpt" @questionsaved="onQuestionSave" :edit_question="question"></q-question>

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
      question: null,
      cpt: 0
    }
  },

  methods: {
    onQuestionSave: function() {
      this.question = null
      this.cpt++
    }
  }
}
</script>