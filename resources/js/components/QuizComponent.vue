<template>
    <div>
        <div v-if="loaded" class="row justify-content-center">
            <b-card :title="question.name" class="m-4 p-2">
                <b-card-text>
                    <span v-html="question.content"></span>
                </b-card-text>
                <b-card-text>
                    Réponse en cours : {{ question.current_answer }}
                        <b-form-checkbox
      id="checkbox-1"
      v-model="status"
      name="checkbox-1"
      value="accepted"
      unchecked-value="not_accepted"
    >
      I accept the terms and use
    </b-form-checkbox>
                </b-card-text>
                <b-button v-if="questionNumber > 1" variant="primary" @click="getPrevious">Previous</b-button>
                <b-button v-if="questionNumber < TotalQuestion" variant="primary" @click="getNext">Next</b-button>
            </b-card>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    var md = require('markdown-it')();
    var mk = require('@iktakahiro/markdown-it-katex');
    md.use(mk);

    export default {

        data() {
            return {
                title: "Question",
                loaded: false,
                question: null,
                questionNumber: 1,
                TotalQuestion: 3, //Todo(tmz) from activity
                activity_id: 1 //Todo(tmz) from activity
            }
        },

        methods: {
            getQuestion: function(num){
                axios
                    .get('http://127.0.0.1:8000/api/quizzes/1/questions/4')
                    .then((rep) => {
                        this.question = rep.data
                        this.question.content = md.render(this.question.content)
                        this.loaded = true
                        this.questionNumber = num
                    })
            },

            getNext(){
                this.getQuestion(this.questionNumber + 1)
            },

            getPrevious(){
                this.getQuestion(this.questionNumber - 1)
            }
        },

        mounted() {
                this.getQuestion(1)
        }
    }
</script>
