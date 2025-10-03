<template>
  
  <div class="card p-3 mb-4 mt-3 shadow-sm product-hot-section">
    <h4 class="mb-3">📖 Sản phẩm hot</h4>
    <div v-if="loading" class="text-center my-5">
    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
      <span class="visually-hidden">Đang tải...</span>
    </div>
</div>
    <div v-else class="product-grid">
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
            <button v-if="isLoggedIn" class="btn btn-light btn-sm me-2" @click="addToCart(product)">
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

          <div v-if="product.sale>0">
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
          <div class="progress-wrapper mb-2">
  <div class="progress-fill" :style="{ width: soldPercent(product) + '%' }"></div>
  <div class="progress-text">
    {{ product.quantity_buy }} đã bán
  </div>
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
const loading = ref(false)

const isLoggedIn=ref(false)

const totalPages = computed(() => Math.ceil(products.value.length / perPage))
const paginatedProducts = computed(() => {
  const start = (page.value - 1) * perPage
  return products.value.slice(start, start + perPage)
})
async function checkLogin() {
  try {
    const res = await fetch('/api/user')
    const data = await res.json()
    isLoggedIn.value = data.status === 'logged_in'
  } catch (err) {
    console.error(err)
    isLoggedIn.value = false
  }
}

function formatPrice(num) {
  return new Intl.NumberFormat('vi-VN').format(num)
}

function discountedPrice(p) {
  return p.price - (p.price * p.sale / 100)
}

async function fetchProducts() {
  loading.value = true   // 👉 bật spinner
  try {
    const res = await fetch('/api/suggest-products')
    products.value = await res.json()
  } catch (err) {
    console.error(err)
  } finally {
    loading.value = false  // 👉 tắt spinner khi xong
  }
}

async function toggleWishlist(product) {
  if (product.in_wishlist) {
    // Xoá
    await fetch(`/api/wishlist/${product.id}`, {
      method: 'DELETE',
       headers: {
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  }
    })
    product.in_wishlist = false
  } else {
    // Thêm
    await fetch('/api/wishlist', {
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


async function addToCart(product) {
  try {
    const res = await axios.post('/api/cart', {
      product_id: product.id,
      price: product.price,
      sale: product.sale || 0
    })

    if (res.status === 201) {
      alert(`🛒 Đã thêm ${product.name} vào giỏ hàng!`)
      eventBus.emit('cart-add')
    } else {
      
      alert(res.data.message)
      eventBus.emit('cart-add')
    }
  } catch (err) {
    if (err.response && err.response.status === 401) {
      alert('⚠️ Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng!')
    } else {
      console.error(err)
      alert('❌ Có lỗi xảy ra khi thêm sản phẩm!')
    }
  }
}

function viewDetail(product) {
  window.location.href = `/products/${product.id}`
}
const soldPercent = (product) => {
  if (!product.quantity || product.quantity === 0) return 0
  return Math.min(Math.round((product.quantity_buy / (product.quantity+product.quantity_buy)) * 100), 100)
}
onMounted(fetchProducts)
onMounted(() => {
  fetchProducts()
  // 🔥 Lắng nghe sự kiện từ Search
  eventBus.on('wishlist-updated', fetchProducts)
  checkLogin()
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
.progress-wrapper {
  background-color: #d6dadf;
  border-radius: 12px;
  height: 24px;
  position: relative;
  overflow: hidden;
}

.progress-fill {
  background: linear-gradient(90deg, #5da4d7, #9fd9f4);
  height: 100%;
  transition: width 0.5s ease;
}

.progress-text {
  position: absolute;
  top: 0;
  left: 50%;         /* Luôn ở giữa thanh */
  transform: translateX(-50%);
  width: 100%;        /* Chiếm toàn bộ thanh để chữ luôn ở center */
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #e9edf1;        /* Màu chữ nổi bật */
  font-weight: 600;
  font-size: 0.875rem;
  
  pointer-events: none;
  white-space: nowrap;
}
/* Tên sản phẩm luôn chiếm 2 dòng */
.card-title {
  display: -webkit-box;
  -webkit-line-clamp: 2;   /* tối đa 2 dòng */
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;

  /* Bắt buộc luôn chiếm 2 dòng */
  line-height: 1.2rem;   /* bạn điều chỉnh theo font-size */
  height: calc(1.2rem * 2); /* 2 dòng */
  min-height: calc(1.2rem * 2);
  max-height: calc(1.2rem * 2);
}

/* Tác giả, giá, sale - 1 dòng */
.card-text,
.card-body p {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.product-hot-section {
  background: url('/storage/banner/main_banner2.png') no-repeat center center;
  background-size: cover;   /* ảnh luôn phủ full */
  border-radius: 0.5rem;
  color: #fff; /* chữ nổi bật hơn */
}
</style>
