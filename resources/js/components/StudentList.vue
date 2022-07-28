<template>
  <div>
    <h2 v-if="activity.quiz">
      Quiz n°{{ activity.quiz.id }} {{ activity.quiz.name }}
    </h2>
    <h2>Liste des étudiants</h2>


    <!-- student list -->
    <b-table :items="studentList" :fields="fields" striped hover>
      <template v-slot:cell(id)="data">
        {{ data.item.id }}
      </template>

      <template v-slot:cell(name)="data">
        {{data.item.user.firstname}} {{data.item.user.lastname}}
      </template>

      <template v-slot:cell(action)="data">
        <b-button
          :to="`/activities/${activity.id}/studentResult/${data.item.id}`"
          variant="outline-primary"
          class="btn-circle"
          v-b-popover.hover.top="`Examen de ${data.item.user.firstname} ${data.item.user.lastname}`"
        >
          <b-icon-file-fill />
        </b-button>
      </template>
    </b-table>


  </div>
</template>

<script>

import MarkdownItVue from 'markdown-it-vue'
import axios from 'axios';

export default {
  components: {
    MarkdownItVue
  },
  data() {
    return {
      activity: {},
      studentList:[],
      fields: [
        { key: 'id', label: '#', sortable: true },
        { key: 'name', label: 'Étudiant', sortable: true },
        { key: 'action', label: 'Examen' }
      ]
    };
  },
  props: {
    activity_id: null
  },
  
  methods: {

    loadActivity() {
      axios.get(`/api/activities/${this.activity_id}`).then(({ data: activity }) => {
        this.activity = activity;
      });
    },

    loadStudents() {
      axios.get(`/api/activities/${this.activity_id}/studentList`).then(({data: studentList}) => {
        this.studentList = studentList;
      });
    }
    
  },
  mounted() {
    this.loadActivity();
    this.loadStudents();
  }
};
</script>
<style scoped>
.opacity-50 {
  opacity: 0.5;
}

.pb {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  border-radius: 0;
  height: 0.5rem;
}
</style>
