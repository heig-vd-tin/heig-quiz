<template>
  <div>
    <navbar>
      <template v-slot:title>
        Mes Activités
      </template>
    </navbar>
    <div class="mt-4 container">
      <b-jumbotron v-if="!activities.count" header="Aucune Activité" lead="Aucune activité n'est actuellement disponible.">
        <p>Revenez plus tard ou patientez...</p>
      </b-jumbotron>
      <b-table
        v-else
        striped
        hover
        :items="activities.data"
        :fields="activities.fields"
        @row-clicked="activityClickHandler"
      >

        <template v-slot:cell(status)="data">
          <b-badge class="p-1" v-if="data.item.status == 'idle'" variant="info">En Attente</b-badge>
          <b-badge class="p-1" v-else-if="data.item.status == 'opened'" variant="primary">Ouvert</b-badge>
          <b-badge class="p-1" v-else-if="data.item.status == 'finished'" variant="success">Terminé</b-badge>
          <b-badge class="p-1" v-else-if="data.item.status == 'started'" variant="warning">Démarré</b-badge>
        </template>

        <template v-slot:cell(actions)="data">

          <!-- Go to the activity -->
          <b-button
            v-if="data.item.status == 'opened'"
            @click="startActivity(data.item.id)"
            variant="outline-success"
            class="btn-circle"
            v-b-popover.hover.top="'Démarrer l\'activité'"
          >
            <b-icon-play-fill></b-icon-play-fill>
          </b-button>

          <!-- View results -->
          <b-button
            v-if="data.item.status == 'finished'"
            :to="{name: 'results', params: { activity_id: data.item.id }}"
            variant="outline-primary"
            class="btn-circle"
            v-b-popover.hover.top="'Voir les résultats'"
          >
            <b-icon-trophy-fill></b-icon-trophy-fill>
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
      activities: {
        fields: [
          {
            key: "roster.name",
            label: "Classe",
            sortable: true,
            class: "d-none d-md-table-cell"
          },
          {
            key: "roster.teacher.name",
            label: "Enseignant",
            sortable: true,
          },
          {
            key: "quiz.name",
            label: "Quiz",
            sortable: true,
          },
          // {
          //   key: "quiz.questions",
          //   label: "Questions",
          //   sortable: true,
          // },
          // {
          //   key: "duration",
          //   label: "Durée",
          //   sortable: true,
          //   formatter: "humanDuration",
          // },
          {
            key: "created_at",
            label: "Créé le",
            sortable: true,
            formatter: "timeAgo",
          },
          {
            key: "mark",
            label: "Note",
            sortable: true,
          },
          {
            key: "status",
            label: "Status",
            sortable: true
          },
          {
            label: "Actions",
            key: "actions",
          },
        ],
        data: [],
        count: 0
      }
    };
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
      this.$router.push({ name: "quiz", params: { activity_id: record.id } });
    },
    loadActivities(roster_id) {
      axios
        .get("/api/user/activities")
        .then((rep) => {
          this.activities.data = rep.data.data;
          this.activities.count = rep.data.count;
          console.log("Activities loaded")
        });
    },
  },
  mounted() {
    this.loadActivities();

    window.Echo.private("activity")
    .listen("ActivityUpdated", (e) => {
      this.loadActivities();
      console.log("Activity Updated", e);
    })
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
