<template>
  <div>
      <b-row class="my-1">
        <b-col sm="2">
          <label>Validation: </label>
        </b-col>
        <b-col sm="6">
          <b-form-input  v-model="validation_list" type="text"></b-form-input>
        </b-col>
        <b-col sm="3">
          <label  class="help">List of right answers (1,3,4) </label>
        </b-col>
      </b-row>

      <b-row class="my-1">
        <b-col sm="2">
          <label>Justification: </label>
        </b-col>
        <b-col sm="6">
          <b-form-textarea  v-model="validation_justification" type="text"></b-form-textarea>
        </b-col>
        <b-col sm="3">
          <label  class="help">Justification wanted</label>
        </b-col>
      </b-row>
  </div>
</template>
<script>

export default {
  data() {
    return {
      validation_list: '',
      validation_justification: ''
    }
  },

  props: {
    validation: Array
  },

  watch:{
    validation_list: function(newVal, oldVal){
      this.validation = newVal.split(',');
      this.validation.push(this.validation_justification);
      console.log(this.validation);
    },
    validation_justification: function(newVal, oldVal){
      let size = this.validation.length;
      if (size == 0) {
        this.validation.push('')
        this.validation[size] = newVal;
      } else {
        this.validation[size-1] = newVal;
      }
      console.log(this.validation);
    } 
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