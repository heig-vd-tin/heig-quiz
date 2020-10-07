<template>
  <div>
    <navbar>
      <template v-slot:title>Bac à sable</template>
      <b-nav-item to="/quiz/activities">
        <b-icon-easel />
        Activités
      </b-nav-item>
      <b-nav-item to="/quiz/quizzes">
        <b-icon-dice-5 />
        Quizzes
      </b-nav-item>
      <b-nav-item to="/quiz/sandbox">
        <b-icon-bucket />
        Bac à sable
      </b-nav-item>
    </navbar>

    <div class="container">
      <prism-editor class="my-editor" v-model="code" :highlight="highlighter" line-numbers></prism-editor>
      <b-button @click="build" variant="primary">Run</b-button>
    </div>
    <b-modal id="modal-lg" centered size="lg" title="Résultat" button-size="sm">{{result.stdout}}</b-modal>
  </div>
</template>
<script>
import Sub from './sub';
import VueCountdown from '@chenfengyuan/vue-countdown';
import Footer from '../../layouts/footer';

// import Prism Editor
import { PrismEditor } from 'vue-prism-editor';
import 'vue-prism-editor/dist/prismeditor.min.css'; // import the styles somewhere

// import highlighting library (you can use any library you want just return html string)
import { highlight, languages } from 'prismjs/components/prism-core';
import 'prismjs/components/prism-clike';
import 'prismjs/components/prism-javascript';
import 'prismjs/themes/prism-tomorrow.css'; // import syntax highlighting styles
import axios from "axios";
export default {
  components: {
    foobar: Sub,
    appFooter: Footer,
    countdown: VueCountdown,
    PrismEditor,
  },
  data() {
    return {
      shared: 'Content',
      value: 600 * 1000,
      code: `#include <stdio.h>

int main() {
    printf("hello, world!\\n");
}
`,
      result: {}
    };
  },
  methods: {
    build() {
      axios({
        method: 'post',
        url: "/api/build/c",
        data: { code: this.code }
      }).then((rep) => {
        this.result = rep.data
        console.log(rep.data);
        this.$bvModal.show('modal-lg')
      });
    },
    highlighter(code) {
      return highlight(code, languages.clike); // languages.<insert language> to return html with markup
    },
  },
  mounted() {
    //console.log(this.$store.state.count)
  }
};
</script>
<style>
  /* required class */
  .my-editor {
    /* we dont use `language-` classes anymore so thats why we need to add background and text color manually */
    /*background: #2d2d2d;*/
    color: #ccc;

    /* you must provide font-family font-size line-height. Example: */
    font-family: Fira code, Fira Mono, Consolas, Menlo, Courier, monospace;
    font-size: 14px;
    line-height: 1.5;
    padding: 5px;
  }

  /* optional class for removing the outline */
  .prism-editor__textarea:focus {
    outline: none;
  }
</style>
