<template>
  <div>
    <!-- Chọn tất cả -->
    <div class="mb-2">
      <input type="checkbox" id="select-all" v-model="selectAll" @change="toggleSelectAll">
      <label for="select-all">Chọn tất cả</label>
    </div>

    <!-- Bảng danh sách -->
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th></th>
          <th>STT</th>
          <th>Ảnh</th>
          <th>Tên sản phẩm</th>
          <th>Giá</th>
          <th>Khuyến mãi</th>
          <th>Số lượng còn</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item,index) in wishlists.data" :key="item.id">
          <td>
            <input type="checkbox" v-model="selected" :value="item.id">
          </td>
          <td>{{ index + 1 + (wishlists.current_page - 1) * wishlists.per_page }}</td>
          <td>
            <img :src="item.images ? `/storage/${item.images}` : '/storage/products/default.png'" 
                 alt="product" width="60" height="60">
          </td>
          <td>
            <a :href="`/products/${item.id}`" class="text-decoration-none">
              {{ item.name }}
            </a>
          </td>
          <td>{{ formatPrice(item.price) }} đ</td>
          <td>
            <span v-if="item.sale">{{ item.sale }}%</span>
            <span v-else>-</span>
          </td>
          <td>{{ item.quantity }}</td>
          <td>
  <!-- Nút Thêm giỏ hàng -->
  <button  v-if="isLoggedIn" class="btn btn-sm btn-primary me-1" @click="addToCart(item)">
    <i class="bi bi-cart-plus"></i>
  </button>

  <!-- Nút Hủy thích -->
  <button class="btn btn-sm btn-danger" @click="removeFromWishlist(item.id)">
    <i class="bi bi-trash"></i>
  </button>
</td>
        </tr>
      </tbody>
    </table>

    <!-- Phân trang -->
    <nav v-if="wishlists.last_page > 1">
      <ul class="pagination">
        <li class="page-item" :class="{disabled: wishlists.current_page === 1}">
          <a href="#" class="page-link" @click.prevent="fetchWishlists(wishlists.current_page - 1)">«</a>
        </li>
        <li v-for="page in wishlists.last_page" :key="page" class="page-item" :class="{active: wishlists.current_page === page}">
          <a href="#" class="page-link" @click.prevent="fetchWishlists(page)">{{ page }}</a>
        </li>
        <li class="page-item" :class="{disabled: wishlists.current_page === wishlists.last_page}">
          <a href="#" class="page-link" @click.prevent="fetchWishlists(wishlists.current_page + 1)">»</a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { eventBus } from '../eventBus'

const wishlists = ref({ data: [], current_page: 1, last_page: 1 })
const selected = ref([])
const selectAll = ref(false)

const isLoggedIn=ref(false)

// Lấy dữ liệu wishlist
async function fetchWishlists(page = 1) {
  try {
    const res = await fetch(`/api/wishlist?page=${page}`)
    const data = await res.json()
    wishlists.value = data
  } catch (err) {
    console.error(err)
  }
}
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
// Thêm sản phẩm vào giỏ hàng
function addToCart(product) {
  alert(`Đã thêm ${product.name} vào giỏ hàng!`)
}

// Xóa sản phẩm khỏi wishlist
async function removeFromWishlist(productId) {
  try {
    await fetch(`/api/wishlist/${productId}`, {
      method: 'DELETE',
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    // reload
    fetchWishlists(wishlists.value.current_page)
  } catch (err) {
    console.error(err)
  }
}

// Chọn tất cả checkbox
function toggleSelectAll() {
  if (selectAll.value) {
    selected.value = wishlists.value.data.map(item => item.id)
  } else {
    selected.value = []
  }
}

// Format giá
function formatPrice(num) {
  return new Intl.NumberFormat('vi-VN').format(num)
}

onMounted(() => {
  fetchWishlists()
  eventBus.on('wishlist-updated', fetchWishlists)
  checkLogin()
})
</script>

<style scoped>
.table img {
  object-fit: cover;
  border-radius: 4px;
}
</style>
