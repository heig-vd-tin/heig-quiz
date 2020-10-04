<template>
  <div>
    <navbar>
      <template v-slot:title>Bac à sable</template>
      <b-nav-item to="/quiz/activities">
        <b-icon-easel />
        Activités
      </b-nav-item>
      <b-nav-item to="/quiz/quizzes">
        <b-icon-dice-5 />
        Quizzes
      </b-nav-item>
      <b-nav-item to="/quiz/sandbox">
        <b-icon-bucket />
        Bac à sable
      </b-nav-item>
    </navbar>

    <div class="container">
      <countdown :time="value">
        <template slot-scope="props">
          {{ String(props.minutes).padStart(2, '0') }} : {{ String(props.seconds).padStart(2, '0') }}
        </template>
      </countdown>
      <foobar title="Foo" :value.sync="shared"></foobar>
      <foobar title="Bar" :value.sync="shared"></foobar>
      <button @click="value = 600 * 2000">Reset</button>

      <appFooter></appFooter>
    </div>
  </div>
</template>
<script>
import Sub from './sub';
import VueCountdown from '@chenfengyuan/vue-countdown';
import Footer from '../../layouts/footer';

export default {
  components: {
    foobar: Sub,
    appFooter: Footer,
    countdown: VueCountdown
  },
  data() {
    return {
      shared: 'Content',
      value: 600 * 1000
    };
  }
};
</script>
