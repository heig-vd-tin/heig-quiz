<template>
  <div>

    <div v-for="(gap, index) in gaps">
      <b-row class="my-1">
        <b-col sm="2">
          <label>gaps: </label>
        </b-col>
        <b-col sm="6">
          <b-form-input v-model="gaps[index]" type="text" @input="handleChange"></b-form-input>
        </b-col>
        <b-col sm="3">
          <b-button @click="deleteGap(index)">delete</b-button>
          <label class="help">List of possible answer (fruit,banana,strawberries) </label>
        </b-col>
      </b-row>
      
    </div>
    <b-button @click="addGaps">New gaps</b-button>
  </div>

</template>
<script>
import { objectExpression } from '@babel/types';


export default {
  data() {
    return {
      gaps: [''],
      gapsObject: {
        'gaps': null
      }
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
      this.handleChange();
    },

    handleChange () {
      let gaps = [];
      for (let i = 0; i < this.gaps.length; ++i) {
        gaps.push(this.gaps[i].split(','));
      }

      this.gapsObject.gaps = gaps;
      this.$emit("onOptionsChange", this.gapsObject)
    }

  },

  props: {
    options: Object
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