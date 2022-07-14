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
      points : {{ points }}
    </p>


    <b-button v-if="hint" variant="success" class="btn-circle running" v-b-popover.hover.top="hint">
      <b-icon-question-circle-fill />
    </b-button>
    <b-form-group v-if="$store.state.user.role == 'student'">
      <codemirror ref="myCm"
              v-model="value"
              :value="value" 
              :options="cmOptions"
              >
      </codemirror>

    </b-form-group>

  </div>
</template>

<script>
import MarkdownItVue from 'markdown-it-vue'

// require component
import { codemirror } from 'vue-codemirror'

// require styles
import 'codemirror/lib/codemirror.css'
// language js
import 'codemirror/mode/javascript/javascript.js'
// theme css
import 'codemirror/theme/base16-dark.css'

export default {
  data () {
    return {
      
      cmOptions: {
        // codemirror options
        tabSize: 2,
        mode: 'text/javascript',
        theme: 'base16-dark',
        lineNumbers: true,
        line: true,
      }
    }
  },

  components: {
    MarkdownItVue,
    codemirror,
  },

  computed: {

    codemirror() {
      return this.$refs.myCm.codemirror
    },

    value: {
      get() {
        return this.answered;
      },
      set(value) {
        console.log(value)
        this.$emit('update:answered', value);
      }
    }
  },

  props: {
    id: Number,
    content: String,
    hint: String,
    points: Number,
    width: { type: String, default: '30' },
    answered: String,
    is_correct: { type: [Boolean, null], required: false },
    allowBuild: Boolean,
    allowRun: Boolean
  },


}
</script>

