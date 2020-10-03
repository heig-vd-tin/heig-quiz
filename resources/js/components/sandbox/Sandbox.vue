<template>
  <div class="container">
    <countdown :time="value">
      <template slot-scope="props">{{ String(props.minutes).padStart(2, '0') }} : {{ String(props.seconds).padStart(2, '0') }}</template>
    </countdown>
    <foobar title="Foo" :value.sync="shared"></foobar>
    <foobar title="Bar" :value.sync="shared"></foobar>
    <button @click="value = 600 * 2000">Reset</button>
  </div>
</template>
<script>
import Sub from "./sub";
import VueCountdown from "@chenfengyuan/vue-countdown";

export default {
  components: {
    foobar: Sub,
    countdown: VueCountdown,
  },
  data() {
    return {
      shared: "Content",
      value: 600 * 1000,
    };
  },
};
</script>
