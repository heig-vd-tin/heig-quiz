<template>
  <div>
      <b-container class="p-0">
        <b-row class="my-1">
            <b-col sm="2">
            <label>Validation selection: </label>
            </b-col>
            <b-col sm="6">
            <b-form-input v-model="text_validation" type="text"></b-form-input>
            </b-col>
            <b-col sm="3">
            <label  class="help">List of right answers (1,3,4) </label>
            </b-col>
        </b-row>

        <b-row class="my-1">
            <b-col sm="2">
                <label>Validation explanation: </label>
            </b-col>
            <b-col sm="6">
                <b-form-textarea v-model="validation.explanation" rows="3" max-rows="6"></b-form-textarea>
            </b-col>
            <b-col sm="3">
                <label  class="help"></label>
            </b-col>
        </b-row>
      </b-container>

      
  </div>
</template>
<script>

export default {
  data() {
    return {
    }
  },

  props: {
    validation: Object
  },

  computed: {
    text_validation: {
      get: function () {
        if( this.validation == null ){
          return ''
        }
        else {
          return this.validation.join()
        }
      },
      set: function (newValue) {
        this.validation.list = newValue.split(',');
        console.log(this.validation);
      }
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