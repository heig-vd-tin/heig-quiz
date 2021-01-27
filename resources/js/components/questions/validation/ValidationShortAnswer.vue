<template>
  <div>
      <b-row class="my-1">
        <b-col sm="2">
          <label>Validation: </label>
        </b-col>
        <b-col sm="3">
          <b-form-group label="Type" v-slot="{ ariaDescribedby }">
            <b-form-radio v-model="valType" :aria-describedby="ariaDescribedby" name="rad1" value="regex">Regex</b-form-radio>
            <b-form-radio v-model="valType" :aria-describedby="ariaDescribedby" name="rad1" value="equal">Equal</b-form-radio>
          </b-form-group>
        </b-col>
        <b-col sm="3">
          <b-form-checkbox v-model="validation.trim">
            Trim answer
          </b-form-checkbox>
        </b-col>
      </b-row>

      <b-row class="my-1" v-if="valType == 'regex'">
        <b-col sm="2">
        </b-col>
        <b-col sm="6">
          <b-form-input v-model="validation.pattern" type="text"></b-form-input>
        </b-col>
        <b-col sm="3">
          <label  class="help">Regex: /\\b(0b?)0*111\\b/ </label>
        </b-col>
      </b-row>

      <b-row class="my-1" v-else>
        <b-col sm="2">
        </b-col>
        <b-col sm="6">
          <b-form-input v-model="validation.equals" type="text"></b-form-input>
        </b-col>
        <b-col sm="3">
          <label  class="help">Equals: val=12 </label>
        </b-col>
      </b-row>

      <b-row class="my-4">
        <b-col sm="2">
          <label> Expected : </label>
        </b-col>
        <b-col sm="6">
          <b-form-input v-model="validation.expected" type="text"></b-form-input>
        </b-col>
        <b-col sm="3">
          <label  class="help">0b0111</label>
        </b-col>
      </b-row>
  </div>
</template>
<script>

export default {
  data() {
    return {
      valType: 'regex',
      trim: false
    }
  },

  watch:{
    valType: function(newVal, oldVal){
      console.log("watch")
      if(newVal === 'regex'){
        delete this.validation.equals
      }
      else{
        delete this.validation.pattern
      }
    }
  },

  props: {
    validation: Object
  },

  computed: {
  },

  mounted() {
  }
}
</script>

<style scoped>
  .help {
    font-size: 0.9em;
    font-style: italic;
  }
</style>