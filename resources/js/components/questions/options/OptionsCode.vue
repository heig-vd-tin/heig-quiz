<template>
  <div>
    <b-row class="my-1">
      <b-col sm="2">
        <label>Language: </label>
      </b-col>
      <b-col sm="3">
        <b-form-group label="Type" v-slot="{ ariaDescribedby }">
          <b-form-radio v-model="valType" :aria-describedby="ariaDescribedby" name="language" value="C">C</b-form-radio>
          <b-form-radio v-model="valType" :aria-describedby="ariaDescribedby" name="language" value="CPP">C++</b-form-radio>
          <b-form-radio v-model="valType" :aria-describedby="ariaDescribedby" name="language" value="JAVA">Java</b-form-radio>
          <b-form-radio v-model="valType" :aria-describedby="ariaDescribedby" name="language" value="PYTHON">Python</b-form-radio>
        </b-form-group>
      </b-col>
    </b-row>
  </div>
</template>
<script>

export default {
  data() {
    return {
      valType: 'C'
    }
  },

  watch:{
    valType: function(newVal, oldVal){
      console.log("watch")
      this.options.language = newVal;
      
    }
  },

  props: {
    options: Object
  },

  computed: {
  },

  mounted() {
    this.options.language = 'C'
  }
}
</script>

<style scoped>
  .help {
    font-size: 0.9em;
    font-style: italic;
  }
</style>