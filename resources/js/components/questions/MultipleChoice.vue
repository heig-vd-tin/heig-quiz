<!-- Multiple choices
An embedded Markdown content where titles `## K` are remplaced with selectable
propositions.
-->
<template>
</template>

<script>
export default {
  props: {
    content: String, // Markdown content
    multipleAnswers: Boolean, // Allow multiple answers
    values: Array, // Validated answers
  }
}
</script>
