<template>
  <div>
    <h2>Tous les quiz</h2>

    <!-- Quizzes list -->
    <b-table :items="quizzes" :fields="fields" striped hover>
      <template v-slot:cell(name)="data">
        {{ data.item.name }}
        <br />
        <b-badge class="mr-1 keyword" v-for="keyword in data.item.keywords" v-bind:key="keyword.id" variant="secondary">
          {{ keyword }}
        </b-badge>
      </template>

      <template v-slot:cell(difficulty)="data">
        <b-badge v-if="data.item.difficulty <= 1" variant="success"><div class="difficulty-easy" /></b-badge>
        <b-badge v-else-if="data.item.difficulty <= 2" variant="warning"><div class="difficulty-medium" /></b-badge>
        <b-badge v-else-if="data.item.difficulty <= 3" variant="danger"><div class="difficulty-hard" /></b-badge>
        <b-badge v-else-if="data.item.difficulty <= 4" variant="dark"><div class="difficulty-insane" /></b-badge>
      </template>

      <template v-slot:cell(actions)="data">
        <b-button
          v-on:click="displayCreateActivityForm(data.item.id)"
          variant="outline-primary"
          class="btn-circle pulse-primary"
          v-b-popover.hover.left="'Créer une activité à partir de ce quiz'"
        >
          <b-icon-plus />
        </b-button>
      </template>
    </b-table>

    <!-- New Activity from Quiz -->
    <b-modal
      @ok="createActivity"
      id="new-activity-modal"
      title="Nouvelle activité"
      cancel-title="Annuler"
      ok-title="Créer"
      centered
    >
      <p class="mb-3">
        Une nouvelle activité sera crée pour la classe sélectionnée. Il faudra encore la démarrer pour qu'elle soit
        visible aux étudiants.
      </p>
      <b-form ref="createActivityForm" @submit.stop.prevent="handleSubmit">
        <b-form-group id="input-roster" label="Classe" label-for="roster">
          <b-form-select id="input-roster" v-model="form.roster_id" required>
            <b-form-select-option v-for="roster in rosters" v-bind:key="roster.id" :value="roster.id">
              {{ roster.name }}
            </b-form-select-option>
          </b-form-select>
        </b-form-group>
        <b-form-group
          id="input-group-1"
          label="Durée"
          label-for="duration"
          description="Durée du quiz"
          invalid-feedback="Durée requise"
        >
          <b-row>
            <b-col>
              <b-form-input
                id="input-duration"
                v-model="form.duration.minutes"
                type="number"
                required
                placeholder="min"
              ></b-form-input>
            </b-col>
            <b-col>
              <b-form-input
                id="input-duration"
                v-model="form.duration.seconds"
                type="number"
                required
                placeholder="sec"
              ></b-form-input>
            </b-col>
          </b-row>
        </b-form-group>

        <b-form-checkbox id="shuffleQuestions" v-model="form.shuffle_questions" name="shuffleQuestions">
          Mélanger les questions
        </b-form-checkbox>
        <b-form-checkbox id="shufflePropositions" v-model="form.shuffle_propositions" name="shufflePropositions">
          Mélanger les propositions multiples
        </b-form-checkbox>
      </b-form>
    </b-modal>
  </div>
</template>
<script>
import { mapActions, mapGetters, mapState } from 'vuex';

export default {
  data() {
    return {
      form: {
        duration: {
          minutes: 5,
          seconds: 0
        },
        roster_id: null,
        quiz_id: null,
        shuffle_questions: false,
        shuffle_propositions: false
      },
      fields: [
        { key: 'id', label: '#', sortable: true },
        { key: 'name', label: 'Quiz', sortable: true },
        { key: 'questions', label: 'Questions', sortable: true },
        //{ key: 'taken_times', label: 'Utilisé', sortable: true },
        { key: 'owner.name', label: 'Créateur', sortable: true },
        { key: 'difficulty', label: 'Difficulté', sortable: true },
        { key: 'actions', label: 'Actions' }
      ]
    };
  },
  computed: {
    ...mapState('quiz', {'quizzes': state => state.data}),
    ...mapState('roster', {'rosters': state => state.data})
  },
  methods: {
    displayCreateActivityForm(quiz_id) {
      this.$bvModal.show('new-activity-modal');
      this.form.quiz_id = quiz_id;
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
      if (!this.checkFormValidity()) return;
      form.duration = form.duration.minutes * 60 + form.duration.seconds;
      this.create(this.form).then(response => {
        this.$router.push('activities');
        this.$bvModal.hide('new-activity-modal');
      });
    },
    ...mapActions('activity', ['create'])
  }
};
</script>
