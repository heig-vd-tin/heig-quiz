<template>
  <div>
    <navbar>
      <template v-slot:title>
        Liste des questions
      </template>
      <b-nav-item to="/quiz/activities"><b-icon-easel/> Activités</b-nav-item>
      <b-nav-item v-if="this.isTeacher()" to="/quiz/quizzes"><b-icon-dice-5 /> Quizzes</b-nav-item>
      <b-nav-item v-if="this.isTeacher()" to="/quiz/sandbox"><b-icon-bucket/> Bac à sable</b-nav-item>
    </navbar>
    <div class="mt-2 container">
      <b-button variant="primary" to="/quiz/questions/create">Nouvelle Question</b-button>
      <!-- Questions list -->
      <b-table
        pt-2
        striped
        hover
        :items="questions.data"
        :fields="questions.fields"
      >

        <template v-slot:cell(name)="data">
          {{ data.item.name }}<br />
          <b-badge
            class="mr-1 keyword"
            v-for="keyword in data.item.keywords"
            v-bind:key="keyword"
            variant="secondary"
            >{{ keyword.name }}</b-badge
          >
        </template>

        <template v-slot:cell(difficulty)="data">
          <b-badge v-if="data.item.difficulty == 'easy'" variant="success"
            ><div class="difficulty-easy"
          /></b-badge>
          <b-badge v-else-if="data.item.difficulty == 'medium'" variant="warning"
            ><div class="difficulty-medium"
          /></b-badge>
          <b-badge v-else-if="data.item.difficulty == 'hard'" variant="danger"
            ><div class="difficulty-hard"
          /></b-badge>
          <b-badge v-else-if="data.item.difficulty == 'insane'" variant="dark"
            ><div class="difficulty-insane"
          /></b-badge>
        </template>

      </b-table>
    </div>
</div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      questions: {
        fields: [
          {
            key: "id",
            label: "#",
            sortable: true,
          },
          {
            key: "name",
            label: "Question",
            sortable: true,
          },
          {
            key: "type",
            label: "Type",
            sortable: true,
          },
          {
            key: "difficulty",
            label: "Difficulté",
            sortable: true,
          },
          {
            label: "Créé le",
            key: "created_at",
          },
        ],
        data: [],
        loaded: false,
      },
    };
  },
  methods: {
    isTeacher() {
      return Vue.prototype.$user.affiliation == "member;staff";
    },
    isStudent() {
      return Vue.prototype.$user.affiliation == "member;student";
    },
    loadQuestions() {
      axios.get("/api/questions").then((rep) => {
        this.questions.data = rep.data.data;
        this.questions.count = rep.data.count;
        this.questions.loaded = true;
      });
    },

  },
  mounted() {
    this.loadQuestions();
  },
};
</script>
<style scoped>
.keyword {
  font-size: 70%;
  padding: 4px;
  text-transform: lowercase;
}
</style>
