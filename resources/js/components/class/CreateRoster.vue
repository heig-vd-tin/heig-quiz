<template>
  <div>
    <b-container fluid>
    <h2>Create roster</h2>
    
    <b-row class="my-1">
      <b-col sm="3">
        <label> Course : </label>
      </b-col>
      <b-col sm="9">
        <b-form-input v-model="course" list="course-list"></b-form-input>

        <datalist id="course-list">
          <option v-for="c in courses" :key="c.id">{{ c.name }}</option>
        </datalist>
      </b-col>
    </b-row>

    <b-row class="my-1">
      <b-col sm="3">
        <label> Name : </label>
      </b-col>
      <b-col sm="9">
        <b-form-input v-model="roster.name" type="text"></b-form-input>
      </b-col>
    </b-row>

    <b-row class="my-1">
      <b-col sm="3">
        <label> Semester : </label>
      </b-col>
      <b-col sm="9">
        <b-form-input v-model="roster.semester" type="number"></b-form-input>
      </b-col>
    </b-row>

    <b-row class="my-1">
      <b-col sm="3">
        <label> Year : </label>
      </b-col>
      <b-col sm="9">
        <b-form-input v-model="roster.year" type="number"></b-form-input>
      </b-col>
    </b-row>

    <b-button variant="success" @click="validate">Create roster</b-button>

    </b-container>
  </div>
</template>
<script>

export default {
  data() {
    return {
        courses:[],
        course: '',
        roster: {
          'year': null,
          'semester': null,
          'name': '',
          'course_id': null
        }
    }
  },

  props:{
  },

  methods: {
    getCourse: function(name) {
        return this.courses.find(o => { return o.name === name })
    },

    loadCourses : function() {
      axios
      .get('api/courses')
      .then(resp => {
          this.courses = resp.data.courses
      })
    },

    validate: function() {
      const c = this.getCourse(this.course)
      const vm = this
      this.roster.course_id = c.id
      axios({
          method: 'post',
          url: 'api/rosters/create',
          data: this.roster
      })
      .then(function (reponse) {
          vm.students = reponse.data.students.data
      })
      .catch(function (erreur) {
          console.log(erreur)
      });
    }
  }, 

  mounted: function() {
      this.loadCourses()
  }
}
</script>