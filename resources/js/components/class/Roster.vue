<template>
  <div>
    <h2>Roster</h2>
    <b-form-select v-model="roster">
      <option v-for="r in rosters" :key="r.id" v-bind:value="r">
      {{ r.name }}
      </option>
    </b-form-select>

    <b-button variant="success" @click="validate">Validate roster</b-button>

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

      validate: function() {
        debugger
        this.$emit("validate-roster", this.roster)
      }
  }, 

  mounted: function() {
      this.loadRoster()
  }
}
</script>