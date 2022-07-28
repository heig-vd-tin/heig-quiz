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
      <span v-if="type != 'code'">
        <b-form-input v-model="answer" :size="width" :readonly="true">
        </b-form-input>
        <br />
        <p>
          Nouvelle validation
        </p>
        <b-form-input v-model="new_validation" :size="width">
        </b-form-input>

        <b-button class="mt-3" variant="success" @click="updateAnswer">Mise à jour</b-button>
      </span>
        
      <span v-else>
        <codemirror ref="myCm"
              v-model="answer"
              :value="answer" 
              :options="cmOptions"
              >
        </codemirror>
        <br />
        <p>
          Nouvelle validation
        </p>
        <b-form-input v-model="new_validation" :size="width">
        </b-form-input>

        <b-button  class="mt-3" variant="success" @click="updateAnswer">Mise à jour</b-button>
      </span>
      
      
    </b-form-group>
  </div>
</template>

<script>
import MarkdownItVue from 'markdown-it-vue'

// require component
import { codemirror } from 'vue-codemirror'

// require styles
import 'codemirror/lib/codemirror.css'
// language js
import 'codemirror/mode/javascript/javascript.js'
// theme css
import 'codemirror/theme/base16-dark.css'

export default {
  data() {
    return {
      cmOptions: {
        // codemirror options
        tabSize: 2,
        mode: 'text/javascript',
        theme: 'base16-dark',
        lineNumbers: true,
        line: true,
        readOnly: true,
      }
    }
  },

  components: {
    MarkdownItVue,
    codemirror,
  },

  props: {
    student_id: Number,
    activity_id: Number,
    question_id: Number,
    answer: String,
    width: { type: String, default: '30' },
    is_correct: { type: [Boolean, null], required: false },
    new_validation: String,
    points: Number,
    id: Number,
    type: String
  },

  methods: {
    updateAnswer: function () {
      axios({
        method: 'post',
        url: '/api/activities/updateAnswer',
        data: {
          'student_id': this.student_id,
          'activity_id': this.activity_id,
          'question_id': this.question_id,
          'id': this.id,
          'points': this.points,
          'new_validation': this.new_validation
        }
      })
        .then((reponse) => {
          console.log("answer saved");
          if (id == -1) {
            this.id = reponse.data.id;
          }
        })
        .catch(function (erreur) {
          console.log(erreur)
        });
    }
  }
};
</script>