<template>
  <div class="card p-3">
    <h4 class="mb-3">📖 Gợi ý sách cho bạn</h4>
    <ul v-if="books.length">
      <li v-for="book in books" :key="book.id">
        {{ book.name }} - <small>{{ book.author }}</small>
      </li>
    </ul>
    <p v-else>Không có gợi ý nào...</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const books = ref([])

onMounted(async () => {
  const res = await fetch('/api/suggest-products')
  books.value = await res.json()
})
</script>
