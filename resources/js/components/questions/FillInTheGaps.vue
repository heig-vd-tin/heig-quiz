<!-- Short Answer
Allow to give a short answer (one line of text)
-->
<template>
  <b-form inline ref="content">
      <markdown-it-vue :content="content" />
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
    gaps: Object, // Gaps type
    answered: Object,
    is_correct: { type: Boolean, default: null },
  },
  mounted() {
    let md = new MyMarkdownItVue({ propsData: { content: this.content } });
    md.$mount();
    this.$nextTick().then(() => {
      //console.log(md.$el);
      //console.log(md.$el.querySelectorAll("code"));
      this.$refs.content.querySelectorAll("code").forEach((code, index) => {
        console.log(code);
        let gap = new Gap({
          propsData: {
            name: "Foobar",
            options: ["a", "b", "c"],
          },
        });
        gap.$mount();
        code.replaceWith(gap.$el);
      });

    });
  },
  updated() {},
};
</script>
<!--
Remplacer les `gap` par un composant `<gap :name="gap.name" :options="gap.option"/>`

Soit chercher tous les `codes` dans un truc
-->
