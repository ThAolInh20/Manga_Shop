<template>
  <div class="mb-3">
    <label class="form-label">{{ label }}</label>

    <div class="d-flex flex-column gap-1" style="max-height: auto;">
      <div
        v-for="(opt, index) in visibleOptions"
        :key="opt"
        class="form-check"
      >
        <input
          class="form-check-input"
          type="checkbox"
          :id="`${rowName}-${opt}`"
          :value="opt"
          v-model="value"
        >
        <label class="form-check-label" :for="`${rowName}-${opt}`">{{ opt }}</label>
      </div>
    </div>

    <button
      v-if="options.length > maxVisible"
      type="button"
      class="btn btn-link p-0 mt-1"
      @click="toggleShowMore"
    >
      {{ showAll ? 'Thu gọn' : `Xem thêm (${options.length - maxVisible})` }}
    </button>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'FilterField',
  props: {
    rowName: { type: String, required: true },
    modelValue: { type: Array, default: () => [] },
    label: { type: String, default: '' },
    maxVisible: { type: Number, default: 10 } // số checkbox hiển thị ban đầu
  },
  emits: ['update:modelValue'],
  data() {
    return {
      value: [...this.modelValue],
      options: [],
      showAll: false
    };
  },
  watch: {
    value(val) {
      this.$emit('update:modelValue', val);
    },
    rowName: {
      immediate: true,
      handler() {
        this.fetchOptions();
      }
    }
  },
  computed: {
    visibleOptions() {
      if (this.showAll) return this.options;
      return this.options.slice(0, this.maxVisible);
    }
  },
  methods: {
    toggleShowMore() {
      this.showAll = !this.showAll;
    },
    async fetchOptions() {
      try {
        const res = await axios.get(`/api/boloc?field=${this.rowName}`);
        this.options = res.data;
      } catch (err) {
        console.error('Error fetching filter options:', err);
      }
    }
  }
};
</script>
