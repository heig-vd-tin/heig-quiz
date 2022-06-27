<template>
  <div>

    <b-row class="my-1">
      <b-col sm="2">
        <label>Points de l'étudiant : </label>
      </b-col>
      <b-col sm="3">
        <b-form-input v-model="points" type="number"></b-form-input>
      </b-col>
    </b-row>
    <br />
    <b-form-group>

      <p>
        Réponse de l'étudiants
      </p>
      <b-form-input v-model="answer" :size="width" :readonly="true">
      </b-form-input>
      <br />
      <p>
        Nouvelle validation
      </p>
      <b-form-input v-model="new_validation" :size="width">
      </b-form-input>

      <b-button class="mt-3" variant="success" @click="updateAnswer">Mise à jour</b-button>
    </b-form-group>
  </div>
</template>

<script>
import MarkdownItVue from 'markdown-it-vue'

export default {
  components: {
    MarkdownItVue
  },

  props: {
    answer: String,
    width: { type: String, default: '30' },
    is_correct: { type: [Boolean, null], required: false },
    new_validation: String,
    points: Number,
    id: Number
  },

  methods: {
    updateAnswer: function () {
      axios({
        method: 'post',
        url: 'api/activities/updateAnswer',
        data: {
          'id': this.id,
          'points': this.points,
          'new_validation': this.new_validation,
          'is_correct': this.is_correct
        }
      })
        .then(function (reponse) {
          console.log("answer saved")
        })
        .catch(function (erreur) {
          console.log(erreur)
        });
    }
  }
};
</script>