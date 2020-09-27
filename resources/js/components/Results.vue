<template>
  <div>
    <navbar>
      <template v-slot:title>
        Activités
      </template>
      <b-nav-item to="activities"><b-icon-easel/> Activités</b-nav-item>
      <b-nav-item to="quizzes"><b-icon-dice-5/> Quizzes</b-nav-item>
      <b-nav-item to="sandbox"><b-icon-bucket/> Bac à sable</b-nav-item>
    </navbar>
    <div class="mt-4 container">
      <h2>Résultats</h2>
    </div>
  </div>
</template>

<script>

export default {
  data() {
    return {
      results: {}
    }
  },
  props: {
    activity_id: null,
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
    loadQuiz() {
      axios
        .get(`/api/user/activities/${this.activity_id}/results`)
        .then((rep) => {
          this.results.data = rep.data.data;
          this.results.count = rep.data.count;
        });
    },

  },
  mounted() {
    this.loadQuiz();
  },
};
</script>
