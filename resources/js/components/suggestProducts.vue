<template>
  <div>
    <div class="product-grid">
      <div
        v-for="product in paginatedProducts"
        :key="product.id"
        class="card h-100"
      >
        <div class="position-relative">
          <img
             :src="product.images ? `/storage/${product.images}` : '/storage/products/default.png'"
          
            class="card-img-top product-img"
            alt="product"
          >
          <span
            v-if="product.quantity_buy > 50"
            class="badge bg-danger position-absolute top-0 end-0 m-2"
          >
            HOT
          </span>
        </div>
        <div class="card-body">
          <h5 class="card-title">{{ product.name }}</h5>
          <p class="card-text text-muted">{{ product.author }}</p>

          <div v-if="product.sale">
            <p class="mb-1">
              <span class="text-muted text-decoration-line-through me-2">
                {{ formatPrice(product.price) }} đ
              </span>
              <small class="text-success">-{{ product.sale }}%</small>
            </p>
            <p class="fw-bold text-danger">
              {{ formatPrice(discountedPrice(product)) }} đ
            </p>
          </div>
          <div v-else>
            <p class="fw-bold text-dark">
              {{ formatPrice(product.price) }} đ
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <nav class="mt-3">
      <ul class="pagination justify-content-center">
        <li class="page-item" :class="{disabled: page===1}">
          <a href="#" class="page-link" @click.prevent="page--">«</a>
        </li>
        <li
          v-for="n in totalPages"
          :key="n"
          class="page-item"
          :class="{active: page===n}"
        >
          <a href="#" class="page-link" @click.prevent="page=n">{{ n }}</a>
        </li>
        <li class="page-item" :class="{disabled: page===totalPages}">
          <a href="#" class="page-link" @click.prevent="page++">»</a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const products = ref([])
const page = ref(1)
const perPage = 6 // số sản phẩm mỗi trang

const totalPages = computed(() => Math.ceil(products.value.length / perPage))
const paginatedProducts = computed(() => {
  const start = (page.value - 1) * perPage
  return products.value.slice(start, start + perPage)
})

function formatPrice(num) {
  return new Intl.NumberFormat('vi-VN').format(num)
}

function discountedPrice(p) {
  return p.price - (p.price * p.sale / 100)
}

onMounted(async () => {
  const res = await fetch('/api/suggest-products')
  products.value = await res.json()
})
</script>

<style scoped>
.product-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr); 
  gap: 1.5rem;
}

.product-img {
  width: 100%;
  height: 200px; 
  object-fit: cover;
}
</style>
