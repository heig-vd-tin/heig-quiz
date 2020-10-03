<template>
  <div>
    <p>
      <markdown-it-vue mb-2 :content="content" />
    </p>
    <b-form-group>
    <b-form-input
      v-model="value"
      :size="width"
      :readonly="validation != null"
      :state="validation ? is_correct : null"
    ></b-form-input>
    <b-form-invalid-feedback v-if="validation">
      {{ validation.expected }}
    </b-form-invalid-feedback>
    </b-form-group>
  </div>
</template>
<script>
export default {
  computed: {
    value: {
      get() { return this.answered },
      set(value) { this.$emit('update:answered', value) }
    }
  },
  props: {
    content: String,
    width: { type: String, default: "30" },
    answered: String,
    validation: Object,
    is_correct: { type: [Boolean, null], required: false }
  },
};
</script>
