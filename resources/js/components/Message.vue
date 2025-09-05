<template>
  <div v-if="message" class="fixed top-5 right-5 z-50">
    <div
      :class="[
        'px-4 py-2 rounded shadow-md text-white font-semibold',
        typeClass
      ]"
    >
      {{ message }}
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  json: {
    type: Object,
    required: true
  }
});

const message = ref('');
const type = ref('success'); // 'success', 'error', 'info'

// Lắng nghe props.json thay đổi
watch(
  () => props.json,
  (newVal) => {
    if (newVal?.message) {
      message.value = newVal.message;
      type.value = newVal.type || 'success';

      // 3 giây tự ẩn
      setTimeout(() => {
        message.value = '';
      }, 3000);
    }
  },
  { immediate: true, deep: true }
);

// Class dựa trên type
const typeClass = computed(() => {
  switch (type.value) {
    case 'success':
      return 'bg-green-500';
    case 'error':
      return 'bg-red-500';
    case 'info':
      return 'bg-blue-500';
    default:
      return 'bg-gray-500';
  }
});
</script>

<style scoped>
/* optional shadow / animation */
</style>
