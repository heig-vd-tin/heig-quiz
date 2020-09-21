<!-- Short Answer
Allow to give a short answer (one line of text)
-->
<template>
</template>

<script>
export default {
  props: {
    content: String, // Markdown content
    width: Number, // Number of characters allowed
    lines: Number, // Number of lines for the short answer
    value: String, // Given default value (can be blank)
  }
}
</script>
