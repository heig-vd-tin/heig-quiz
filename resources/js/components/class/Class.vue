<template>
  <div>
    <h2>Test class</h2>
    <cmp-course @validate-course=validateCourse></cmp-course>
    <cmp-roster @validate-roster=validateRoster></cmp-roster>
    <cmp-student key:cpt_refresh v-if=roster :roster=roster></cmp-student>
  </div>
</template>
<script>

import CmpCourse from "./Course.vue"
import CmpRoster from "./Roster.vue"
import CmpStudent from "./Student.vue"

export default {
  components:{
      'cmp-course': CmpCourse,
      'cmp-roster': CmpRoster,
      'cmp-student': CmpStudent
  },

  data() {
    return {
        courses:[],
        roster: null,
        cpt_refresh: 0
    }
  },

  methods: {
      validateCourse : function(course) {
          console.log("Course valide")
          console.log(course)
      },

      validateRoster : function(roster) {
          this.roster = roster
          console.log(roster)
          this.cpt_refresh++
      },
  }, 

  mounted: function() {
  }
}
</script>