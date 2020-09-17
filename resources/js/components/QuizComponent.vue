<template>    
    <div>
        <div v-if="loaded" class="row justify-content-center">
            <b-card :title="question.name" class="m-4 p-2">
                <b-card-text>
                    {{ question.content }}
                </b-card-text>
                <b-card-text>
                    Réponse en cours : {{ question.current_answer }}
                </b-card-text>
                <b-button v-if="questionNumber > 1" variant="primary" @click="getPrevious">Previous</b-button>                
                <b-button v-if="questionNumber < TotalQuestion" variant="primary" @click="getNext">Next</b-button>
            </b-card>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'

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
                    .get('api/activity/' + this.activity_id + '/' + num)
                    .then((rep) => {
                        this.question = rep.data
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