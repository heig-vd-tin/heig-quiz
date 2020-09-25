<template>
  <div>
    <!-- Activity Idle -->
    <b-jumbotron v-if="state=='idle'" header="Fermé">
      <template v-slot:lead>
        L'activité n'est pas disponible, revenez plus tard.
      </template>
    </b-jumbotron>
    <!-- Activity Opened -->
    <b-jumbotron v-if="state=='opened'" header="Salle d'attente">
      <template v-slot:lead>
        <b-spinner label="Spinning"></b-spinner>
        Attente de {{students.here}} / {{students.total}} étudiants...
      </template>
    </b-jumbotron>
    <!-- Quiz started -->
    <div v-else-if="state=='started'" class="progress mb-2">
      <div
        class="progress-bar bg-dark progress-bar"
        role="progressbar"
        :style="'width: '+ percent +'%'"
        :aria-valuenow="percent"
        aria-valuemin="0"
        aria-valuemax="100"
      >{{question_id}}/{{total}}</div>
    </div>
    <b-card
      v-if="loaded"
      border-variant="dark"
      :title="question_id + '. ' + question.name"
      align="left"
    >
      <component class="mb-4" :key="reloadComp" :is="compQuestion" v-bind="compProp"></component>
      <b-container>
        <b-row class="text-center align-middle">
          <b-col>
            <b-button
              v-if="question_id > 1"
              pill
              variant="outline-secondary"
              @click="prevQuestion"
            >Précédent</b-button>
          </b-col>
          <b-col cols="5">
            <h2 class="display-3 time" v-bind:class="{'text-danger' : timer < 30}">{{countdown}}</h2>
          </b-col>
          <b-col>
            <b-button
              v-if="question_id < total"
              pill
              variant="outline-secondary"
              @click="nextQuestion"
            >Suivant</b-button>
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
      question_id: 1,
      duration: 0,
      question: {},
      values: [],
      activity_id: 1,
      countdown: "- : -",
      timer: 20,

      students: {
        here: 0,
        total: 0,
      },
      state: 'idle'
    };
  },
  computed: {
    percent() {
      return (this.question_id / this.total) * 100;
    },
  },
  methods: {
    setComponent() {
      switch (this.question.type) {
        case "short-answer":
          this.compQuestion = "q-short-answer";
          break;
        case "fill-in-the-gaps":
          this.compQuestion = "q-fill-in-the-gaps";
          break;
        case "multiple-choice":
          this.compQuestion = "q-multiple-choice";
          break;
      }

      this.compProp = {
        question: this.question,
        values: this.values,
      };

      this.reloadComp += 1;
    },

    nextQuestion() {
      this.submitAnswer();
      this.question_id += 1;
      this.loadQuestion();
    },

    prevQuestion() {
      this.submitAnswer();
      this.question_id -= 1;
      this.loadQuestion();
    },

    loadQuestion() {
      axios
        .get(`/api/activities/${this.activity_id}$`)
        .then((rep) => {
          this.students.total = rep.data.students;
          this.state = rep.data.state;
        });

      axios
        .get(
          `/api/activities/${this.activity_id}/questions/${
            this.question_id - 1
          }`
        )
        .then((rep) => {
          this.question = rep.data;
          this.setComponent();
        });
    },

    submitAnswer() {
      // Todo(tmz): Check if request not working
      if (this.values.length > 0) {
        let obj = { answer: [...this.values] };
        axios({
          method: "post",
          url: `/api/activities/${this.activity_id}/questions/${
            this.question_id - 1
          }`,
          data: obj,
        })
          .then(function (reponse) {
            //console.log(reponse);
          })
          .catch(function (erreur) {
            //console.log(erreur);
          });
        this.values.splice(0, this.values.length);
      }
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
    window.Echo.join(`activity.${this.activity_id}`)
      .here((users) => {
        this.students.here = users.length
        console.log(users);
      })
      .joining((user) => {
        this.students.here++;
        console.log(user.name);
      })
      .leaving((user) => {
        this.students.here--;
        console.log(user.name);
      });

    axios.get(`/api/activities/${this.activity_id}`).then((rep) => {
      //let question = rep.data.quiz.questions[this.question_id-1];
      this.questions = rep.data.quiz.questions;
      this.duration = rep.data.duration;
      this.activity_id = rep.data.id;
      this.started_at = rep.data.started_at;
      this.total = rep.data.quiz.questions_count;
      this.loaded = true;
      this.loadQuestion();
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
