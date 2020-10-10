<template>
  <div>
    <b-button variant="primary" to="/questions/create">Nouvelle Question</b-button>
    <!-- Questions list -->
    <b-table pt-2 striped hover :items="questions.data" :fields="questions.fields">
      <template v-slot:cell(name)="data">
        {{ data.item.name }}
        <br />
        <b-badge class="mr-1 keyword" v-for="keyword in data.item.keywords" v-bind:key="keyword" variant="secondary">
          {{ keyword.name }}
        </b-badge>
      </template>

      <template v-slot:cell(difficulty)="data">
        <b-badge v-if="data.item.difficulty == 'easy'" variant="success"><div class="difficulty-easy" /></b-badge>
        <b-badge v-else-if="data.item.difficulty == 'medium'" variant="warning">
          <div class="difficulty-medium" />
        </b-badge>
        <b-badge v-else-if="data.item.difficulty == 'hard'" variant="danger"><div class="difficulty-hard" /></b-badge>
        <b-badge v-else-if="data.item.difficulty == 'insane'" variant="dark"><div class="difficulty-insane" /></b-badge>
      </template>
    </b-table>
  </div>
</template>
<script>
export default {
  data() {
    return {
      questions: {
        fields: [
          {
            key: 'id',
            label: '#',
            sortable: true
          },
          {
            key: 'name',
            label: 'Question',
            sortable: true
          },
          {
            key: 'type',
            label: 'Type',
            sortable: true
          },
          {
            key: 'difficulty',
            label: 'Difficulté',
            sortable: true
          },
          {
            label: 'Créé le',
            key: 'created_at'
          }
        ],
        data: [],
        loaded: false
      }
    };
  },
  methods: {
    loadQuestions() {
      axios.get('/api/questions').then(rep => {
        this.questions.data = rep.data.data;
        this.questions.count = rep.data.count;
        this.questions.loaded = true;
      });
    }
  },
  mounted() {
    this.loadQuestions();
  }
};
</script>
<style scoped>
.keyword {
  font-size: 70%;
  padding: 4px;
  text-transform: lowercase;
}
</style>
