<template>
  <div class="container py-4">
    <h3 class="mb-4">Giỏ hàng của bạn</h3>

    <!-- Nếu giỏ hàng trống -->
    <div v-if="cart.length === 0" class="alert alert-info">
      Giỏ hàng của bạn đang trống.
    </div>

    <!-- Danh sách giỏ hàng -->
    <table v-else class="table table-bordered align-middle">
      <thead class="table-light">
        <tr>
          <th>STT</th>
          <th>Sản phẩm</th>
          <th>Giá đơn lẻ</th>
          <th>Số lượng trong kho</th>
          <th>Số lượng</th>
          <th>Tổng</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in cart" :key="item.id">
          <td>{{ index + 1 }}</td>
          <td>
            <img
              :src="'/storage/' + item.product.images"
              alt="Ảnh sản phẩm"
              class="img-thumbnail me-2"
              style="width: 60px; height: 60px; object-fit: cover;"
            />
            <a :href="`/products/${item.product.id}`">{{ item.product.name }}</a>
           
        
          </td>
          <td>{{ formatPrice(item.price) }} đ</td>
          <td>{{ item.product.quantity }}</td>  
          <td style="width: 140px;">
            <input
              type="number"
              class="form-control form-control-sm"
              v-model.number="item.quantity"
              min="0"
              @change="updateQuantity(item)"
            />
          </td>
          <td>{{ formatPrice(item.price * item.quantity) }} đ</td>
          <td>
            <button
              class="btn btn-sm btn-danger"
              @click="removeFromCart(item.product_id)"
            >
              <i class="bi bi-trash"></i> Xóa
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Tổng cộng -->
    <div v-if="cart.length > 0" class="text-end fw-bold fs-5 mt-3">
      Tổng cộng: {{ formatPrice(total) }} đ
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue"
import axios from "axios"
import { eventBus } from '../eventBus'


const cart = ref([])

// Tải giỏ hàng khi load trang
const fetchCart = async () => {
  try {
    const res = await axios.get("/api/cart")
    cart.value = res.data
  } catch (err) {
    if (err.response?.status === 401) {
      alert("⚠️ Bạn cần đăng nhập để xem giỏ hàng!")
    }
  }
}

// Cập nhật số lượng
const updateQuantity = async (item) => {
    if (item.quantity > item.product.quantity) {
    alert(`Số lượng vượt quá tồn kho! Chỉ còn ${item.product.quantity} sản phẩm.`)
    item.quantity = item.product.quantity // reset về max stock
    return
  }
  try {
    await axios.put(`/api/cart/${item.product_id}`, {
      quantity: item.quantity,
    })
  } catch (err) {
    console.error(err)
    alert("Lỗi khi cập nhật số lượng!")
  }
}

// Xóa khỏi giỏ
const removeFromCart = async (productId) => {
  if (!confirm("Bạn có chắc muốn xóa sản phẩm này?")) return
  try {
    await axios.delete(`/api/cart/${productId}`)
    cart.value = cart.value.filter((i) => i.product_id !== productId)
  } catch (err) {
    console.error(err)
    alert("❌ Lỗi khi xóa sản phẩm!")
  }
}

// Format giá
const formatPrice = (price) => {
  return new Intl.NumberFormat("vi-VN").format(price)
}

// Tính tổng
const total = computed(() =>
  cart.value.reduce((sum, item) => sum + item.price * item.quantity, 0)
)

onMounted(() => {
  fetchCart()
  // 🔥 Lắng nghe sự kiện từ Search
  eventBus.on('wishlist-updated', fetchCart)
 
})
</script>
