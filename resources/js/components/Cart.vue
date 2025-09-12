<template>
  <div class="container py-4">
    <h3 class="mb-4">Giỏ hàng của bạn</h3>

    <div class="row">
      <!-- Bảng giỏ hàng bên trái -->
      <div class="col-md-8">
        <!-- Nếu giỏ hàng trống -->
        <div v-if="cart.length === 0" class="alert alert-info">
          Giỏ hàng của bạn đang trống.
        </div>

        <!-- Danh sách giỏ hàng -->
        <table v-else class="table align-middle">
          <thead class="table-light">
            <tr>
              <th colspan="2">
                <div class="d-flex align-items-center">
                  <input 
                    type="checkbox" 
                    v-model="selectAll" 
                    @change="toggleSelectAll" 
                    class="checkbox-lg me-2"
                  />
                  <span class="fw-bold">Chọn tất cả ({{ cart.length }})</span>
                </div>
              </th>
              <th>Số lượng</th>
              <th>Tổng</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in cart" :key="item.id">
              <!-- Checkbox -->
              <td>
                <input 
                  type="checkbox" 
                  v-model="selectedItems" 
                  :value="item.product_id" 
                  class="checkbox-lg"
                />
              </td>

              <!-- Gom ảnh + tên + giá -->
              <td>
                <div class="d-flex align-items-center">
                  <img
                    :src="'/storage/' + item.product.images"
                    alt="Ảnh sản phẩm"
                    class="img-thumbnail me-2"
                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;"
                  />
                  <div>
                    <a :href="`/products/${item.product.id}`" class="fw-bold d-block">
                      {{ item.product.name }}
                    </a>
                    <small class="text-muted">Giá: {{ formatPrice(item.price) }} đ</small><br />
                    <small class="text-muted">Kho: {{ item.product.quantity }}</small>
                  </div>
                </div>
              </td>

              <!-- Số lượng -->
              <td style="width: 100px;">
                <input
                  type="number"
                  class="form-control form-control-sm"
                  v-model.number="item.quantity"
                  min="0"
                  @change="updateQuantity(item)"
                />
              </td>

              <!-- Tổng -->
              <td class="fw-bold">{{ formatPrice(item.price * item.quantity) }} đ</td>

              <!-- Hành động -->
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
      </div>

      <!-- Cột tổng tiền bên phải -->
      <div class="col-md-4">
  <div class="card shadow-sm p-3">
    <!-- Danh sách khuyến mãi -->
    <h5 class="fw-bold mb-3">Khuyến mãi hiện có:</h5>
    <ul class="list-unstyled mb-3">
      <li v-for="item in vouchers" :key="item.id" class="mb-2">
        <span class="badge bg-success">{{ item.code }}</span> 
        - Giảm {{ item.sale }}% (tối đa {{ formatPrice(item.max_discount) }}đ)
      </li>
    </ul>

    <!-- Ô nhập mã khuyến mãi -->
    <div class="input-group mb-3">
      <input 
        type="text" 
        v-model="voucherCode" 
        class="form-control" 
        placeholder="Nhập mã khuyến mãi"
      />
      <button class="btn btn-outline-primary" @click="applyVoucher">
        Áp dụng
      </button>
    </div>

    <hr />

    <!-- Tổng tiền -->
    <div class="d-flex justify-content-between">
      <span>Tạm tính:</span>
      <span>{{ formatPrice(totalSelected) }} đ</span>
    </div>
    <div v-if="discount > 0" class="d-flex justify-content-between text-success">
      <span>Giảm giá:</span>
      <span>- {{ formatPrice(discount) }} đ</span>
    </div>
    <div class="d-flex justify-content-between fw-bold fs-5 mt-2">
      <span>Thành tiền:</span>
      <span>{{ formatPrice(finalTotal) }} đ</span>
    </div>

    <button class="btn btn-primary w-100 mt-3" @click="checkout">
      Thanh toán
    </button>
  </div>
</div>
    </div>
  </div>
</template>



<script setup>
import { ref, onMounted, computed, watch } from "vue"
import axios from "axios"
import { eventBus } from '../eventBus'

const cart = ref([])
const selectedItems = ref([])
const selectAll = ref(false)

const vouchers = ref([])
const voucherCode = ref("")
const appliedVoucher = ref(null)
const discount = ref(0)

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
const fetchVouchers = async () => {
  try {
    const res = await axios.get("/api/vouchers/active")
    vouchers.value = res.data
  } catch (err) {
    console.error("Lỗi khi lấy voucher:", err)
  }
}
const applyVoucher = () => {
  const voucher = vouchers.value.find(v => v.code === voucherCode.value.trim())
  if (!voucher) {
    alert("❌ Mã khuyến mãi không hợp lệ")
    appliedVoucher.value = null
    discount.value = 0
    return
  }

  // Tính số tiền giảm
// const maxDiscount = voucher.max_discount
 const percentDiscount = (totalSelected.value * voucher.sale) / 100
discount.value = Math.min(percentDiscount, voucher.max_discount, totalSelected.value)

  appliedVoucher.value = voucher
  alert(`✅ Áp dụng mã ${voucher.code} thành công!`)
}

const finalTotal = computed(() =>
  Math.max(totalSelected.value - discount.value, 0)
)
const total = computed(() =>
  cart.value.reduce((sum, item) => sum + item.price * item.quantity, 0)
)

const totalSelected = computed(() =>
  cart.value
    .filter(item => selectedItems.value.includes(item.product_id))
    .reduce((sum, item) => sum + item.price * item.quantity, 0)
)
const toggleSelectAll = () => {
  if (selectAll.value) {
    selectedItems.value = cart.value.map(item => item.product_id)
  } else {
    selectedItems.value = []
  }
}

watch([selectedItems, () => appliedVoucher.value], () => {
  if (!appliedVoucher.value) {
    discount.value = 0
    return
  }
  const voucher = appliedVoucher.value
  const percentDiscount = (totalSelected.value * voucher.sale) / 100
  discount.value = Math.min(percentDiscount, voucher.max_discount, totalSelected.value)
})

const updateQuantity = async (item) => {
  if (item.quantity > item.product.quantity) {
    alert(`Số lượng vượt quá tồn kho! Chỉ còn ${item.product.quantity} sản phẩm.`)
    item.quantity = item.product.quantity
    return
  }
  try {
    await axios.put(`/api/cart/${item.product_id}`, {
      quantity: item.quantity,
    })
  } catch (err) {
    console.error(err)
    alert("Lỗi khi cập nhật số lượng!")
    fetchCart()
  }
}

const removeFromCart = async (productId) => {
  if (!confirm("Bạn có chắc muốn xóa sản phẩm này?")) return
  try {
    await axios.delete(`/api/cart/${productId}`)
    cart.value = cart.value.filter((i) => i.product_id !== productId)
  } catch (err) {
    console.error(err)
    alert("Lỗi khi xóa sản phẩm!")
  }
}
const checkout = async () => {
  if (selectedItems.value.length === 0) {
    alert("Vui lòng chọn sản phẩm để thanh toán!")
    return
  }

  // Chuẩn bị data gửi lên API
  const orderData = cart.value
    .filter(item => selectedItems.value.includes(item.product_id))
    .map(item => ({
      product_id: item.product_id,
      quantity: item.quantity,
      price: item.price
    }))

  
  const payload = {
    products: orderData,
    total_price: finalTotal.value
  }

  try {
    // const res = await axios.post("/api/order", payload)
    const res = await axios.post("/api/order",payload)
    alert("✅ Tạo đơn hàng thành công!")
    const order_id = res.data.order_id
     
    window.location.href = `/order/update/${order_id}`
    // Sau khi tạo đơn xong có thể xóa các sản phẩm đã đặt khỏi giỏ
    
    fetchCart()
    selectedItems.value = []
    voucherCode.value = ""
    appliedVoucher.value = null
    discount.value = 0
  } catch (err) {
    console.error('Loixoi:'+err)
    alert("Lỗi khi tạo đơn hàng!")
  }
}


const formatPrice = (price) => {
  return new Intl.NumberFormat("vi-VN").format(price)
}


onMounted(() => {
  fetchCart()
   fetchVouchers()
  eventBus.on('cart-add', fetchCart)
})
</script>

<style>
/* Bỏ đường kẻ bảng */
.table {
  border: none !important;
}
.table th, .table td {
  border: none !important;
}
.checkbox-lg {
  width: 20px;
  height: 20px;
  cursor: pointer;
}
th, td {
  vertical-align: middle !important;
}

td .fw-bold {
  font-size: 15px;
}
</style>
