<template>
  <div>
    <navbar>
      <template v-slot:title>
        Activités
      </template>
      <b-nav-item to="quizzes"><b-icon-dice-5/> Quizzes</b-nav-item>
      <b-nav-item to="sandbox"><b-icon-bucket/> Bac à sable</b-nav-item>
      <b-nav-text>|</b-nav-text>

      <!-- Select roster -->
      <b-nav-item-dropdown :text="current_roster != null ? current_roster.name : 'Toutes les classes'">
        <b-dropdown-item @click="rosterChange(null)" :active="current_roster == null">Toutes les classes</b-dropdown-item>
        <div class="dropdown-divider"></div>
        <b-dropdown-item
          @click="rosterChange(roster)"
          v-for="roster in rosters.data"
          v-model="current_roster"
          :key="roster.id"
          :active="current_roster != null && roster.id == current_roster.id"
          ><b-icon-people />
          {{ roster.name }}
          <b-spinner
            v-if="roster.has_running_activities"
            type="grow"
            small
          ></b-spinner>
        </b-dropdown-item>
      </b-nav-item-dropdown>

    </navbar>
    <div class="mt-4 container">
      <h2>Activités de {{ current_roster != null ? current_roster.name : 'toutes les classes' }}</h2>
      <p>
        Les activités en cours et passées sont accessibles des étudiants
        concernés. En cachant une activité, les étudiants ne pourront plus y
        accéder.
      </p>

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
            v-if="current_roster != null && !data.item.completed && !data.item.started_at"
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
            v-on:click="openActivity(data.item.id)"
            variant="outline-success"
            class="btn-circle running"
          >
            <b-icon-broadcast></b-icon-broadcast>
          </b-button>
          <b-button
            v-if="!data.item.started_at"
            v-on:click="deleteActivity(data.item.id)"
            variant="outline-danger"
            class="btn-circle"
          >
            <b-icon-trash></b-icon-trash>
          </b-button>
        </template>
      </b-table>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import TimeAgo from "javascript-time-ago";
import fr from "javascript-time-ago/locale/fr";
TimeAgo.addLocale(fr);
const timeAgo = new TimeAgo("fr-CH");

export default {
  data() {
    return {
      loaded: false,
      activity: {
        quiz_id: null,
        roster_id: null,
        duration: 600,
      },
      current_roster: null,
      rosters: {
        data: [],
        active: 0,
        count: 0,
      },
      activities: {
        fields: [
          // {
          //   key: "id",
          //   label: "#",
          //   sortable: true,
          // },
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
            class: "d-none d-md-table-cell"
          },
          {
            key: "duration",
            label: "Durée",
            sortable: true,
            formatter: "humanDuration",
          },
          {
            key: "created_at",
            label: "Créé le",
            sortable: true,
            formatter: "timeAgo",
          },
          // {
          //   key: "roster.teacher.name",
          //   label: "Enseignant",
          //   sortable: true,
          // },
          {
            label: "Actions",
            key: "actions",
          },
        ],
        data: [],
        loaded: false,
      }
    };
  },
  watch: {
    current_roster(roster) {
      let id = roster != null ? roster.id : null;

      // Hide roster column if one is selected
      let field = this.activities.fields.find(field => field.key == 'roster.name')
      field.class = id ? "d-none" : "";

      this.loadActivities(id);
    },
  },
  methods: {
    rosterChange(roster) {
      console.log("RosterChange")
      this.current_roster = roster;
    },
    timeAgo(date) {
      return timeAgo.format(Date.parse(date));
    },
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
    loadActivities(roster_id) {
      axios
        .get("/api/user/activities", {
          params: {
            roster_id: roster_id,
          },
        })
        .then((rep) => {
          this.activities.data = rep.data.data;
          this.activities.count = rep.data.count;
          this.activities.loaded = true;
        });
    },

  },
  mounted() {
    this.loadRosters();
    this.loadActivities();

    window.Echo.private("activity").listen("ActivityCreated", (e) => {
      console.log("Activity Created", e);
    });
  },
};
</script>
<style scoped>
.running {
  animation: running 2s infinite;
}

@keyframes running {
	0% {
		transform: scale(0.95);
		box-shadow: 0 0 0 0 rgba(57, 150, 20, 0.925);
	}

	70% {
		transform: scale(1);
		box-shadow: 0 0 0 15px rgba(0, 0, 0, 0);
	}

	100% {
		transform: scale(0.95);
		box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
	}
}
</style>
