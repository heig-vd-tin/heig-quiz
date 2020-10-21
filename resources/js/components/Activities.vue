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
    if (Vue.prototype.$user.affiliation.includes('student'))
      this.component = ActivityStudent;
    else
      this.component = ActivityTeacher;
  },
}
</script>
