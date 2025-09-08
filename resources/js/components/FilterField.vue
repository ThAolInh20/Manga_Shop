<template>
  <div class="mb-3">
    <label class="form-label">{{ label }}</label>
    <div v-for="(opt, index) in visibleOptions" :key="index" class="form-check">
      <input
        class="form-check-input"
        type="checkbox"
        :id="rowName + '-' + index"
        :value="opt"
        v-model="selected"
      >
      <label class="form-check-label" :for="rowName + '-' + index">
        {{ opt }}
      </label>
    </div>

    <button
      v-if="options.length > limit"
      class="btn btn-link p-0 mt-2"
      @click="showAll = !showAll"
    >
      {{ showAll ? 'Ẩn bớt' : 'Xem thêm...' }}
    </button>

    <hr class="text-muted">
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'FilterField',
  props: {
    rowName: { type: String, required: true },
    label: { type: String, default: '' },
    modelValue: { type: Array, default: () => [] }
  },
  emits: ['update:modelValue'],
  data() {
    return {
      options: [],
      selected: this.modelValue,
      limit: 10,
      showAll: false
    }
  },
  computed: {
    visibleOptions() {
      return this.showAll ? this.options : this.options.slice(0, this.limit)
    }
  },
  watch: {
    selected(val) {
      this.$emit('update:modelValue', val)
    },
    rowName: {
      immediate: true,
      handler() {
        this.fetchOptions()
      }
    }
  },
  methods: {
    async fetchOptions() {
      try {
        const res = await axios.get(`/api/boloc?field=${this.rowName}`)
        this.options = res.data
      } catch (err) {
        console.error('Error fetching filter options:', err)
      }
    }
  }
}
</script>
