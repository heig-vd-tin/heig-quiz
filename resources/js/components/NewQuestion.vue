<template>
  <div>
    <h2>Nouvelle question</h2>
    <b-form-textarea
      v-model="markdown"
      rows="8"
    ></b-form-textarea>
    {{ question }}
    <question v-model="question"></question>
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
