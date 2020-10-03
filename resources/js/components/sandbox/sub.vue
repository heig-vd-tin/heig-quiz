<template>
  <b-form-group :description="title">
    <b-form-input v-model="text"></b-form-input>
  </b-form-group>
</template>
<script>
export default {
  computed: {
    text: {
      get() { return this.value },
      set(value) { this.$emit('update:value', value) }
    }
  },
  props: {
    value: String,
    title: String
  }
}
</script>
