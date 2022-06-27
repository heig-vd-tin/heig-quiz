<template>
  <div>
    <p> 
      <markdown-it-vue mb-2 :content="content" />
      points : {{points}}
    </p>
    <b-form-group v-if="$store.state.user.role == 'student'">
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
    <b-form-group v-else>
      <b-form-input
        v-model="validation.expected"
        :size="width"
        :readonly="validation != null"
      ></b-form-input>
    </b-form-group>
  </div>
</template>
<script>
import MarkdownItVue from 'markdown-it-vue'

export default {
  components: {
    MarkdownItVue
  },
  computed: {
    value: {
      get() {
        return this.answered;
      },
      set(value) {
        this.$emit('update:answered', value);
      }
    }
  },
  props: {
    content: String,
    width: { type: String, default: '30' },
    answered: String,
    validation: Object,
    is_correct: { type: [Boolean, null], required: false },
    points: Number
  }
};
</script>
