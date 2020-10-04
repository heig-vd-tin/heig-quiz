<template>
  <div>
    <navbar>
      <template v-slot:title>
        Liste des questions
      </template>
      <b-nav-item to="/quiz/activities"><b-icon-easel/> Activités</b-nav-item>
      <b-nav-item to="/quiz/quizzes"><b-icon-dice-5 /> Quizzes</b-nav-item>
      <b-nav-item to="/quiz/questions"><b-icon-dice-3/> Questions</b-nav-item>
      <b-nav-item to="/quiz/sandbox"><b-icon-bucket/> Bac à sable</b-nav-item>
    </navbar>
    <div class="container">
    <h2>Nouvelle question</h2>
    <b-form-textarea
      v-model="markdown"
      rows="8"
    ></b-form-textarea>
    {{ question }}
    <question v-model="question"></question>
    </div>
  </div>
</template>
<script>

import Question from './questions/Question'
import YAML from 'yaml'

export default {
  components: {
    'question': Question
  },
  watch: {
    markdown(value) {
      let matches = value.match(/(?<=^---\n).*?(?=\n---)/igms);
      if (matches) {
        this.frontmatter = YAML.parse(matches[0])
      }
      this.question.content = value.replace(/^---\n.*?---\n/igms, '');

    }
  },
  data() {
    return {
      markdown: '',
      frontmatter: {},
      question: {
        content: '',
        explanation: ''
      }
    }
  },
  methods: {
    parseCode() {

    }
  }
}
</script>
