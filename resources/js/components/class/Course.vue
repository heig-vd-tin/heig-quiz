<template>
  <div>
    <h2>Course</h2>
    <b-form-input v-model="course" list="course-list"></b-form-input>

    <datalist id="course-list">
        <option v-for="c in courses" :key="c.id">{{ c.name }}</option>
    </datalist>

    <b-button variant="success" @click="validate">Validate course</b-button>

  </div>
</template>
<script>

export default {

  data() {
    return {
        courses:[],
        course: ''
    }
  },

  props:{
  },

  methods: {
      loadCourses : function() {
        axios
        .get('api/courses')
        .then(resp => {
            this.courses = resp.data.courses
        })
      },

      validate: function() {
        this.$emit("validate-course", this.course)
      }
  }, 

  mounted: function() {
      this.loadCourses()
  }
}
</script>