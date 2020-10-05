<template>
  <div>
    <navbar>
      <template v-slot:title>Progression</template>
      <b-nav-item to="/quiz/activities">
        <b-icon-easel />
        Activités
      </b-nav-item>
      <b-nav-item v-if="this.isTeacher()" to="/quiz/quizzes">
        <b-icon-dice-5 />
        Quizzes
      </b-nav-item>
      <b-nav-item v-if="this.isTeacher()" to="/quiz/sandbox">
        <b-icon-bucket />
        Bac à sable
      </b-nav-item>
    </navbar>

    <div v-if="this.isTeacher()" class="mt-4 container">
      <vue-toggle variant="info" :toggled.sync="showNames" label="Afficher noms"></vue-toggle>
      <vue-toggle variant="warning" class="pl-2" :toggled.sync="showTF" label="Afficher résultats"></vue-toggle>
      <vue-toggle variant="danger" class="pl-2" :toggled.sync="showAnswers" label="Afficher réponses"></vue-toggle>
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

export default {
  components: {
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
      loadedMatrix: false
    };
  },
  props: {
    activity_id: Number
  },
  methods: {
    colorClass(value) {
      let classes = ''
      if (this.showTF && value.answer != null) {
          return value.is_correct ? 'bg-success' : 'bg-danger'
      } else if (value.answer != null) {
          return 'bg-info';
      }
    },
    isTeacher() {
      return Vue.prototype.$user.affiliation == 'member;staff';
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
          console.log("Student ", student)
        })
        .leaving(user => {
          console.log("Student ", student)
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
