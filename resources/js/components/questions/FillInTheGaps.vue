<!-- Short Answer
Allow to give a short answer (one line of text)
-->
<template>
  <b-form inline ref="content">
      <markdown-it-vue :content="content"/>
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
    answered: Object,
    is_correct: { type: Boolean, default: null },
  },
  mounted() {
    this.$nextTick().then(() => {
      this.$refs.content.querySelectorAll("em").forEach((em, index) => {
        if (em.innerText != "-") return;

        let gap = new Gap({
          propsData: {
            name: "Foobar",
            options: this.options.gaps[index].map((item, index) => { return {value: index, text: item}})
          },
          listeners: {
            updated() {
              console.log("updated thing")
            }
          }
        });
        gap.$mount();
        em.replaceWith(gap.$el);
      });
    });
  }
};
</script>
<!--
Remplacer les `gap` par un composant `<gap :name="gap.name" :options="gap.option"/>`

Soit chercher tous les `codes` dans un truc
-->
