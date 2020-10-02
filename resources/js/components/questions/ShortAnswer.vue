<!-- Short Answer
Allow to give a short answer (one line of text)
-->
<template>
  <div>
    <p>
      <markdown-it-vue mb-2 :content="content" />
    </p>
    <b-form-group>
    <b-form-input
      v-model="answer.answered"
      placeholder="Réponse"
      :size="width"
      :readonly="answer.is_correct != null"
      :state="answer.is_correct"
    ></b-form-input>
    <b-form-invalid-feedback>
      {{ validation.expected }}
    </b-form-invalid-feedback>
    </b-form-group>
  </div>
</template>
<script>
export default {
  props: {
    content: String, // Question Markdown content
    answerType: String, // Input type (text, number)
    width: { type: String, default: "30"},
    answer: Object,
    validation: Object
  },
};
</script>
