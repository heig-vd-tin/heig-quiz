<template>
    <div v-if="loaded">
        C est le comp
        <div class="row justify-content-center">
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

        mounted() {
            axios
                .get('api/activity')
                .then((rep) => {
                    Vue.set
                    this.activities = rep.data
                    this.loaded = true
                })
                //this.loaded = true
        }
    }
</script>