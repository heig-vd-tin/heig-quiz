<template>
  <div>
    <h2>Roster</h2>
    <b-form-input v-model="roster" list="roster-list"></b-form-input>

    <datalist id="roster-list">
        <option v-for="r in rosters" :key="r.id">{{ r.name }}</option>
    </datalist>

    <b-button variant="success" @click="validate">Validate roster</b-button>

  </div>
</template>
<script>

export default {

  data() {
    return {
        rosters:[],
        roster: ''
    }
  },

  props:{
  },

  methods: {
      loadRoster : function() {
        axios
        .get('api/rosters')
        .then(resp => {
            this.rosters = resp.data.data
        })
      },

      validate: function() {
        this.$emit("validate-roster", this.roster)
      }
  }, 

  mounted: function() {
      this.loadRoster()
  }
}
</script>