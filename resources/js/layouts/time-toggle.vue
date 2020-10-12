<template>
  <span class="toggle-date" @click="state++">
    {{ text }}
  </span>
</template>
<script>
import TimeAgo from 'javascript-time-ago';
import fr from 'javascript-time-ago/locale/fr';
TimeAgo.addLocale(fr);
const timeAgo = new TimeAgo('fr-CH');

export default {
  name: 'time-toggle',
  data() {
    return {
      state: null,
      text: ''
    };
  },
  computed: {
    date() {
      return new Date(this.value);
    }
  },
  watch: {
    state(val) {
      switch (this.state % 2) {
        case 0:
          this.text = this.timeAgo(this.date);
          break;
        case 1:
          this.text = this.dateToYMD(this.date);
          break;
      }
    }
  },
  methods: {
    timeAgo(date) {
      return timeAgo.format(Date.parse(date));
    },
    dateToYMD(date) {
      var d = date.getDate();
      var m = date.getMonth() + 1; //Month from 0 to 11
      var y = date.getFullYear();
      var min = date.getMinutes();
      var hours = date.getHours();
      return (
        '' +
        y +
        '-' +
        (m <= 9 ? '0' + m : m) +
        '-' +
        (d <= 9 ? '0' + d : d) +
        ' ' +
        (hours <= 9 ? '0' + hours : hours) +
        ':' +
        (min <= 9 ? '0' + min : min)
      );
    }
  },
  props: {
    value: String
  },
  mounted() {
    this.state = 0;
  }
};
</script>
<style scoped>
  .toggle-date:hover {
    cursor: default;
  }
</style>
