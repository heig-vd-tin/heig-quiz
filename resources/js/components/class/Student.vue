<template>
  <div>
    <h2>Students</h2>
    <b-form-input v-model="user" list="user-list"></b-form-input>
    <datalist id="user-list">
        <option v-for="u in usersList" :key="u.id">{{ u.name }}</option>
    </datalist>

    <b-button variant="success" @click="addStudent">Add student</b-button>

    <b-table striped hover :items="students"></b-table>
  </div>
</template>
<script>

export default {

  data() {
    return {
        usersList:[],
        user: '',
        students: null
    }
  },

  props:{
      roster: null
  },

  methods: {
      loadUser : function() {
        axios
        .get('api/users')
        .then(resp => {
            this.usersList = resp.data.users
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

      },

      addStudent: function() {
          var u = this.usersList.find(o => { return o.name === this.user })
          console.log(u);
        //this.$emit("validate-roster", this.roster)
      }
  }, 

  mounted: function() {
      this.loadUser()
      this.loadStudent()
  }
}
</script>