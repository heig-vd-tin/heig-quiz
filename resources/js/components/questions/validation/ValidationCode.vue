<template>
  <div>
      <b-row class="my-1">
        <b-col sm="2">
          <label>Validation: </label>
        </b-col>
        <b-col sm="6">
          <b-form-input v-model="validation.pattern" type="text"></b-form-input>
        </b-col>
        <b-col sm="3">
          <label  class="help">Regex: /\\b(0b?)0*111\\b/ </label>
        </b-col>
      </b-row>

      <b-row class="my-1">
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
    }
  },

  props: {
    validation: Object
  },

  computed: {
    text_validation: {
      get: function () {
        if( this.validation == null ){
          return {
            'expected': '',
            'pattern': ''
          }
        }
        else {
          return this.validation
        }
      },
      set: function (newValue) {
          this.$emit('onValidationChange', newValue)
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