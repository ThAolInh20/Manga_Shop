<template>
  <div v-if="visible" 
       class="alert alert-success alert-dismissible fade show shadow position-fixed top-0 end-0 mt-5 me-3"
       style="z-index: 2000; max-width: 400px;"
       role="alert">
    {{ message }}
    <button type="button" class="btn-close" @click="close"></button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  message: String,
  duration: { type: Number, default: 3000 } // mặc định 3s
})

const visible = ref(true)

function close() {
  visible.value = false
}

onMounted(() => {
  // Tự động biến mất sau duration ms
  setTimeout(() => {
    close()
  }, props.duration)
})
</script>
