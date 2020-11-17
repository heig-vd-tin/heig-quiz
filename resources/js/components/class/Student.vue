<template>
  <div>
    <h2>Students</h2>
    <b-form-input v-model="student" list="student-list"></b-form-input>
    <datalist id="student-list">
        <option v-for="s in studentsList" :key="s.id">{{ s.user.name }}</option>
    </datalist>

    <b-button variant="success" @click="addStudent">Add student</b-button>

    <b-table striped hover :key=cptRefresh :items="students"></b-table>
  </div>
</template>
<script>

export default {

  data() {
    return {
        studentsList:[],
        student: '',
        students: null,
        cptRefresh: 0
    }
  },

  props:{
      roster: null
  },
 
  watch: {
    roster: function (val) {
        this.loadStudent()
    }
  },

  methods: {
      loadUser : function() {
        axios
        .get('api/studentsfull')
        .then(resp => {
            this.studentsList = resp.data.students
        })
      },

      loadStudent : function() {
        axios
        .get('api/rosters/' + this.roster.id + '/students')
        .then(resp => {
            this.students = resp.data.data
        })
      },

      getStudent: function(name) {
        return this.studentsList.find(o => { return o.user.name === name })
      },

      addStudent: function() {
        var s = this.getStudent(this.student)

        var data = {
            'roster_id': this.roster.id,
            'student_id': s.id
        }

        const vm = this
        axios({
            method: 'post',
            url: 'api/rosters/add',
            data: data
        })
        .then(function (reponse) {
           vm.students = reponse.data.students.data
           vm.cptRefresh++
        })
        .catch(function (erreur) {
            console.log(erreur)
        });

        //this.$emit("validate-roster", this.roster)
      }
  }, 

  mounted: function() {
      this.loadUser()
      this.loadStudent()
  }
}
</script>