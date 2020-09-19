<template>
    <div class="container">
        <router-view></router-view>
    </div>
</template>

<script>
    import ActivitiesComponent from './ActivitiesComponent'

    export default {
        components: {
            'activities': ActivitiesComponent
        }
    }
</script>
