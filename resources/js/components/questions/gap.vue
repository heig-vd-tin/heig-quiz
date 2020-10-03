<template>
  <span>
    <b-form-input
      size="sm"
      v-on:change="$emit('updated')"
      v-if="options.length == 0 || validation != null"
      :readonly="validation != null"
      :state="is_correct"
      :value="answered"
    >{{answered}}</b-form-input>
    <b-form-select
      v-model="answered"
      v-on:change="$emit('updated')"
      v-else
      :options="options"
      size="sm"
    ></b-form-select>
  </span>
</template>
<script>
export default {
  props: {
    name: String,
    options: { type: Array, default: [] },
    answered: { type: String, default: "" },
    validation: String,
    is_correct: Boolean,
  },
};
</script>
