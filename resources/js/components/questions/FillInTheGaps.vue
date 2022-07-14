<template>
  <div>
    <b-button v-if="hint"
    variant="success"
    class="btn-circle running"
    v-b-popover.hover.top="hint"
    >
      <b-icon-question-circle-fill />
    </b-button>
    <p>Points : {{points}}</p>
    <b-form inline ref="content">
      <markdown-it-vue @hook:mounted="parseGaps(options.gaps)" :content="content" />
    </b-form>
  </div>
</template>
<script>
import GapComponent from './gap';
import Vue from 'vue';
import MarkdownItVue from 'markdown-it-vue';
import 'markdown-it-vue/dist/markdown-it-vue.css';
let MyMarkdownItVue = Vue.extend(MarkdownItVue);
let Gap = Vue.extend(GapComponent);

export default {
  components: {
    MarkdownItVue,
    Gap
  },
  data() {
    return {
      answer: []
    };
  },
  props: {
    content: String, // Question Markdown content
    options: Object, // Gaps type
    answered: Array,
    validation: Array,
    points: Number,
    hint: String
  },
  methods: {
    /**
     * Replace <em>-</em> or `*-*` in Markdown in the rendered question content.
     * These are gaps.
     */
    parseGaps(gaps) {
      this.$nextTick().then(() => {
        this.$refs.content.querySelectorAll('em').forEach((em, index) => {
          if (em.innerText != '-') return;

          em.replaceWith(
            new Gap({
              propsData: {
                id: index,
                options: gaps[index].map((item, index) => {
                  return { value: item, text: item };
                }),
                answered: this.answered ? this.answered[index] : null,
                validation: this.validation ? this.validation[index] : null,
                is_correct: this.validation ? this.answered[index] == this.validation[index] : null
              }
            })
              .$on('update:gap', ([index, value]) => {
                this.updatedGap(index, value);
              })
              .$mount().$el
          );
        });
      });
    },
    updatedGap(index, value) {
      this.answer[index] = value;
      // Need to return a new array
      this.$emit('update:answered', JSON.parse(JSON.stringify(this.answer)));
    }
  },
  mounted() {}
};
</script>
