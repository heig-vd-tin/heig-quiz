<template>
  <div>
    <b-container fluid>
        <cmp-roster class="mb-4" @validate-roster=validateRoster></cmp-roster>
        <cmp-student class="mb-4" key:cpt_refresh v-if=roster :roster=roster></cmp-student>
    </b-container>
  </div>
</template>
<script>

import CmpRoster from "./Roster.vue"
import CmpStudent from "./Student.vue"

export default {
  components:{
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
          this.cpt_refresh++
      },
  }, 

  mounted: function() {
  }
}
</script>