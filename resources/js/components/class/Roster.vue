<template>
  <div>
    <h2>Select Roster</h2>
    <b-form-select v-model="roster" @change="onRosterChange">
      <option v-for="r in rosters" :key="r.id" v-bind:value="r">
      {{ r.name }} / {{ r.semester }} / {{ r.year }}
      </option>
    </b-form-select>
    
  </div>
</template>
<script>

export default {

  data() {
    return {
        rosters:[],
        id_roster: null,
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

      onRosterChange: function() {
        this.$emit("validate-roster", this.roster)
      }
  }, 

  mounted: function() {
      this.loadRoster()
  }
}
</script>