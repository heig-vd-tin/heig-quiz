<template>
  <div>
      <b-row class="my-1">
        <b-col sm="2">
          <label>Validation: </label>
        </b-col>
        <b-col sm="6">
          <b-form-input v-model="text_validation" type="text"></b-form-input>
        </b-col>
        <b-col sm="3">
          <label  class="help">List of answers in the order (banana,apple,egg) </label>
        </b-col>
      </b-row>

      <div v-for="(gap, index) in gaps">
        <b-row class="my-1">
          <b-col sm="2">
            <label>gaps: </label>
          </b-col>
          <b-col sm="6">
            <b-form-input v-model="gaps[index]" type="text"></b-form-input>
          </b-col>
          <b-col sm="3">
            <b-button @click="deleteGap(index)">delete</b-button>
            <label  class="help">List of possible answer (fruit,banana,strawberries) </label>
          </b-col>
        </b-row>
      </div>
      <b-button @click="addGaps">New gaps</b-button>
  </div>
</template>
<script>

export default {
  data() {
    return {
      gaps:['']
    }
  },

  methods: {
    addGaps: function () {
      this.gaps.push('');
    },

    deleteGap: function (index) {
      console.log(index);
      console.log(this.finds);
      this.gaps.splice(index, 1);
    }
  },

  props: {
    validation: Array,
    options: Array
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
          this.$emit('onValidationChange', newValue.split(','))
          
      }
    },

    gaps: {
      get: function() {
        
      },

      set: function(newValue) {
        console.log(newValue);
        
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