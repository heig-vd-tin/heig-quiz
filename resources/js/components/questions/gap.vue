<template>
  <span>
    <b-form-input
      size="sm"
      v-on:change="$emit('updated')"
      v-if="options.length == 0 || validation != null"
      :readonly="validation != null"
      :state="is_correct"
      :value="value"
    >
      {{ answered }}
    </b-form-input>
    <b-form-select v-model="value" v-on:change="$emit('updated')" v-else :options="options" size="sm"></b-form-select>
  </span>
</template>
<script>
export default {
  computed: {
    value: {
      get() {
        return this.answered;
      },
      set(value) {
        this.$emit('update:gap', [this.id, value]);
      }
    }
  },
  props: {
    id: Number,
    name: String,
    options: { type: Array, default: [] },
    answered: { type: String, default: '' },
    validation: String,
    is_correct: Boolean
  }
};
</script>
