<template>
  <div>
    <navbar>
      <template v-slot:title>
        Liste des Quiz
      </template>
      <b-nav-item to="/quiz/activities"><b-icon-easel/> Activités</b-nav-item>
      <b-nav-item to="/quiz/sandbox"><b-icon-bucket/> Bac à sable</b-nav-item>
    </navbar>
    <div class="mt-2 container">
      <h2>Tous les quiz</h2>

      <!-- Quizzes list -->
      <b-table
        v-if="quizzes.loaded"
        striped
        hover
        :items="quizzes.data"
        :fields="quizzes.fields"
      >

        <template v-slot:cell(name)="data">
          {{ data.item.name }}<br />
          <b-badge
            class="mr-1 keyword"
            v-for="keyword in data.item.keywords"
            v-bind:key="keyword"
            variant="secondary"
            >{{ keyword }}</b-badge
          >
        </template>

        <template v-slot:cell(difficulty)="data">
          <b-badge v-if="data.item.difficulty <= 1" variant="success"
            ><div class="difficulty-easy"
          /></b-badge>
          <b-badge v-else-if="data.item.difficulty <= 2" variant="warning"
            ><div class="difficulty-medium"
          /></b-badge>
          <b-badge v-else-if="data.item.difficulty <= 3" variant="danger"
            ><div class="difficulty-hard"
          /></b-badge>
          <b-badge v-else-if="data.item.difficulty <= 4" variant="dark"
            ><div class="difficulty-insane"
          /></b-badge>
        </template>

        <template v-slot:cell(actions)="data">
          <b-button
            v-on:click="displayCreateActivityForm(data.item.id)"
            variant="outline-primary"
            class="btn-circle pulse-primary"
            v-b-popover.hover.left="'Créer une activité à partir de ce quiz'"
          >
          <b-icon-plus/>
          </b-button>
        </template>

      </b-table>

      <!-- New Activity from Quiz -->
      <b-modal @ok="createActivity" id="new-activity-modal" title="Nouvelle activité" cancel-title="Annuler" ok-title="Créer" centered>
        <p class="mb-3">
          Une nouvelle activité sera crée pour la classe sélectionnée.
          Il faudra encore la démarrer pour qu'elle soit visible aux étudiants.
        </p>
        <b-form ref="createActivityForm" @submit.stop.prevent="handleSubmit">
          <b-form-group
            id="input-roster"
            label="Classe"
            label-for="roster"
          >
          <b-form-select id="input-roster" v-model="form.roster_id" required>
            <b-form-select-option v-for="roster in rosters.data" v-bind:key="roster.id" :value="roster.id">{{roster.name}}</b-form-select-option>
          </b-form-select>
          </b-form-group>
          <b-form-group
            id="input-group-1"
            label="Durée"
            label-for="duration"
            description="Durée du quiz en secondes"
            invalid-feedback="Durée requise"
          >
            <b-form-input
              id="input-duration"
              v-model="form.duration"
              type="number"
              required
              placeholder="Entrer durée"
            ></b-form-input>
          </b-form-group>

    <b-form-checkbox
      id="shuffleQuestions"
      v-model="form.shuffle_questions"
      name="shuffleQuestions"
    >
      Mélanger les questions
    </b-form-checkbox>
    <b-form-checkbox
      id="shufflePropositions"
      v-model="form.shuffle_propositions"
      name="shufflePropositions"
    >
      Mélanger les propositions multiples
    </b-form-checkbox>
        </b-form>
      </b-modal>
    </div>
</div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      form: {
        duration: 500,
        roster_id: null,
        quiz_id: null,
        shuffle_questions: false,
        shuffle_propositions: false,
      },
      rosters: {
        data: [],
        count: 0,
        loaded: false,
      },
      quizzes: {
        fields: [
          {
            key: "id",
            label: "#",
            sortable: true,
          },
          {
            key: "name",
            label: "Quiz",
            sortable: true,
          },
          {
            key: "questions",
            label: "Questions",
            sortable: true,
          },
          {
            key: "taken_times",
            label: "Utilisé",
            sortable: true,
          },
          {
            key: "owner.name",
            label: "Créateur",
            sortable: true,
          },
          {
            key: "difficulty",
            label: "Difficulté",
            sortable: true,
          },
          {
            label: "Actions",
            key: "actions",
          },
        ],
        data: [],
        loaded: false,
      },
    };
  },
  methods: {
    /**
     * Create activity form/modal
     */
    displayCreateActivityForm(quiz_id) {
      this.$bvModal.show("new-activity-modal");
      console.log("Hey", quiz_id)
      this.form.quiz_id = quiz_id;
      this.loadRosters();
    },
    createActivity(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.handleSubmit();
    },
    checkFormValidity() {
      const valid = this.$refs.createActivityForm.checkValidity();
      return valid;
    },
    handleSubmit() {
      console.log("Handle Submit");
      if (!this.checkFormValidity()) {
        return;
      }

      axios
        .post("/api/activities/create", this.form)
        .then((response) => {
          this.$router.push("activities");
        })
        .catch((error) => {
          console.log(error);
        });

      this.$nextTick(() => {
        this.$bvModal.hide("new-activity-modalng");
      });
    },

    /**
     * Async fetch
     */
    loadQuizzes() {
      axios.get("/api/quizzes").then((rep) => {
        this.quizzes.data = rep.data.data;
        this.quizzes.count = rep.data.count;
        this.quizzes.loaded = true;
      });
    },
    loadRosters() {
      axios.get("/api/user/rosters").then((rep) => {
        this.rosters.data = rep.data.data;
        this.rosters.count = rep.data.count;
        this.rosters.loaded = true;
      });
    },
  },
  mounted() {
    this.loadQuizzes();
    Echo.private("quiz").listen("QuizCreated", (e) => {
      this.loadQuizzes();
    });
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
