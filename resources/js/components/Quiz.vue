<template>
  <div>
    <navbar>
      <template v-slot:title> {{ name }} </template>
      <b-nav-item to="/quiz/activities"><b-icon-easel /> Activités</b-nav-item>
      <b-nav-item v-if="this.isTeacher()" to="/quiz/quizzes"
        ><b-icon-dice-5 /> Quizzes</b-nav-item
      >
      <b-nav-item v-if="this.isTeacher()" to="/quiz/sandbox"
        ><b-icon-bucket /> Bac à sable</b-nav-item
      >
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
          <b-spinner
            v-if="students.total - students.here > 1"
            label="Spinning"
          ></b-spinner>
        </template>
      </b-jumbotron>
      <!-- Quiz started -->
      <div v-else-if="activity.status == 'running'">
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
            v-bind="question"
            @update:answered="(u) => {answered = u}"
          ></component>
          <b-container>
            <b-row class="text-center align-middle">
              <b-col>
                <b-button
                  v-if="question.previous_question"
                  pill
                  variant="outline-secondary"
                  :to="`/quiz/activities/${activity.id}/questions/${question.previous_question + 1}`"
                  >Précédent</b-button
                >
              </b-col>
              <b-col cols="5">
                <h2 class="display-3 time">
                  <countdown :time="timeLeft">
                    <template slot-scope="props">
                      {{ String(props.minutes).padStart(2, "0") }} :
                      {{ String(props.seconds).padStart(2, "0") }}
                    </template>
                  </countdown>
                </h2>
              </b-col>
              <b-col>
                <b-button
                  v-if="question.next_question"
                  pill
                  variant="outline-secondary"
                  :to="`/quiz/activities/${activity.id}/questions/${question.next_question + 1}`"
                  >Suivant</b-button
                >
              </b-col>
            </b-row>
          </b-container>
        </b-card>
      </div>
    </div>
    {{ answered }}
  </div>
</template>

<script>
import axios from "axios";

import Code from "./questions/Code";
import FillInTheGaps from "./questions/FillInTheGaps";
import MultipleChoice from "./questions/MultipleChoice";
import ShortAnswer from "./questions/ShortAnswer";

import VueCountdown from "@chenfengyuan/vue-countdown";

export default {
  components: {
    'q-code': Code,
    'q-fill-in-the-gaps': FillInTheGaps,
    'q-multiple-choice': MultipleChoice,
    'q-short-answer': ShortAnswer,
    'countdown': VueCountdown,
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
      activity: {},
      answered: null,
      name: "",
      reloadComp: 0,
      compQuestion: null,
      compProp: {},
      total: 1,
      question: {},
      values: [],
      students: {
        here: 0,
        total: 0,
      },
      timeLeft: 0
    };
  },
  watch: {
    activity() {
      console.log("ActivityUpdated")
      if (this.activity.status == "running") {
        let elapsed = Date.now() - Date.parse(this.activity.started_at);
        this.timeLeft = this.activity.duration * 1000 - elapsed
      } else {
        this.timeLeft = 0;
      }
    },
    answered() {
      console.log("QuestionUpdated")
    },
    question_id() {
      this.loadQuestion();
    }
  },
  computed: {
    percent() {
      return (this.question_id / this.total) * 100;
    },
  },
  methods: {
    isTeacher() {
      return Vue.prototype.$user.affiliation == "member;staff";
    },
    isStudent() {
      return Vue.prototype.$user.affiliation == "member;student";
    },
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
      console.log(this.question);
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
    loadActivity() {
      axios
        .get(`/api/activities/${this.activity_id}`)
        .then(({ data: activity }) => {
          this.activity = activity;
          this.loadQuestion();
        });
    },
    joinActivityChannel() {
      window.Echo.join(`activity.${this.activity_id}`)
        .here((users) => {
          let students = 0;
          users.forEach((student) => {
            if (student.type == "student") students++;
          });
          this.students.here = students;
        })
        .joining((user) => {
          if (user.type == "student") this.students.here++;
        })
        .leaving((user) => {
          if (user.type == "student") this.students.here--;
        })
        .listen("ActivityUpdated", (e) => {
          this.loadActivity();
        });
    },
  },
  mounted() {
    this.joinActivityChannel();
    this.loadActivity();
  },
};
</script>
<style scoped>
.time {
  font-size: 2.5rem;
}
</style>
