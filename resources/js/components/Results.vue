<template>
  <div>
    <h2 v-if="activity.quiz">
      Quiz n°{{ activity.quiz.id }}
      <span v-if="activity.mark">(note : {{ activity.mark }})</span>
    </h2>
    <b-card class="mb-4 mt-1" v-for="(question, index) in questions" :key="question.id">
      <template v-slot:header>
        <b-progress
          :id="`progress-${question.id}`"
          v-if="question.statistics"
          class="opacity-50 pb"
          :max="
            question.statistics.correct_answers +
              question.statistics.incorrect_answers +
              question.statistics.missing_answers
          "
        >
          <b-progress-bar :value="question.statistics.correct_answers" variant="success"></b-progress-bar>
          <b-progress-bar striped :value="question.statistics.incorrect_answers" variant="danger"></b-progress-bar>
          <b-progress-bar striped :value="question.statistics.missing_answers" variant="warning"></b-progress-bar>
        </b-progress>

        <b-popover
          v-if="question.statistics"
          :target="`progress-${question.id}`"
          triggers="hover"
          placement="auto"
          delay="5"
        >
          <div v-if="question.statistics.correct_answers > 0">
            Réponses correctes :
            <strong>{{ question.statistics.correct_answers }}</strong>
            <br />
          </div>
          <div v-if="question.statistics.incorrect_answers > 0">
            Réponses incorrectes :
            <strong>{{ question.statistics.incorrect_answers }}</strong>
            <br />
          </div>
          <div v-if="question.statistics.missing_answers > 0">
            Non répondus :
            <strong>{{ question.statistics.missing_answers }}</strong>
            <br />
          </div>
        </b-popover>

        <h2 class="mt-2 mb-0">
          <span v-if="$store.state.user.role == 'student'">
            <b-icon-check2 class="text-success" v-if="question.is_correct" />
            <b-icon-x v-else class="text-danger" />
          </span>
          <strong>Question {{ index + 1 }}.</strong>
          {{ question.name }}
        </h2>
      </template>

      <component class="mb-4" :is="question.component" v-bind="question"></component>

      <b-alert show variant="info" v-if="question.explanation">
        <markdown-it-vue mb-2 :content="question.explanation" />
      </b-alert>
    </b-card>
  </div>
</template>

<script>
import Code from './questions/Code';
import FillInTheGaps from './questions/FillInTheGaps';
import MultipleChoice from './questions/MultipleChoice';
import ShortAnswer from './questions/ShortAnswer';
import MarkdownItVue from 'markdown-it-vue'
export default {
  components: {
    'q-code': Code,
    'q-fill-in-the-gaps': FillInTheGaps,
    'q-multiple-choice': MultipleChoice,
    'q-short-answer': ShortAnswer,
    MarkdownItVue
  },
  data() {
    return {
      questions: {},
      activity: {}
    };
  },
  props: {
    activity_id: null
  },
  watch: {
    current_roster(roster) {
      let id = roster != null ? roster.id : null;

      // Hide roster column if one is selected
      let field = this.activities.fields.find(field => field.key == 'roster.name');
      field.class = id ? 'd-none' : '';

      this.loadActivities(id);
    }
  },
  methods: {
    getComponent(question) {
      switch (question.type) {
        case 'short-answer':
          return 'q-short-answer';
          break;
        case 'fill-in-the-gaps':
          return 'q-fill-in-the-gaps';
          break;
        case 'multiple-choice':
          return 'q-multiple-choice';
          break;
      }
      return null;
    },
    loadActivity() {
      axios.get(`/api/activities/${this.activity_id}`).then(({ data: activity }) => {
        this.activity = activity;
      });
    },
    loadQuiz() {
      axios.get(`/api/activities/${this.activity_id}/questions`).then(({ data: { data, count } }) => {
        this.questions = data;

        this.questions.forEach(question => {
          question.component = this.getComponent(question);
        });
      });
    }
  },
  mounted() {
    this.loadActivity();
    this.loadQuiz();
  }
};
</script>
<style scoped>
.opacity-50 {
  opacity: 0.5;
}

.pb {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  border-radius: 0;
  height: 0.5rem;
}
</style>
