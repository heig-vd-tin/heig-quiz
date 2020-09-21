<template>
  <div>
  <b-tabs content-class="mt-3">
    <b-tab v-for="roster in rosters.data" :key="roster.id" :title="roster.name" v-bind:class="{ active : rosters.active == roster.id }"></b-tab>
  </b-tabs>

    <h2>Activités</h2>
    <p>Les activités en cours et passées sont accessibles des étudiants concernés. En cachant une activité, les étudiants ne pourront plus y accéder.</p>

    <b-table
      v-if="activities.loaded"
      striped
      hover
      :items="activities.data"
      :fields="activities.fields"
      @row-clicked="activityClickHandler"
    >
      <template v-slot:cell(actions)="data">
        <b-button
          v-if="data.item.hidden"
          v-on:click="showActivity(data.item.id)"
          variant="outline-primary"
          class="btn-circle"
        >
          <b-icon-eye-slash></b-icon-eye-slash>
        </b-button>
        <b-button
          v-if="!data.item.hidden"
          v-on:click="hideActivity(data.item.id)"
          variant="outline-secondary"
          class="btn-circle"
        >
          <b-icon-eye-fill></b-icon-eye-fill>

        </b-button>

        <b-button
          v-if="!data.item.completed && !data.item.started_at"
          v-on:click="startActivity(data.item.id)"
          variant="outline-success"
          class="btn-circle"
        >
          <b-icon-play-fill></b-icon-play-fill>
        </b-button>

        <b-button
          v-if="data.item.completed"
          v-on:click="viewResults(data.item.id)"
          variant="outline-primary"
          class="btn-circle"
        >
          <b-icon-trophy-fill></b-icon-trophy-fill>
        </b-button>

        <b-button
          v-if="!data.item.started_at"
          v-on:click="deleteActivity(data.item.id)"
          variant="outline-danger"
          class="btn-circle"
        >
          <b-icon-trash></b-icon-trash>
        </b-button>

        <b-popover target="link-button" title="Popover title" triggers="focus">Popover content</b-popover>
      </template>
    </b-table>

    <h2>Tous les quiz</h2>
    <b-table v-if="quizzes.loaded" striped hover :items="quizzes.data" :fields="quizzes.fields">
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
        <p
          class="mb-3"
        >Une nouvelle activité sera crée pour la classe sélectionnée. Il faudra encore la démarrer pour qu'elle soit visible aux étudiants.</p>
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
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      loaded: false,
      activity: {
        quiz_id: null,
        roster_id: null,
        duration: 600,
      },
      rosters: {
        data: [],
        active: 0,
        count: 0
      },
      activities: {
        fields: [
          {
            key: "id",
            label: "#",
            sortable: true,
          },
          {
            key: "quiz.name",
            label: "Quiz",
            sortable: true,
          },
          {
            key: "quiz.questions",
            label: "Questions",
            sortable: true,
          },
          {
            key: "roster.name",
            label: "Classe",
            sortable: true,
          },
          {
            key: "roster.teacher.name",
            label: "Enseignant",
            sortable: true,
          },
          {
            key: "duration",
            label: "Durée",
            sortable: true,
            formatter: "humanDuration",
          },
          {
            label: "Actions",
            key: "actions",
          },
        ],
        data: [],
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
     * Convert a duration in seconds into a human value
     */
    humanDuration(duration) {
      let minutes = parseInt(duration / 60, 10);
      let seconds = parseInt(duration % 60, 10);

      let text = "";
      if (minutes > 0) text += minutes + " minute";
      if (minutes > 1) text += "s";
      if (seconds > 0) text += " " + seconds + " seconde";
      if (seconds > 1) text += "s";

      return text;
    },
    activityClickHandler(record, index) {
      this.$router.push({ name: "Quiz", params: { activity_id: record.id } });
    },
    createActivity(quiz_id) {
      this.$root.$emit("bv::toggle::collapse", "new-activity");
      console.log("Abc", quiz_id);
    },
    hideActivity(activity_id) {
      axios.post(`/api/activities/${activity_id}/hide`).then((rep) => {
        this.loadActivities();
      });
    },
    showActivity(activity_id) {
      axios.post(`/api/activities/${activity_id}/show`).then((rep) => {
        this.loadActivities();
      });
    },
    deleteActivity(activity_id) {
      axios.post(`/api/activities/${activity_id}/delete`).then((rep) => {
        this.loadActivities();
      });
    },
    loadRosters() {
      axios.get("/api/user/rosters").then((rep) => {
        this.rosters.data = rep.data.data;
        this.rosters.count = rep.data.count;
        this.rosters.loaded = true;
      });
    },
    loadActivities() {
      axios.get("/api/user/activities").then((rep) => {
        this.activities.data = rep.data.data;
        this.activities.count = rep.data.count;
        this.activities.loaded = true;
      });

    },
    loadQuizzes() {
      axios.get("/api/quizzes").then((rep) => {
        this.quizzes.data = rep.data.data;
        this.quizzes.count = rep.data.count;
        this.quizzes.loaded = true;
      })
    },
  },
  mounted() {
    this.loadRosters();
    this.loadActivities();
    this.loadQuizzes();
  },
};
</script>
<style scoped>
.btn-circle {
  width: 30px;
  height: 30px;
  padding: 6px 0px;
  border-radius: 15px;
  text-align: center;
  font-size: 12px;
  line-height: 1.42857;
}

.btn-circle.btn-xl {
  width: 50px;
  height: 50px;
  padding: 5px 0px;
  border-radius: 25px;
  font-size: 22px;
  line-height: 1.33;
}
</style>
