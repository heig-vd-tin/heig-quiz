<template>
  <div>
    <h2>Students</h2>
    <b-form-input v-model="user" list="user-list"></b-form-input>
    <datalist id="user-list">
        <option v-for="u in usersList" :key="u.id">{{ u.name }}</option>
    </datalist>

    <b-button variant="success" @click="addStudent">Add student</b-button>

  </div>
</template>
<script>

export default {

  data() {
    return {
        usersList:[],
        user: ''
    }
  },

  props:{
  },

  methods: {
      loadStudent : function() {
        axios
        .get('api/users')
        .then(resp => {
            this.usersList = resp.data.users
        })
      },

      addStudent: function() {
        //this.$emit("validate-roster", this.roster)
      }
  }, 

  mounted: function() {
      this.loadStudent()
  }
}
</script>