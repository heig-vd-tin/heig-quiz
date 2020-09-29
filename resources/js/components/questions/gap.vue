<template>
  <span>
      <b-form-text v-on:change="$emit('updated')" v-if="options.length == 0" :value="value"></b-form-text>
      <b-form-select v-model="selected" v-on:change="$emit('updated')" v-else :options="options" size="sm"></b-form-select>
  </span>
</template>
<script>
export default {
  props: {
    name: String,
    options: {
      type: Array,
      default: []
    },
    value: {
      type: String,
      default: ''
    },
    selected: Number
  },
}
</script>
