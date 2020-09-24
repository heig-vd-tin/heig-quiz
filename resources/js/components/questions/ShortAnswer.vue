<!-- Short Answer
Allow to give a short answer (one line of text)
-->
<template>
  <div>
    <b-card-text v-html="htmlContent"></b-card-text>
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
    }
  },

  mounted() {
    this.htmlContent = md.render(this.question.content)
    //this.loadAnswer()
  }
}
</script>
