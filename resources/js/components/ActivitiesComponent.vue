<template>
    <div>
        <h2>Activités</h2>
        <p>Les activités en cours et passées sont accessibles des étudiants concernés. En cachant une activité, les étudiants ne pourront plus y accéder.</p>

        <b-table v-if="activities.loaded" striped hover :items="activities.data" :fields="activities.fields" @row-clicked="activityClickHandler">
            <template v-slot:cell(duration)="data">
                {{ data.item.duration }} minutes
            </template>

            <template v-slot:cell(actions)="data">
                <font-awesome-icon v-b-tooltip.hover title="Tooltip directive content" icon="user-secret" />
                <font-awesome-icon icon="eye" />
                <font-awesome-icon icon="eye-slash" />
                <font-awesome-icon icon="trash-alt" />
                <font-awesome-icon icon="poll" />
                <b-button id="link-button" href="#" tabindex="0">
                <font-awesome-icon icon="stop" />
                </b-button>

                <b-popover target="link-button" title="Popover title" triggers="focus">
                Popover content
                </b-popover>

            </template>
        </b-table>

        <h2>Tous les quiz</h2>
        <!-- <b-table v-if="quizzes.loaded" striped hover :items="quizzes.data" :fields="quizzes.fields" @row-clicked="activityClickHandler"></b-table> -->

    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        data() {
            return {
                loaded: false,
                activities: {
                    fields: [
                        {
                            key: 'id',
                            label: '#',
                            sortable: true,
                        },
                        {
                            key: 'quiz.name',
                            label: 'Quiz',
                            sortable: true,
                        },
                        {
                            key: 'roster.number',
                            label: 'Classe',
                            sortable: true,
                        },
                        {
                            key : 'teacher.name',
                            label: 'Enseignant',
                            sortable: true,
                        },
                        {
                            key: 'duration',
                            label: 'Durée',
                            sortable: true,
                        },
                        {
                            label: 'Actions',
                            key: 'actions',
                        }
                    ],
                    data: [],
                    loaded: false
                },
                quizzes: {
                    fields: [
                        {
                            key: 'id',
                            label: '#',
                            sortable: true,
                        },
                        {
                            key: 'quiz.name',
                            label: 'Quiz',
                            sortable: true,
                        },
                        {
                            key: 'roster.number',
                            label: 'Classe',
                            sortable: true,
                        },
                        {
                            key : 'teacher.name',
                            label: 'Enseignant',
                            sortable: true,
                        },
                        {
                            key: 'duration',
                            label: 'Durée',
                            sortable: true,
                        },
                        {
                            label: 'Actions',
                            key: 'actions',
                        }
                    ],
                    data: [],
                    loaded: false
                },

            }
        },

        methods: {
            loadActivities: function(){
                axios
                    .get('api/activities')
                    .then((rep) => {
                        this.activities.data = rep.data.activities
                        this.activities.count = rep.data.count
                        this.activities.loaded = true
                    })
            },
            loadQuizzes: function(){
                axios
                    .get('api/quizzes')
                    .then((rep) => {
                        this.quizzes.data = rep.data.quizzes
                        this.quizzes.count = rep.data.count
                        this.quizzes.loaded = true
                    })
            },
            activityClickHandler(record, index) {
                this.$router.push({ name: 'Quiz', params: { activity_id: record.id } })
            }
        },

        mounted() {
            this.loadActivities()
            this.loadQuizzes()
        }
    }
</script>
