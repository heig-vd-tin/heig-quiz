<template>
  <component :is="component"></component>
</template>
<script>

import ActivityStudent from './ActivitiesStudent'
import ActivityTeacher from './ActivitiesTeacher'

export default {
  data() {
    return {
      component: null
    }
  },
  mounted() {
    if (this.$store.state.user.role == 'teacher')
      this.component = ActivityTeacher;
    else
      this.component = ActivityStudent;
  },
}
</script>
