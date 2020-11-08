<template>
  <div>
    <h2>Bac à sable</h2>
  </div>
</template>
<script>
import Sub from './sub';
import VueCountdown from '@chenfengyuan/vue-countdown';
import MarkdownItVue from 'markdown-it-vue'
import 'markdown-it-vue/dist/markdown-it-vue.css'
export default {
  components: {
    markdown: MarkdownItVue,
    countdown: VueCountdown,
  },
  methods: {
    dabout() {
      this.alert("Salut");
      this.$activity.start();
    }
  },
  data() {
    return {
      shared: 'Content',
      value: 600 * 1000
    };
  }
};
</script>
