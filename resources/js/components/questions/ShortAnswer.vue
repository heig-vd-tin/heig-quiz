<!-- Short Answer
Allow to give a short answer (one line of text)
-->
<template>
  <div>
    <markdown-it-vue :content="content" />
    <b-form-input
      v-model="answered"
      placeholder="Réponse"
      :size="width"
      :readonly="is_correct != null"
      :state="is_correct"
    ></b-form-input>
  </div>
</template>
<script>
export default {
  props: {
    content: String, // Question Markdown content
    answerType: String, // Input type (text, number)
    width: { type: String, default: "30"},
    is_correct: { type: Boolean, default: null },
    answered: String, // Given default value (can be blank)
  },
};
</script>
