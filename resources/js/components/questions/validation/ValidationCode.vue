<template>
  <div>
    <b-row class="my-1">
      <b-col sm="2">
        <label>Language: </label>
      </b-col>
      <b-col sm="3">
        <b-form-group label="Type" v-slot="{ ariaDescribedby }">
          <b-form-radio v-model="valType" :aria-describedby="ariaDescribedby" name="lang" value="c">C</b-form-radio>
          <b-form-radio v-model="valType" :aria-describedby="ariaDescribedby" name="lang" value="cpp">C++</b-form-radio>
          <b-form-radio v-model="valType" :aria-describedby="ariaDescribedby" name="lang" value="java">Java</b-form-radio>
          <b-form-radio v-model="valType" :aria-describedby="ariaDescribedby" name="lang" value="python">Python</b-form-radio>
        </b-form-group>
      </b-col>
    </b-row>
  </div>
</template>
<script>

export default {
  data() {
    return {
      valType: 'c'
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