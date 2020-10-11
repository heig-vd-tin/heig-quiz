<template>
  <div v-if="activity">
    <!-- Activity Idle -->
    <b-jumbotron v-if="activity.status == 'idle'" header="Fermé">
      <template v-slot:lead>
        L'activité n'est pas disponible, revenez plus tard...
      </template>
    </b-jumbotron>

    <!-- Activity Finished -->
    <b-jumbotron v-if="activity.status == 'finished'" header="Merci !">
      <template v-slot:lead>
        <p>L'activité n'est plus disponible pour édition.</p>
        <b-button :to="`/activities/${activity.id}/results`" variant="primary">Voir la correction</b-button>
      </template>
    </b-jumbotron>

    <!-- Activity Opened -->
    <b-jumbotron class="waiting-room" v-if="activity.status == 'opened'" header="Salle d'attente">
      <template v-slot:lead>
        {{ here }} / {{ total }} étudiant{{ total > 1 ? 's' : '' }} connectés ; attente de
        {{ total - here }}
        étudiant{{ total - here > 1 ? 's' : '' }}...
        <b-spinner v-if="total - here > 1" label="Spinning"></b-spinner>
        <br />
        <div v-if="here">
          Présent{{ here > 1 ? 's' : '' }} :
          <ul>
            <li v-for="student in students" :key="student.id">
              {{ student.name }}
            </li>
          </ul>
        </div>
      </template>
    </b-jumbotron>

    <!-- Wait for the end -->
    <b-jumbotron v-if="activity.status == 'running' && finished" header="Merci !">
      <template v-slot:lead>
        <p>L'activité n'est plus disponible pour édition. Vos résultats dans :</p>
        <countdown :time="timeLeft" @end="activity.status = 'finished'">
          <template slot-scope="props">
            <span :class="{ 'text-danger': props.totalMilliseconds <= 30 * 1000 }">
              {{ String(props.minutes).padStart(2, '0') }}
              :
              {{ String(props.seconds).padStart(2, '0') }}
            </span>
          </template>
        </countdown>
      </template>
    </b-jumbotron>
    <!-- Quiz started -->
    <div v-else-if="activity.status == 'running' && !finished">
      <div class="progress mb-2">
        <div
          class="progress-bar bg-dark progress-bar"
          role="progressbar"
          :style="'width: ' + percent + '%'"
          :aria-valuenow="percent"
          aria-valuemin="0"
          aria-valuemax="100"
        >
          {{ question_id }}/{{ activity.quiz.questions }}
        </div>
      </div>
      <b-card
        border-variant="dark"
        :title="'Question ' + question_id + '. ' + (question.name != null ? question.name : '')"
        align="left"
      >
        <component
          class="mb-4"
          :key="component_nonce"
          :is="component_question"
          v-bind="question"
          @update:answered="
            u => {
              answered = u;
            }
          "
        ></component>
        <b-container>
          <b-row class="text-center align-middle">
            <b-col>
              <b-button v-if="question.previous_question" pill variant="outline-secondary" @click="previousQuestion">
                Précédent
              </b-button>
            </b-col>
            <b-col cols="5">
              <h2 class="display-3 time">
                <countdown :time="timeLeft" @end="activity.status = 'finished'">
                  <template slot-scope="props">
                    {{ String(props.minutes).padStart(2, '0') }}
                    :
                    {{ String(props.seconds).padStart(2, '0') }}
                  </template>
                </countdown>
              </h2>
            </b-col>
            <b-col>
              <b-button v-if="question.next_question" pill variant="outline-secondary" @click="nextQuestion">
                Suivant
              </b-button>
              <!-- Last question -->
              <b-button v-else pill variant="primary" @click="submitQuiz">
                Terminer
              </b-button>
            </b-col>
          </b-row>
        </b-container>
      </b-card>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

import Code from './questions/Code';
import FillInTheGaps from './questions/FillInTheGaps';
import MultipleChoice from './questions/MultipleChoice';
import ShortAnswer from './questions/ShortAnswer';

import VueCountdown from '@chenfengyuan/vue-countdown';
import { mapActions, mapGetters } from 'vuex';

export default {
  components: {
    'q-code': Code,
    'q-fill-in-the-gaps': FillInTheGaps,
    'q-multiple-choice': MultipleChoice,
    'q-short-answer': ShortAnswer,
    countdown: VueCountdown
  },
  props: {
    question_id: {
      type: Number,
      required: false,
      default: 1
    },
    activity_id: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      finished: false,
      answered: null,
      name: '',
      component_nonce: 0,
      component_question: null,
      question: {},
      values: [],
      timeLeft: 0
    };
  },
  watch: {
    activity() {
      console.log('ActivityUpdated');
      if (this.activity.status == 'running') {
        let elapsed = Date.now() - Date.parse(this.activity.started_at);
        this.timeLeft = this.activity.duration * 1000 - elapsed;
      } else {
        this.timeLeft = 0;
      }
    },
    answered() {
      console.log('QuestionUpdated');
    },
    question_id() {
      console.log('Question Id Changed');
      this.loadQuestion();
    }
  },
  computed: {
    percent() {
      return (this.question_id / this.activity.quiz.questions) * 100;
    },
    here() {
      return this.students.length
    },
    total() {
      return this.activity.roster.students
    },
    ...mapGetters('activity', [
      'students',
      'activity'
    ])
  },
  methods: {
    setComponent() {
      switch (this.question.type) {
        case 'short-answer':
          this.component_question = 'q-short-answer';
          break;
        case 'fill-in-the-gaps':
          this.component_question = 'q-fill-in-the-gaps';
          break;
        case 'multiple-choice':
          this.component_question = 'q-multiple-choice';
          break;
      }
      this.component_nonce += 1;
    },
    previousQuestion() {
      if (this.answered != this.question.answered) this.submitQuestion();

      this.$router.push(`/activities/${this.activity_id}/questions/${this.question.previous_question}`);
    },
    nextQuestion() {
      if (this.answered != this.question.answered) this.submitQuestion();

      this.$router.push(`/activities/${this.activity_id}/questions/${this.question.next_question}`);
    },
    submitQuestion() {
      axios({
        method: 'post',
        url: `/api/activities/${this.activity_id}/questions/${this.question_id}`,
        data: { answer: this.answered }
      }).catch(function(erreur) {
        console.log(erreur);
      });
    },
    submitQuiz() {
      if (this.answered != this.question.answered) this.submitQuestion();
      this.finished = true;
    },
    loadQuestion() {
      axios.get(`/api/activities/${this.activity_id}/questions/${this.question_id}`).then(rep => {
        this.question = rep.data;
        this.answered = this.question.answered;
        this.setComponent();
      });
    },
    ...mapActions('activity', ['joinActivity', 'leaveActivity'])
  },
  mounted() {
    this.joinActivity(this.activity_id);
  },
  destroyed() {
    this.leaveActivity(this.activity_id);
  }
};
</script>
<style scoped>
.time {
  font-size: 2.5rem;
}

.waiting-room {
  background-image: url('../../img/waiting-room.svg');
  background-position: right;
  background-repeat: no-repeat;
  background-origin: content-box;
}
</style>
