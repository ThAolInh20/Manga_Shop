<template>
  
  <div class="row">
    <!-- <Alert v-if="alertMessage" :message="alertMessage" /> -->
    <!-- Sidebar filter -->
     
    <div class="col-3">
      <CategoriesList v-model="filters.category_id[0]" @change="onCategorySelected" />
      <h4 class="mb-3">Bộ lọc</h4>
      <!-- <filter-field v-model="filters.category_id" row-name="category_id" label="Thể loại" @change="onFilterChange"></filter-field> -->
      <filter-field v-model="filters.categ" row-name="categ" label="Thể loại" @change="onFilterChange"></filter-field>
      
      <filter-field v-model="filters.author" row-name="author" label="Tác giả" @change="onFilterChange"></filter-field>
      <filter-field v-model="filters.publisher" row-name="publisher" label="Nhà xuất bản" @change="onFilterChange"></filter-field>
    
    </div>
      <div v-if="loading" class="text-center my-5">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
          <span class="visually-hidden">Đang tải...</span>
        </div>
    </div>
    <!-- Product list -->
    <div  v-else  class="col-9">
      <h4 class="mb-3">Danh sách sản phẩm</h4>
      <div class="card p-3 mb-3">
  <div class="row g-3 align-items-end">
  <!-- Lọc theo giá -->
  <div class="col-md-5">
    <label class="form-label">Khoảng giá:</label>
    <div class="d-flex ">
            <input
                type="text"
                class="form-control me-2"
                v-model="minPriceInput"
                @input="formatMinPrice"
                placeholder="Từ"
                />

                <input
                type="text"
                class="form-control"
                v-model="maxPriceInput"
                @input="formatMaxPrice"
                placeholder="Đến"
                />
    </div>
  </div>

  <!-- Số lượng hiển thị -->
  <div class="col-md-3">
    <label class="form-label">📄 Số sản phẩm/trang</label>
    <select class="form-select" v-model.number="perPage" @change="fetchProducts(1)">
      <option :value="6">6</option>
      <option :value="9">9</option>
      <option :value="12">12</option>
      <option :value="24">24</option>
    </select>
  </div>

  <!-- Sắp xếp -->
  <div class="col-md-3">
    <label class="form-label">Sắp xếp theo:</label>
    <div class="input-group">
      <select class="form-select" v-model="sortBy">
        <option value="name">Tên sản phẩm</option>
        <option value="price">Giá sản phẩm</option>
        <option value="quantity_buy">Lượt mua</option>
        <option value="sale">Giảm giá</option>
      </select>
      <button class="btn btn-outline-secondary sort-btn" @click="toggleSortOrder">
        <i :class="sortOrder === 'asc' ? 'bi bi-sort-down-alt' : 'bi bi-sort-down'"></i>
      </button>
    </div>
  </div>
</div>

</div>
      <div v-if="!filteredProducts.length" class="text-center my-5">
        <i class="bi bi-search fs-1 text-muted d-block mb-2"></i>
        <p class="text-muted fs-5">Không tìm thấy sản phẩm phù hợp</p>
      </div>
      <div v-else class="product-grid">
        <div v-for="product in filteredProducts" :key="product.id" class="card h-100">
          <!-- Ảnh + actions -->
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
              <button class="btn btn-light btn-sm me-2" @click="toggleWishlist(product)">
                <i :class="product.in_wishlist ? 'bi bi-heart-fill text-danger' : 'bi bi-heart'"></i>
                   <!-- <i :class="isInWishlist(product.id) ? 'bi bi-heart-fill text-danger' : 'bi bi-heart'"></i> -->

              </button>
              
              <button v-if="isLoggedIn" class="btn btn-light btn-sm me-2" @click="addToCart(product)">
                <i class="bi bi-cart"></i>
              </button>
             
              <button class="btn btn-light btn-sm" @click="viewDetail(product)">
                <i class="bi bi-search"></i>
              </button>
            </div>
          </div>

          <!-- Thông tin -->
          <div class="card-body">
            <h5 class="card-title">{{ product.name }}</h5>
            <p class="card-text text-muted">Tác giả: {{ product.author }}</p>

            <div v-if="product.sale>0">
              <p class="mb-1">
                <span class="text-muted text-decoration-line-through me-2">
                  {{ formatPrice(product.price) }} đ
                </span>
                <small class="text-success" >-{{ product.sale }}%</small>
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
      <nav class="mt-4">
        <ul class="pagination justify-content-center">
          <li class="page-item" :class="{disabled: page===1}">
            <a href="#" class="page-link" @click.prevent="fetchProducts(page-1)">«</a>
          </li>
          <li v-for="n in lastPage" :key="n" class="page-item" :class="{active: page===n}">
            <a href="#" class="page-link" @click.prevent="fetchProducts(n)">{{ n }}</a>
          </li>
          <li class="page-item" :class="{disabled: page===lastPage}">
            <a href="#" class="page-link" @click.prevent="fetchProducts(page+1)">»</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted,onUnmounted } from 'vue'
// import { useWishlist } from '../stores/wishlistStore'
// const { toggle, isInWishlist } = useWishlist()
import { eventBus } from '../eventBus'
const products = ref([])
const page = ref(1)
const lastPage = ref(1)
const perPage = ref(12)
const minPriceInput = ref('')
const maxPriceInput = ref('')
const sortBy = ref('name')
const sortOrder = ref('asc')

const searchKeyword = ref('')
const alertMessage = ref('')
const isLoggedIn = ref(false)
const loading = ref(false)

const filters = ref({
  category_id: [],
  categ:[],
  author: [],
  publisher: [],
    minPrice: null,
  maxPrice: null
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

function onCategorySelected(categoryId) {
  filters.value.category_id = categoryId ? [categoryId] : [] // gán vào filter
  page.value = 1
  fetchProducts(1)
}
function onFilterChange() {
  page.value = 1
  fetchProducts(1)
}

function formatNumber(value) {
  // Bỏ ký tự không phải số
  let num = value.replace(/\D/g, '')
  // Thêm dấu chấm
  return num.replace(/\B(?=(\d{3})+(?!\d))/g, '.')
}
function formatMinPrice(e) {
  // Chuyển input string dạng "1.000" thành number
  const raw = e.target.value.replace(/\D/g, '')
  minPriceInput.value = formatNumber(e.target.value)
  filters.value.minPrice = raw ? Number(raw) : null
}

function formatMaxPrice(e) {
  const raw = e.target.value.replace(/\D/g, '')
  maxPriceInput.value = formatNumber(e.target.value)
  filters.value.maxPrice = raw ? Number(raw) : null
}
async function fetchProducts(p = 1) {
  if (p < 1 || (lastPage.value && p > lastPage.value)) return
  loading.value = true
  const params = new URLSearchParams({
    page: p,
    perPage: perPage.value,
    sortBy: sortBy.value,
    sortOrder: sortOrder.value,
    search: searchKeyword.value // 🔥 quan trọng
  })

  if (filters.value.minPrice) params.append('minPrice', filters.value.minPrice)
  if (filters.value.maxPrice) params.append('maxPrice', filters.value.maxPrice)
   if (filters.value.category_id.length) params.append('category_id', filters.value.category_id.join(','))
   if (filters.value.categ.length) params.append('categ', filters.value.categ.join(','))
  if (filters.value.author.length) params.append('author', filters.value.author.join(','))
  if (filters.value.publisher.length) params.append('publisher', filters.value.publisher.join(','))
  try{
    const res = await fetch(`/api/products?${params.toString()}`)
    const data = await res.json()
    products.value = data.data
    page.value = data.current_page
    lastPage.value = data.last_page
  }catch (err) {
    console.error(err)
  }finally{
    loading.value = false 
  }
  
}

const filteredProducts = computed(() => {
  let list = products.value

  // Lọc theo categ/author/publisher
  if (filters.value.category_id.length ||filters.value.categ.length || filters.value.author.length || filters.value.publisher.length) {
    list = list.filter(p => {
      const matchCateg = filters.value.category_id.length === 0 || filters.value.category_id.includes(p.category_id)
      const matchCateg1 = filters.value.categ.length === 0 || filters.value.categ.includes(p.categ)
      const matchAuthor = filters.value.author.length === 0 || filters.value.author.includes(p.author)
      const matchPublisher = filters.value.publisher.length === 0 || filters.value.publisher.includes(p.publisher)
      return matchCateg && matchAuthor && matchPublisher &&matchCateg1
    })
  }

  // Lọc theo giá
if (filters.value.minPrice !== null) {
  list = list.filter(p => Number(p.price) >= filters.value.minPrice)
}
if (filters.value.maxPrice !== null) {
  list = list.filter(p => Number(p.price) <= filters.value.maxPrice)
}

  // Sắp xếp
  list = [...list].sort((a, b) => {
    let valA = a[sortBy.value]
    let valB = b[sortBy.value]

    // Nếu là số (giá, lượt mua) → ép về number
  if (sortBy.value === 'price' || sortBy.value === 'quantity_buy'||sortBy.value==='sale') {
    valA = Number(valA)
    valB = Number(valB)
  } else if (typeof valA === 'string') {
    // Nếu là chuỗi (tên, tác giả,...) → chuyển lowercase để so sánh
    valA = valA.toLowerCase()
    valB = valB.toLowerCase()
  }

    if (valA < valB) return sortOrder.value === 'asc' ? -1 : 1
    if (valA > valB) return sortOrder.value === 'asc' ? 1 : -1
    return 0
  })

  return list
})
function toggleSortOrder() {
  sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
}
function formatPrice(num) {
  return new Intl.NumberFormat('vi-VN').format(num)
}

function discountedPrice(p) {
  return p.price - (p.price * p.sale / 100)
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
// function toggleWishlist(product) {
//   toggle(product)
// }

async function addToCart(product) {
  try {
    const res = await axios.post('/api/cart', {
      product_id: product.id,
      price: product.price,
      sale: product.sale || 0
    })

    if (res.status === 201) {
      alert(`Đã thêm ${product.name} vào giỏ hàng!`)
    } else {
      alert(res.data.message)
    }
  } catch (err) {
    if (err.response && err.response.status === 401) {
      alert('Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng!')
    } else {
      console.error(err)
      alert('Có lỗi xảy ra khi thêm sản phẩm!')
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

onMounted(() => {
  const urlParams = new URLSearchParams(window.location.search)
  searchKeyword.value = urlParams.get('search') || ''
  fetchProducts(1)
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
  gap: 1rem;
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
.sort-btn {
  z-index: 1; /* Hoặc auto */
  position: relative; /* đảm bảo z-index có tác dụng */
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


</style>
