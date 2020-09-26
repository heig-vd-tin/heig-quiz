<template>
  <div>
    <navbar>
      <b-nav-item href="/quiz/activities">Activités</b-nav-item>
      <b-nav-item href="/quiz/sandbox">Bac à sable</b-nav-item>
    </navbar>
    <div class="mt-2 container">
      <h2>Tous les quiz</h2>
      <b-table
        v-if="quizzes.loaded"
        striped
        hover
        :items="quizzes.data"
        :fields="quizzes.fields"
      >
        <template v-slot:cell(actions)="data">
          <b-button
            v-on:click="createActivity(data.index)"
            variant="outline-success"
            class="btn-circle"
          >
            <b-icon-play-fill></b-icon-play-fill>
          </b-button>
        </template>
      </b-table>

      <!-- New Activity from Quiz -->
      <b-sidebar id="new-activity" title="Nouvelle Activité" right shadow>
        <template v-slot:footer="{ hide }">
          <div class="d-flex bg-dark text-light align-items-center px-3 py-2">
            <strong class="mr-auto">Créer Activité</strong>
            <b-button
              v-on:click="createActivity(data.item.id)"
              variant="success"
              class="btn-circle btn-xl"
            >
              <b-icon-plus></b-icon-plus>
            </b-button>
          </div>
        </template>
        <div class="px-3 py-2">
          <p class="mb-3">
            Une nouvelle activité sera crée pour la classe sélectionnée. Il
            faudra encore la démarrer pour qu'elle soit visible aux étudiants.
          </p>
          <b-form>
            <b-form-group
              id="input-group-1"
              label="Durée"
              label-for="duration"
              description="Durée du quiz en secondes"
            >
              <b-form-input
                id="input-duration"
                v-model="activity.duration"
                type="number"
                required
                placeholder="Entrer durée"
              ></b-form-input>
            </b-form-group>
          </b-form>
        </div>
      </b-sidebar>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
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
    createActivity(quiz_id) {
      this.$root.$emit("bv::toggle::collapse", "new-activity");
      console.log("Abc", quiz_id);
    },
    loadQuizzes() {
      axios.get("/api/quizzes").then((rep) => {
        this.quizzes.data = rep.data.data;
        this.quizzes.count = rep.data.count;
        this.quizzes.loaded = true;
      });
    },
  },
  mounted() {
    this.loadQuizzes();
  }
};
</script>
