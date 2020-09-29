<template>
  <span>
      <b-form-text class="display:inline;" v-if="options.length == 0"></b-form-text>
      <b-form-select class="display:inline;"  v-else>
        <b-form-select-option v-for="(option, index) in options" :key="index" :value="value">
          {{ option }}
        </b-form-select-option>
      </b-form-select>
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
    }
  }
}
</script>
