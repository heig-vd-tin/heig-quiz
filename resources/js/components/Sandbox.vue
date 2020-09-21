<template>
  <div>
      <div class="progress mb-2">
        <div
          class="progress-bar bg-dark progress-bar"
          role="progressbar"
          :style="'width: '+ percent +'%'"
          :aria-valuenow="percent"
          aria-valuemin="0"
          aria-valuemax="100"
        >{{data.id}}/{{data.total}}</div>
      </div>
    <b-card border-variant="dark" :title="data.id + '. ' + data.title" align="left">
      <b-card-text v-html="htmlContent"></b-card-text>

      <b-list-group class="mt-3 mb-4">
        <b-list-group-item v-for="(proposition, index) in propositions" :key="index">
          <b-row class="text-center align-middle">
            <b-col cols="1">
              <b-button :pressed.sync="myToggle[index]" variant="outline-secondary" class="btn-circle">{{letter(index)}}</b-button>
            </b-col>
            <b-col class="text-left align-middle" v-html="proposition"></b-col>
          </b-row>
        </b-list-group-item>
      </b-list-group>

      <b-container>
        <b-row class="text-center align-middle">
          <b-col>
            <b-button pill variant="outline-secondary">Précédent</b-button>
          </b-col>
          <b-col cols="5">
            <h2 class="display-3 time" v-bind:class="{'text-danger' : timer < 30}">{{countdown}}</h2>
          </b-col>
          <b-col>
            <b-button pill variant="outline-secondary">Suivant</b-button>
          </b-col>
        </b-row>
      </b-container>


    </b-card>
  </div>
</template>

<script>
import axios from "axios";
var md = require("markdown-it")();
var mk = require("@iktakahiro/markdown-it-katex");
md.use(mk);

export default {
  data() {
    return {
      myToggle: [false, false, false, false],

      propositions: [
        "Dion Cassius chante avec René",
        "Dion Cassius n'affiche aucune sérénité",
        "Dion Cassius adhère pleinement",
        "La quatrième proposition est certainement la bonne"
      ],

      loaded: false,
      question: null,
      questionNumber: 1,
      TotalQuestion: 3, //Todo(tmz) from activity
      activity_id: 1, //Todo(tmz) from activity
      countdown: "- : -",
      timer: 20,
      htmlContent: '',
      data: {
        id: 3,
        total: 10,
        remaining_seconds: 500,
        finishes_at: "2020-09-21T10:27",
        title: "Question",
        type: 'multiple-choice',
        timer: 0,
        content: `
Témoin de la chute de [Jérusalem](https://en.wikipedia.org/wiki/Jerusalem), **Philostrate** n’hésite pas à écrire
que :

>Ce peuple s’était dès longtemps insurgé non contre les Romains, mais contre l’humanité en général. Des hommes qui ont imaginé une vie insociable, qui ne partagent avec leurs semblables ni la table, ni les libations, ni les prières, ni les sacrifices, sont plus éloignés de nous que Suse ou Bactres ou que l’Inde plus reculée encore...

Quelle eut été la portée de son argument sur **Dion Cassius** ?

## A
Dion Cassius chante avec René
## B
Dion Cassius n'affiche aucune sérénité
## C
Dion Cassius adhère pleinement
## D
La quatrième proposition est certainement la bonn
`,
      }
    };
  },
  computed: {
    percent() {
      return this.data.id / this.data.total * 100;
    },
    content() {
      return md.render(this.data.content)
    }
  },
  methods: {
    /**
     * Extract the propositions in a multiple choice Markdown content.
     */
    extractPropositions(markdown) {
      let state = ''
      let ignore = []
      let propositions = {}
      let proposition = 0
      let level = 0
      let tokens = md.parse(markdown)
      // Parse tokens
      tokens.forEach((token, index) => {
        // Looks for propositions
        if (token.type == 'heading_open' && token.tag == 'h2') {
          state = 'check-inline'
          ignore.push(index)
          return
        }

        // Capture proposition number
        if (state == 'check-inline' && token.type == 'inline') {
          if (/^[A-Z]$/.test(token.content)) {
            ignore.push(index)
            state = 'capture-content'
            proposition++
            propositions[proposition] = []
          } else {
            ignore.pop()
            state = 'looking-for-proposition'
          }
          return
        }

        // Capture proposition content
        if (state == 'capture-content') {
          ignore.push(index)
          propositions[proposition].push(token)

          if (token.type == 'paragraph-open') {
            level++
          }

          if (token.type == 'paragraph-close') {
            level--
            if (level == 0)
              state == 'looking-for-proposition'
              return
          }
        }
      })

      function* output() {
        for (const [index, token] of tokens.entries()) {
          if (ignore.includes(index)) continue;
          yield token;
        }
      };

      this.htmlContent = md.renderer.render(Array.from(output()), md.options)
      console.log(propositions)
      for (const [key, value] of Object.entries(propositions)) {
          this.propositions[key] = md.renderer.render(value, md.options)
      }

    },
    /**
     * Get the proposition number from the proposition id.
     * 0 -> A, 1 -> B...
     */
    letter(i) {
      let letter = String.fromCharCode("A".charCodeAt(0) + i);
      return i < 26 ? letter : 'A' + letter;
    },
    /**
     * Start countdown timer from the received duration
     */
    startTimer(duration) {
      this.timer = duration
      let timer = setInterval(() => {
        let minutes = parseInt(this.timer / 60, 10);
        let seconds = parseInt(this.timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        this.countdown = minutes + ":" + seconds;

        if (--this.timer <= 0) clearInterval(timer);
      }, 1000);
    },
  },
  mounted() {
    this.startTimer(this.data.remaining_seconds);
    this.extractPropositions(this.data.content);
  },
};
</script>
<style scoped>
.time {
  font-size: 2.5rem;
}

.btn-circle {
  width: 38px;
  height: 38px;
  text-align: center;
  padding: 0;
  border-radius: 50%;
  font-size: 1.3rem;
  font-weight: 500;
}
</style>
