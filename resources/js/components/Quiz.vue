<template>
  <div>
    <navbar>
      <template v-slot:title> {{ name }} </template>
      <b-nav-item to="activities"><b-icon-easel/> Activités</b-nav-item>
      <b-nav-item v-if="this.isTeacher()" to="quizzes"><b-icon-dice-5/> Quizzes</b-nav-item>
      <b-nav-item v-if="this.isTeacher()" to="sandbox"><b-icon-bucket/> Bac à sable</b-nav-item>
    </navbar>
    <div class="mt-4 container">
      <!-- Activity Finished -->
      <b-jumbotron v-if="activity.status == 'finished'" header="Erreur !">
        <template v-slot:lead>
          L'activité n'est plus disponible pour édition.
        </template>
      </b-jumbotron>
      <!-- Activity Idle -->
      <b-jumbotron v-if="activity.status == 'idle'" header="Fermé">
        <template v-slot:lead>
          L'activité n'est pas disponible, revenez plus tard.
        </template>
      </b-jumbotron>
      <!-- Activity Opened -->
      <b-jumbotron v-if="activity.status == 'opened'" header="Salle d'attente">
        <template v-slot:lead>
          {{ students.here }} / {{ students.total }} étudiant{{
            students.total > 1 ? "s" : ""
          }}
          connectés. Attente d'encore
          {{ students.total - students.here }} étudiant{{
            students.total - students.here > 1 ? "s" : ""
          }}.
          <b-spinner v-if="students.total - students.here > 1" label="Spinning"></b-spinner>
        </template>
      </b-jumbotron>
      <!-- Quiz started -->
      <div v-else-if="activity.status == 'started'">
        <div class="progress mb-2">
          <div
            class="progress-bar bg-dark progress-bar"
            role="progressbar"
            :style="'width: ' + percent + '%'"
            :aria-valuenow="percent"
            aria-valuemin="0"
            aria-valuemax="100"
          >
            {{ question_id }}/{{ total }}
          </div>
        </div>
        <b-card
          border-variant="dark"
          :title="question_id + '. ' + question.name"
          align="left"
        >
          <component
            class="mb-4"
            :key="reloadComp"
            :is="compQuestion"
            v-bind="compProp"
          ></component>
          <b-container>
            <b-row class="text-center align-middle">
              <b-col>
                <b-button
                  v-if="question_id > 1"
                  pill
                  variant="outline-secondary"
                  @click="previousQuestion"
                  >Précédent</b-button
                >
              </b-col>
              <b-col cols="5">
                <h2
                  class="display-3 time"
                  v-bind:class="{ 'text-danger': timer < 30 }"
                >
                  {{ countdown }}
                </h2>
              </b-col>
              <b-col>
                <b-button
                  v-if="question_id < total"
                  pill
                  variant="outline-secondary"
                  @click="nextQuestion"
                  >Suivant</b-button
                >
              </b-col>
            </b-row>
          </b-container>
        </b-card>
      </div>
    </div>
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
  props: {
    question_id: {
      type: Number,
      required: true,
    },
    activity_id: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      name: "",
      activity: {
        duration: 0,
      },
      reloadComp: 0,
      compQuestion: null,
      compProp: {},
      total: 1,
      question: {},
      values: [],
      countdown: "- : -",
      timer: 20,

      students: {
        here: 0,
        total: 0,
      },
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

    previousQuestion() {
      this.submitAnswer();
      this.question_id -= 1;
      this.loadQuestion();
    },

    loadQuestion() {
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
    loadActivity() {
      axios
        .get(`/api/activities/${this.activity_id}`)
        .then(({ data: activity }) => {
          this.activity = activity;
          this.students.total = activity.roster.students;
          // this.loadQuestion();
          // this.startTimer(this.duration);
        });
    },
  },
  mounted() {
    window.Echo.join(`activity.${this.activity_id}`)
      .here((users) => {
        let students = 0;
        users.forEach((student) => {
          if (student.type == "student") students++;
        });
        this.students.here = students;
        console.log(users);
      })
      .joining((user) => {
        if (user.type == "student") this.students.here++;
        console.log(user.name);
      })
      .leaving((user) => {
        if (user.type == "student") this.students.here--;
        console.log(user.name);
      })
      .listen("ActivityUpdated", (e) => {
        this.loadActivity();
        console.log("Activity Updated", e);
      });

    this.loadActivity();
  },
};
</script>
<style scoped>
.time {
  font-size: 2.5rem;
}
</style>
