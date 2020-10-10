<template>
  <div>
    <h2>Bac à sable</h2>
  </div>
</template>
<script>
import Sub from './sub';
import VueCountdown from '@chenfengyuan/vue-countdown';

export default {
  components: {
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
