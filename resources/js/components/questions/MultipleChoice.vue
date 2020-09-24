<!-- Multiple choices
An embedded Markdown content where titles `## K` are remplaced with selectable
propositions.
-->
<template>
  <div>
    <b-card-text v-html="htmlContent"></b-card-text>

    <b-list-group class="mt-3 mb-4">
      <b-list-group-item v-for="(proposition, index) in propositions" :key="index">
        <b-row class="text-center align-middle">
          <b-col cols="1">
            <b-button
            :pressed.sync="selected[index]"
              variant="outline-secondary"
              class="btn-circle"
              @click="onClick(index, proposition, $event)"
            >{{index+1}}</b-button>
          </b-col>
          <b-col class="text-left align-middle" v-html="proposition"></b-col>
        </b-row>
      </b-list-group-item>
    </b-list-group>
  </div>
</template>

<script>
var md = require("markdown-it")();
var mk = require("@iktakahiro/markdown-it-katex");
md.use(mk);

export default {
  props: {
    values: Array, // Validated answers
    question: null
  },
  data() {
    return {
      selected: [],
      propositions: [],
      questionNumber: 1,
      countdown: "- : -",
      timer: 20,
      htmlContent: '',
    }
  },
  methods: {
    onClick(index, proposition, $event){
      this.values.splice(0, this.values.length)
      var i
      for(i=0; i<this.selected.length; i++){
        if(i != index && !this.question.multipleAnswers) {
          this.selected[i] = false
        }

        if(this.selected[i]){
          this.values.push(i)
        }
      }
    },
    /**
     * Extract the propositions in a multiple choice Markdown content.
     */
    extractPropositions(markdown) {
      let state = ''
      let ignore = []
      let propositions = {}
      let proposition = -1
      let level = 0
      let tokens = md.parse(markdown)
      // Parse tokens
      tokens.forEach((token, index) => {
        // Looks for propositions
        if (token.type == 'heading_open' && token.tag == 'h2') {
          state = 'check-inline'
          ignore.push(index)
          return
        }

        // Capture proposition number
        if (state == 'check-inline' && token.type == 'inline') {
          if (/^[A-Z]$/.test(token.content)) {
            ignore.push(index)
            state = 'capture-content'
            proposition++
            propositions[proposition] = []
          } else {
            ignore.pop()
            state = 'looking-for-proposition'
          }
          return
        }

        // Capture proposition content
        if (state == 'capture-content') {
          ignore.push(index)
          propositions[proposition].push(token)

          if (token.type == 'paragraph-open') {
            level++
          }

          if (token.type == 'paragraph-close') {
            level--
            if (level == 0)
              state == 'looking-for-proposition'
              return
          }
        }
      })

      function* output() {
        for (const [index, token] of tokens.entries()) {
          if (ignore.includes(index)) continue;
          yield token;
        }
      };

      this.htmlContent = md.renderer.render(Array.from(output()), md.options)
      for (const [key, value] of Object.entries(propositions)) {
          this.propositions[key] = md.renderer.render(value, md.options)
          this.selected[key] = false
      }

    }
  },
  mounted() {
    console.log('multiple-choice')
    this.extractPropositions(this.question.content);
  }
};
</script>
<style scoped>
.time {
  font-size: 2.5rem;
}

.btn-circle {
  width: 38px;
  height: 38px;
  text-align: center;
  padding: 0;
  border-radius: 50%;
  font-size: 1.3rem;
  font-weight: 500;
}
</style>
