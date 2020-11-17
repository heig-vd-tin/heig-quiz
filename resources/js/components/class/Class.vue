<template>
  <div>
    <h2>Test class</h2>
    <cmp-course @validate-course=validateCourse></cmp-course>
    <cmp-roster @validate-roster=validateRoster></cmp-roster>
    <cmp-student></cmp-student>
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
        courses:[]
    }
  },

  methods: {
      validateCourse : function(course) {
          console.log("Course valide")
          console.log(course)
      },

      validateRoster : function(roster) {
          console.log(roster)
      },
  }, 

  mounted: function() {
  }
}
</script>