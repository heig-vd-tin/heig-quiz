<!-- Code type question.
A code type is a general question where the last code-block item is
transformed into a textfield. The student have additional buttons:

- Run (To build and execute)
- Build (To only build)
-->
<template>
<div>
  <p>
    <markdown-it-vue mb-2 :content="content" />
    points : {{points}}
  </p>
</div>
</template>

<script>
import MarkdownItVue from 'markdown-it-vue'

export default {
  components: {
    MarkdownItVue
  },
  
    computed: {
    value: {
      get() {
        return this.answered;
      },
      set(value) {
        this.$emit('update:answered', value);
      }
    }
  },

  props: {
    content: String,
    answered: String,
    allowBuild: Boolean,
    allowRun: Boolean
  }
}
</script>
