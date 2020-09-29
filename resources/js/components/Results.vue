<template>
  <div>
    <navbar>
      <template v-slot:title> Activités </template>
      <b-nav-item to="activities"><b-icon-easel /> Activités</b-nav-item>
      <b-nav-item v-if="this.isTeacher()" to="quizzes"
        ><b-icon-dice-5 /> Quizzes</b-nav-item
      >
      <b-nav-item v-if="this.isTeacher()" to="sandbox"
        ><b-icon-bucket /> Bac à sable</b-nav-item
      >
    </navbar>
    <div class="mt-4 container">
      <h2>Résultats du Quiz</h2>
      <b-card
        class="mb-4 mt-1"
        v-for="(question, index) in questions"
        :key="question.id"
      >
        <template v-slot:header>
          <h2 class="mb-0">
            <b-icon-check2 class="text-success" v-if="question.is_correct" />
            <b-icon-x v-else class="text-danger" />
            <strong>Question {{ index + 1}}. </strong> {{ question.name }}
          </h2>
          <b-progress height="1.0rem"
            v-if="question.stats"
            class="mt-2 opacity-50"
            :max="
              question.stats.correct +
              question.stats.incorrect +
              question.stats.unanswered
            "
            show-value
          >
            <b-progress-bar
              :value="question.stats.correct"
              variant="success"
            ></b-progress-bar>
            <b-progress-bar striped
              :value="question.stats.incorrect"
              variant="danger"
            ></b-progress-bar>
            <b-progress-bar striped
              :value="question.stats.unanswered"
              variant="warning"
            ></b-progress-bar>
          </b-progress>
        </template>

        <component
          class="mb-4"
          :is="question.component"
          v-bind="question"
        ></component>
      </b-card>
    </div>
  </div>
</template>

<script>
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
      questions: {},
    };
  },
  props: {
    activity_id: null,
  },
  watch: {
    current_roster(roster) {
      let id = roster != null ? roster.id : null;

      // Hide roster column if one is selected
      let field = this.activities.fields.find(
        (field) => field.key == "roster.name"
      );
      field.class = id ? "d-none" : "";

      this.loadActivities(id);
    },
  },
  methods: {
    getComponent(question) {
      switch (question.type) {
        case "short-answer":
          return "q-short-answer";
          break;
        case "fill-in-the-gaps":
          return "q-fill-in-the-gaps";
          break;
        case "multiple-choice":
          return "q-multiple-choice";
          break;
      }
      return null;
    },
    isTeacher() {
      return Vue.prototype.$user.affiliation == "member;staff";
    },
    loadQuiz() {
      axios
        .get(`/api/activities/${this.activity_id}/questions`)
        .then(({ data: questions }) => {
          this.questions = questions;
          this.questions.forEach((question) => {
            question.component = this.getComponent(question);
          });
        });
    },
  },
  mounted() {
    this.loadQuiz();
  },
};
</script>
<style scoped>
.opacity-50 {
  opacity: 0.5;
}
</style>
