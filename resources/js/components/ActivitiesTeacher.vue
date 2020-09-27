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
        striped
        hover
        :items="activities.data"
        :fields="activities.fields"
        @row-clicked="activityClickHandler"
      >
        <template v-slot:cell(actions)="data">

          <!-- Show the activity -->
          <b-button
            v-if="data.item.status == 'finished' && data.item.hidden"
            @click="showActivity(data.item.id)"
            variant="outline-primary"
            class="btn-circle"
            v-b-popover.hover.top="'Rendre visible l\'activité'"
          >
            <b-icon-eye-slash></b-icon-eye-slash>
          </b-button>

          <!-- Hide the activity -->
          <b-button
            v-if="data.item.status == 'finished' && !data.item.hidden"
            @click="hideActivity(data.item.id)"
            variant="outline-secondary"
            class="btn-circle"
            v-b-popover.hover.top="'Cacher l\'activité aux étudiants'"
          >
            <b-icon-eye-fill></b-icon-eye-fill>
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
          <!-- Delete an activity -->
          <b-button
            v-if="data.item.status == 'idle'"
            v-b-popover.hover.top="'Supprimer l\'activité'"
            variant="outline-danger"
            class="btn-circle"
            @click="deleteActivity(data.item.id)"
          >
            <b-icon-trash></b-icon-trash>
          </b-button>
          <!-- Open an activity -->
          <b-button
            v-if="data.item.status == 'idle'"
            @click="openActivity(data.item.id)"
            variant="outline-success"
            class="btn-circle"
            v-b-popover.hover.top="'Ouvrir l\'activité pour participation'"
          >
            <b-icon-broadcast></b-icon-broadcast>
          </b-button>

          <!-- Start the activity -->
          <b-button
            v-if="data.item.status == 'opened'"
            @click="startActivity(data.item.id)"
            variant="outline-success"
            class="btn-circle"
            v-b-popover.hover.top="'Démarrer l\'activité'"
          >
            <b-icon-play-fill></b-icon-play-fill>
          </b-button>

          <!-- Open an activity -->
          <b-button
            v-if="data.item.status == 'opened'"
            @click="closeActivity(data.item.id)"
            variant="success"
            class="btn-circle running"
            v-b-popover.hover.top="'Activité disponible pour les étudiants. Cliquez pour interrompre.'"
          >
            <b-icon-broadcast></b-icon-broadcast>
          </b-button>

          <span v-if="data.item.status == 'opened'">
          {{ activities.presence.here }} / {{ data.item.roster.students }}
          </span>
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
            key: "updated_at",
            label: "Modifié",
            sortable: true,
            formatter: "timeAgo",
          },
          {
            label: "Actions",
            key: "actions",
          },
        ],
        data: [],
        presence: {
          here: 0,
          users: []
        }
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
      this.$router.push({ name: "quiz", params: { activity_id: record.id } });
    },
    openActivity(activity_id) {
      axios.post(`/api/activities/${activity_id}/open`).then((rep) => {
      });
      this.listenActivity(activity_id)
    },
    closeActivity(activity_id) {
      axios.post(`/api/activities/${activity_id}/close`).then((rep) => {
      });
      this.leaveActivity(activity_id);
    },
    hideActivity(activity_id) {
      axios.post(`/api/activities/${activity_id}/hide`).then((rep) => {
      });
    },
    showActivity(activity_id) {
      axios.post(`/api/activities/${activity_id}/show`).then((rep) => {
      });
    },
    startActivity(activity_id) {
      axios.post(`/api/activities/${activity_id}/start`).then((rep) => {
      });
    },
    leaveActivity(activity_id) {
      window.Echo.leave(`activity.${activity_id}`);
    },
    listenActivity(activity_id) {
      console.log("Activity Listen ", activity_id)
      this.leaveActivity(activity_id);
      window.Echo.join(`activity.${activity_id}`)
      .here((users) => {
        this.activities.presence.users = users;
        let students = 0;
        users.forEach(student => {
          if (student.type == 'student')
            students++;
        })

        this.activities.presence.here = students;
        console.log("Here: ", users);
      })
      .joining((user) => {
        if (user.type == 'student')
          this.activities.presence.here++;
        console.log(user);
      })
      .leaving((user) => {
        if (user.type == 'student')
          this.activities.presence.here--;
        console.log(user);
      })
    },
    deleteActivity(activity_id) {
      this.$bvModal.msgBoxConfirm('Voulez-vous vraiment supprimer cette activité ?', {
        title: 'Supprimer ?',
        okTitle: 'Supprimer',
        cancelTitle: 'Annuler',
        okVariant: 'danger'
      })
        .then(value => {
          if (value)
            axios.post(`/api/activities/${activity_id}/delete`).then((rep) => {

            });
        })
    },
    loadRosters() {
      axios.get("/api/user/rosters").then(({data : rosters}) => {
        this.rosters.data = rosters.data;
        this.rosters.count = rosters.count;
      });
    },
    loadActivities(roster_id) {
      axios
        .get("/api/user/activities", {
          params: {
            roster_id: roster_id,
          },
        })
        .then(({data : activities}) => {
          this.activities.data = activities.data;
          this.activities.count = activities.count;
          this.activities.data.forEach(activity => {
            if (activity.status == 'opened')
              this.listenActivity(activity.id)
          })
        });
    },

  },
  mounted() {
    this.loadRosters();
    this.loadActivities();

    window.Echo.private("activity").listen("ActivityUpdated", (e) => {
      this.loadActivities();
    });
  },
};
</script>
