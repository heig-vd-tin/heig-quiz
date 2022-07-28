<template>
  <div>
    <div v-if="$store.state.user.role == 'teacher'" class="mt-4 container">
      <b-row>
        <b-col class="align-middle">
          <vue-toggle variant="info" :toggled.sync="showNames" label="Afficher noms"></vue-toggle>
          <vue-toggle variant="warning" class="pl-2" :toggled.sync="showTF" label="Afficher résultats"></vue-toggle>
          <vue-toggle variant="danger" class="pl-2" :toggled.sync="showAnswers" label="Afficher réponses"></vue-toggle>
        </b-col>
        <b-col lg="2" class="text-right align-middle">
          <countdown @end="activity.status = 'finished'" v-if="activity.status == 'running'"
            :time="activity.duration * 1000 - (Date.now() - Date.parse(activity.started_at)) + 2000">
            <template slot-scope="props">
              <h2 class="lead" :class="{ 'text-danger': props.totalMilliseconds <= 30 * 1000 }">
                {{ String(props.hours) }}
                :
                {{ String(props.minutes).padStart(2, '0') }}
                :
                {{ String(props.seconds).padStart(2, '0') }}
              </h2>
            </template>
          </countdown>
        </b-col>
      </b-row>

      <b-row class="align-right">
        <b-col class="text-right align-middle">
          <b-button @click="addTime">
            Add time
          </b-button>
          
        </b-col>
        <b-col md="2" class="text-right align-middle">
          <b-input
            type="number"
            v-model="add_time"
            placeholder="0"
            
          >
          </b-input>
        </b-col>
      </b-row>
      <br/>
      <table v-if="loadedMatrix" class="matrix">
        <tr>
          <th>&nbsp;</th>
          <th class="lead text-center" v-for="(question, index) in questions" :key="question.id">Q{{ index + 1 }}</th>
        </tr>
        <tr v-for="(student, index) in students" :key="student.id">
          <th class="lead">{{ showNames ? student.name : `Étudiant ${index + 1}` }} </th>
          <td v-for="(question, q) in questions" :key="question.id" :class="colorClass(matrix[student.id][q])">
            {{ showAnswers ? matrix[student.id][q].answer : '' }}
          </td>
        </tr>
      </table>
    </div>
    <div v-else class="mt-4 container">
      Pas d'accès étudiants
    </div>
  </div>
</template>

<script>
import Toggle from '../layouts/toggle'
import VueCountdown from '@chenfengyuan/vue-countdown';
import axios from 'axios';

export default {
  components: {
    countdown: VueCountdown,
    'vue-toggle': Toggle
  },
  data() {
    return {
      students: [],
      activity: {},
      matrix: {},
      questions: [],
      showNames: false,
      showTF: false,
      showAnswers: false,
      loadedMatrix: false,
      add_time: 0
    };
  },
  props: {
    activity_id: Number
  },
  methods: {
    colorClass(value) {
      let classes = ''

      if (this.showTF && value.need_help == true) {
        return 'bg-warning'
      } else if (this.showTF && value.answer != null) {
        return value.is_correct ? 'bg-success' : 'bg-danger'
      } else if (value.answer != null) {
        return 'bg-info';
      }
    },

    addTime(){
      let time = {
        id: this.activity_id,
        time: this.add_time*60
      }
      
      axios({
        method: 'post',
        url: `/api/activities/${this.activity_id}/addTime`,
        data: time
      })
      .then((response) => {
        this.activity.duration = parseInt(this.add_time) * 60 + parseInt(this.activity.duration)
      })
      .catch((error) => {
        console.log(error)
      });
      
      console.log(this.activity.duration);
    },

    loadActivity() {
      axios.get(`/api/activities/${this.activity_id}`).then(({ data: activity }) => {
        this.activity = activity;
        this.joinActivityChannel();
        this.loadStudents();
        this.loadQuestions();
        this.loadMatrix();
      });
    },

    loadQuestions() {
      axios.get(`/api/activities/${this.activity_id}/questions`).then(({ data: questions }) => {
        this.questions = questions.data;
      });
    },

    loadStudents() {
      axios.get(`/api/rosters/${this.activity.roster.id}/students`).then(({ data: students }) => {
        this.students = students.data;
      });
    },

    loadMatrix() {
      axios.get(`/api/activities/${this.activity_id}/progression`).then(({ data: matrix }) => {
        this.matrix = matrix;
        this.loadedMatrix = true;
      });
    },

    joinActivityChannel() {
      window.Echo.join(`activity.${this.activity_id}`)
        .here(users => {
          let students = 0;
          users.forEach(student => {
            console.log("Student ", student)
          });
        })
        .joining(user => {
          console.log("Student ", user)
        })
        .leaving(user => {
          console.log("Student ", user)
        })
        .listen('ActivityUpdated', e => {
          this.loadActivity();
        })
        .listen('AnswerUpdated', e => {
          this.loadMatrix();
        });
    }
  },

  mounted() {
    this.loadActivity()
  }
};
</script>
<style scoped lang="scss">
@import '../../sass/variables';

.matrix {
  border: 1px solid black;
  width: 100%;
}

.matrix td {
  border: 1px solid black;
  padding: 4px;
}

.matrix th {
  border: 1px solid black;
  padding: 4px;
}
</style>
