<!-- Short Answer
Allow to give a short answer (one line of text)
-->
<template>
  <div>
    <markdown-it-vue :content="content" />

  </div>
</template>
<script>
export default {
  props: {
    content: String, // Question Markdown content
    gaps: Object, // Gaps type
    answered: Object,
    is_correct: { type: Boolean, default: null },
  },
};
</script>
