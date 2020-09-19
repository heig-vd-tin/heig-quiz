<template>    
    <div>
        <div v-if="loaded" class="row justify-content-center">
            <div> {{ title }} </div>
            <b-table striped hover :items="activities" :fields="fields" @row-clicked="activityClickHandler"></b-table>
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

                fields: [
                    { 
                        key: 'id',
                        label: 'ID'
                    },
                    { 
                        key: 'quiz.name',
                        label: 'Quiz'
                    },
                    { 
                         key: 'classroom.number',
                         label: 'Classroom'
                    },
                    { 
                        key : 'teacher.name',
                        label: 'Teacher'
                    },
                    {   
                        key: 'duration',
                        label: 'Duration'
                    }
                ],
                activities: null
            }
        },

        methods: {
            loadActivities: function(){
                axios
                    .get('api/activity')
                    .then((rep) => {
                        this.activities = rep.data
                        this.loaded = true
                    })
            },

            activityClickHandler(record, index) {
                this.$router.push({ name: 'Quiz', params: { activity_id: record.id } })
            }
        },

        mounted() {
                this.loadActivities()
        }
    }
</script>