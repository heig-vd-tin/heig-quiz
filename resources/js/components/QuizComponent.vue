<template>
  <div>
    <div class="progress mb-2">
      <div
        class="progress-bar bg-dark progress-bar"
        role="progressbar"
        :style="'width: '+ percent +'%'"
        :aria-valuenow="percent"
        aria-valuemin="0"
        aria-valuemax="100"
      >{{question_id}}/{{total}}</div>
    </div>
    <b-card v-if="loaded" border-variant="dark" :title="question_id + '. ' + question.title" align="left">
      <component :key="reloadComp" :is="compQuestion"  v-bind="compProp"></component>
      <b-container>
        <b-row class="text-center align-middle">
          <b-col>
            <b-button v-if="question_id > 1" pill variant="outline-secondary" @click="prevQuestion">Précédent</b-button>
          </b-col>
          <b-col cols="5">
            <h2 class="display-3 time" v-bind:class="{'text-danger' : timer < 30}">{{countdown}}</h2>
          </b-col>
          <b-col>
            <b-button v-if="question_id < total" pill variant="outline-secondary" @click="nextQuestion" >Suivant</b-button>
          </b-col>
        </b-row>
      </b-container>
    </b-card>
  </div>
</template>

<script>
import axios from "axios";

import Code from "./questions/Code";
import FillInTheGaps from "./questions/FillInTheGaps";
import MultipleChoice from "./questions/MultipleChoice";
import ShortAnswer from "./questions/ShortAnswer";

export default {
  components: {
    "q-code": Code,
    "q-fill-in-the-gaps": FillInTheGaps,
    "q-multiple-choice": MultipleChoice,
    "q-short-answer": ShortAnswer,
  },
  data() {
    return {
      reloadComp: 0,
      compQuestion: null,
      compProp: {},
      loaded: false,
      total: 1,
      question_id: 4,
      duration: 0,
      question: {},
      values: [],
      activity_id: 1,
      countdown: "- : -",
      timer: 20,
    };
  },
  computed: {
    percent() {
      return (this.question_id / this.total) * 100;
    },
  },
  methods: {
    setComponent(){
      switch(this.question.type) {
        case 'short-answer':
          this.compQuestion = 'q-short-answer'
          break
        case 'fill-in-the-gaps':
          this.compQuestion = 'q-fill-in-the-gaps'
          break
        case 'multiple-choice':
          this.compQuestion = 'q-multiple-choice'
          break
      }
      
      this.compProp = {
        question: this.question,
        values: this.values
      }

      this.reloadComp += 1
    },

    nextQuestion() {
      this.submitAnswer()
      this.question_id += 1
      this.loadQuestion()
    },

    prevQuestion() {
      this.submitAnswer()
      this.question_id -= 1
      this.loadQuestion() 
    },

    loadQuestion() {
      axios
        .get(`/api/activities/${this.activity_id}/questions/${this.question_id-1}`)
        .then((rep) => {
          this.question = rep.data
          this.setComponent()
      });
    },

    submitAnswer() {
      console.log("Anser : " + this.values)
      this.values.splice(0, this.values.length)
    },
    /**
     * Start countdown timer from the received duration
     */
    startTimer(duration) {
      this.timer = duration;
      let timer = setInterval(() => {
        let minutes = parseInt(this.timer / 60, 10);
        let seconds = parseInt(this.timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        this.countdown = minutes + ":" + seconds;

        if (--this.timer <= 0) clearInterval(timer);
      }, 1000);
    },
  },
  mounted() {
    axios.get(`/api/activities/${this.activity_id}`).then((rep) => {
      //let question = rep.data.quiz.questions[this.question_id-1];
      this.questions = rep.data.quiz.questions
      this.duration = rep.data.duration;
      this.activity_id = rep.data.id;
      this.started_at = rep.data.started_at;
      this.total = rep.data.quiz.questions_count;
      this.loaded = true;
      this.setComponent()
      this.startTimer(this.duration);
    });
  },
};
</script>
<style scoped>
.time {
  font-size: 2.5rem;
}
</style>
