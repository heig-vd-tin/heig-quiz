<template>
  <div>
    <h2>Bac à sable</h2>
        <!-- <countdown :time="31000">
          <template slot-scope="props" >
            <span :class="{ 'text-danger' : props.totalMilliseconds <= 30 * 1000 }">
            {{ String(props.minutes).padStart(2, '0') }}
            :
            {{ String(props.seconds).padStart(2, '0') }}
            </span>
          </template>
        </countdown> -->
        <markdown content="$\sqrt{\frac{2^8}{2^{-2}}}$"></markdown>
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
    countdown: VueCountdown
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
