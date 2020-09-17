<template>
    <div class="container">
        <!-- <router-view></router-view> -->
        <activities></activities>
    </div>
</template>

<script>
    import ActivitiesComp from './ActivitiesComponent'
    
    export default {
        components: {
            'activities': ActivitiesComp
        }
    }
</script>
