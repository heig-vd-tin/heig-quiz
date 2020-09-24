<!-- Short Answer
Allow to give a short answer (one line of text)
-->
<template>
  <div>
    <b-card-text v-html="htmlContent"></b-card-text>
    <b-form-input
          id="input-answer"
          v-model="values[0]"
          required
          placeholder="Enter answer"
        ></b-form-input>
  </div>
</template>

<script>
var md = require("markdown-it")();
var mk = require("@iktakahiro/markdown-it-katex");
md.use(mk);

export default {
  props: {
    values: Array, // Validated answers
    question: null,

    content: String, // Markdown content
    width: Number, // Number of characters allowed
    lines: Number, // Number of lines for the short answer
    value: String, // Given default value (can be blank)
  },

  data() {
    return {
      htmlContent: '',
      answer: null
    }
  },

  mounted() {
    this.htmlContent = md.render(this.question.content)

    if( this.question.answer && this.question.answer.length > 0 ) {
      this.values[0] = this.question.answer[0]
    }
  }
}
</script>
