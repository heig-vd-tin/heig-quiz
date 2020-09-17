<template>    
    <div>
        <b-button variant="success" v-on:click="testclick"></b-button>
        <div v-if="loaded" class="row justify-content-center">
            {{ title }}
            <div v-for="act in activities" :key="act.id" class="card">
                {{ act.id }}
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        data() {
            return {
                title: "Activities",
                loaded: false,
                activities: null
            }
        },

        methods: {
            testclick: function(){
                console.log("Click load")
                axios
                    .get('api/activity')
                    .then((rep) => {
                        Vue.set
                        this.activities = rep.data
                        this.loaded = true
                    })
            }
        },

        mounted() {
                this.testclick()
        }
    }
</script>