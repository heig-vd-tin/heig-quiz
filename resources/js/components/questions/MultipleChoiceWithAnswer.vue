<template>
  <div>
    <b-button v-if="hint"
    variant="success"
    class="btn-circle running"
    v-b-popover.hover.top="hint"
    >
      <b-icon-question-circle-fill />
    </b-button>
    <b-card-text>
      <markdown-it-vue :content="markdownContent" />
    </b-card-text>
    <b-list-group class="mt-3 mb-4">
      <b-list-group-item v-for="(proposition, index) in propositions" :key="index">
        <b-row class="text-center align-middle">
          <b-col cols="1">
            <b-button
              @click="onClick(index)"
              :pressed.sync="selectedPropositions[index]"
              :class="addClass(index)"
              class="btn-circle"
              variant="outline-secondary"
            >
              {{ index + 1 }}
            </b-button>
          </b-col>
          <b-col class="text-left align-middle">
            <markdown-it-vue :content="proposition" />
          </b-col>
          
          <b-col v-if="choices" class="text-right align-middle">
            <b-progress class="mt-2" :max="students" show-value>
              <b-progress-bar :value="choices[index]" :variant="getVariant(index)">{{ Math.round(choices[index] / students * 100, 1) }}%</b-progress-bar>
            </b-progress>
          </b-col>
        </b-row>
      </b-list-group-item>
    </b-list-group>
  </div>
</template>

<script>
// Match Markdown propositions (## titles)
// https://regex101.com/r/dkfEQY/2
let re = /^##\s*([A-Z]|\d+)\b\s*(.*?)(?=^##|$(?![\s\S]))/gms;

import MarkdownItVue from 'markdown-it-vue'

export default {
  components: {
    MarkdownItVue
  },
  props: {
    content: String,
    options: Object,
    answered: Array,
    validation: Array,
    is_correct: { type: Boolean, default: null },
    choices: Object,
    students: Number,
    points: Number,
    hint: String
  },
  data() {
    return {
      value: 45,
      max: 100,
      selectedPropositions: new Proxy([], {
        get(array, i) {
          return array[i] ? array[i] : false;
        }
      })
    };
  },
  watch: {
    selectedPropositions() {
      let values = [];
      this.selectedPropositions.forEach((value, index) => {
        if (value) values.push(index + 1);
      });
      this.$emit('update:answered', values);
    }
  },
  computed: {
    multipleChoices() {
      if (this.options && this.options.multiple == true) return true;

      if (this.options && this.options.multiple == false) return false;

      return false;
    },
    markdownContent() {
      return this.content.replace(re, '');
    },
    propositions() {
      let matches;
      let output = [];
      while ((matches = re.exec(this.content))) {
        let [_ignore, index, content] = matches;

        let letter;
        if ((letter = /[A-Z]/i.exec(index))) {
          index = letter.input.toUpperCase().charCodeAt(0) - 65;
        }
        output[parseInt(index) - 1] = content;
      }
      return output;
    }
  },
  methods: {
    onClick(index) {
      // If disabled revert change
      // TODO: Prevent default state instead
      if (this.validation != null) {
        this.selectedPropositions[index] = !this.selectedPropositions[index];
        return;
      }

      // Prevent multiple answers if not allowed
      if (!this.multipleChoices && this.selectedPropositions[index]) {
        if (this.selectedPropositions.filter(v => v).length > 1) {
          this.selectedPropositions = Array(this.selectedPropositions.length).fill(false);
          this.selectedPropositions[index] = true;
        }
      }
    },
    addClass(index) {
      if (this.validation == null) return;

      if (this.validation.includes(index + 1)) {
        return 'btn-outline-success btn-good';
      } else if (this.answered != null && this.answered.includes(index + 1)) {
        return 'btn-bad';
      }
    },
    getVariant(index) {
      if (this.validation == null)
        return 'danger';
      if (this.validation.includes(index + 1)) {
        return 'success';
      } else if (this.answered != null && this.answered.includes(index + 1)) {
        return 'danger';
      } else
        return 'danger'
    }
  },
  mounted() {
    this.selectedPropositions = Array(this.propositions.length).fill(false);
    if (this.answered != null) this.answered.forEach(ans => (this.selectedPropositions[ans - 1] = true));
  }
};
</script>
<style scoped>
.time {
  font-size: 2.5rem;
}

.btn-circle:focus {
  outline: none !important;
  -webkit-box-shadow: none !important;
  box-shadow: none !important;
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

.btn-circle:hover {
  background-color: inherit;
  color: inherit;
}

.btn-good {
  border-width: 3px;
}

/* TODO: Use default bootstrap colors instead */
/* TODO: Have a second circle aroung the button to indicate the answer */
.btn-bad {
  color: #fff !important;
  border-width: 3px;
  background-color: #d1504b !important;
  border-color: #c90700 !important;
}
</style>
