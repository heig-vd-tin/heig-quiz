<!-- Fill in the gaps type question.
Inline code that match a gap item in `gaps` are transformed into a
menu with propositions. It the option.gaps is not a list, the gap is transformed
into a textfield.
-->
<template>
</template>

<script>
export default {
  props: {
    content: String, // Markdown content, each gap is defined like `foo`
    gaps: Object, // Value type { foo: ['a', 'b', 'c'] }
    values: Array // Values for each gap
  }
}
</script>
