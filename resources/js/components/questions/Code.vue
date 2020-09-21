<!-- Code type question.
A code type is a general question where the last code-block item is
transformed into a textfield. The student have additional buttons:

- Run (To build and execute)
- Build (To only build)
-->
<template>
</template>

<script>
export default {
  props: {
    content: String,
    allowBuild: Boolean,
    allowRun: Boolean,
    value: String
  }
}
</script>
