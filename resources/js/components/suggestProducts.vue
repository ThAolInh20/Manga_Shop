<template>
  <div class="card p-3 mb-4 mt-3 shadow-sm ">
    <h4 class="mb-3">📖 Gợi ý sản phẩm cho bạn</h4>
    <div class="product-grid">
      <div
        v-for="product in paginatedProducts"
        :key="product.id"
        class="card h-100"
      >
        <div class="position-relative product-img-wrapper">
          <img
            :src="product.images ? `/storage/${product.images}` : '/storage/products/default.png'"
            class="card-img-top product-img"
            alt="product"
            @click="viewDetail(product)"
            style="cursor: pointer"
          >
          <!-- Badge HOT -->
          <span
            v-if="product.quantity_buy > 50"
            class="badge bg-danger position-absolute top-0 end-0 m-2"
          >
            HOT
          </span>
         
          <!-- Hover actions -->
          <div class="product-actions">
            <!-- Wishlist -->
            <button class="btn btn-light btn-sm me-2" @click="toggleWishlist(product)">
              <i :class="product.in_wishlist ? 'bi bi-heart-fill text-danger' : 'bi bi-heart'"></i>
            </button>

            <!-- Cart -->
            <button class="btn btn-light btn-sm me-2" @click="addToCart(product)">
              <i class="bi bi-cart"></i>
            </button>

            <!-- Detail -->
            <button class="btn btn-light btn-sm" @click="viewDetail(product)">
              <i class="bi bi-search"></i>
            </button>
          </div>
        </div>

        <div class="card-body">
          <h5 class="card-title">{{ product.name }}</h5>
          <p class="card-text text-muted">Tác giả: {{ product.author }}</p>

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
import { ref, computed, onMounted,onUnmounted } from 'vue'
import { eventBus } from '../eventBus'

const products = ref([])
const page = ref(1)
const perPage = 6

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

async function fetchProducts() {
  const res = await fetch('/api/suggest-products')
  products.value = await res.json()
}

async function toggleWishlist(product) {
  if (product.in_wishlist) {
    // Xoá
    await fetch(`http://127.0.0.1:8000/api/wishlist/${product.id}`, {
      method: 'DELETE',
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    product.in_wishlist = false
  } else {
    // Thêm
    await fetch('http://127.0.0.1:8000/api/wishlist', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({ product_id: product.id })
    })
    
    
    product.in_wishlist = true
  }
  eventBus.emit('wishlist-updated')
}


function addToCart(product) {
  alert(`🛒 Đã thêm ${product.name} vào giỏ hàng!`)
}

function viewDetail(product) {
  window.location.href = `/products/${product.id}`
}

onMounted(fetchProducts)
onMounted(() => {
  fetchProducts()
  // 🔥 Lắng nghe sự kiện từ Search
  eventBus.on('wishlist-updated', fetchProducts)
})

onUnmounted(() => {
  eventBus.off('wishlist-updated', fetchProducts)
})
</script>

<style scoped>
.product-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
}

.product-img-wrapper {
  position: relative;
  overflow: hidden;
  border-radius: 0.5rem;
}

.product-img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  transition: transform 0.3s ease;
  border-radius: 0.5rem;
}

.product-img-wrapper:hover .product-img {
  transform: scale(1.05);
}

.product-actions {
  position: absolute;
  bottom: 10px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.product-img-wrapper:hover .product-actions { 
  opacity: 1;
}
</style>
