<template>
<span>
  <span
    class="fa toggle"
    v-bind:class="[
      value ? 'fa-toggle-on' : 'fa-toggle-off',
      value ? 'text-' + variant : 'text-muted'
    ]"
    @click="value = !value"
  ></span>
  <span class="toggle-label" v-show="label">{{ label }}</span>
  </span>
</template>
<script>
export default {
  data() {
    return {
    }
  },
  computed: {
    value: {
      get() {
        return this.toggled;
      },
      set(value) {
        this.$emit('update:toggled', value);
      }
    }
  },
  props: {
    variant: {
      type: String,
      default: 'text-success'
    },
    label: String,
    toggled: Boolean
  }
}
</script>
<style scoped lang="scss">

.fa {
  font-size: 30px;
  cursor: pointer;
}

.toggle-label {
    display: inline-block;
    padding: 2px 0 0 4px;
    vertical-align: top;
    font-size: 20px;
    color: #777;
}

</style>
