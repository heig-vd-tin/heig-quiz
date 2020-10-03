<template>
  <b-form inline ref="content">
      <markdown-it-vue @hook:mounted="parseGaps(options.gaps)" :content="content"/>
  </b-form>
</template>
<script>
import GapComponent from "./gap";
import Vue from "vue";
import MarkdownItVue from "markdown-it-vue";
import "markdown-it-vue/dist/markdown-it-vue.css";
let MyMarkdownItVue = Vue.extend(MarkdownItVue);
let Gap = Vue.extend(GapComponent);
export default {
  components: {
    Gap,
  },
  props: {
    content: String, // Question Markdown content
    options: Object, // Gaps type
    answered: { type: Array, default: [] },
    validation: Array,
  },
  methods: {
    /**
     * Replace <em>-</em> or `*-*` in Markdown in the rendered question content.
     * These are gaps.
     */
    parseGaps(gaps) {
      this.$nextTick().then(() => {
        this.$refs.content.querySelectorAll("em").forEach((em, index) => {
          if (em.innerText != "-") return;
          let gap = new Gap({
            propsData: {
              options: gaps[index].map((item, index) => { return {value: item, text: item}}),
              answered: this.answered[index],
              validation: this.validation ? this.validation[index] : null,
              is_correct: this.validation ? this.answered[index] == this.validation[index] : null
            },
          });
          gap.$mount();
          em.replaceWith(gap.$el);
        });
      });
    }
  },
  mounted() {

  }
};
</script>
