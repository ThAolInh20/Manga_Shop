<template>
  <div class="container py-4">
    <h3 class="mb-4">Đơn hàng của bạn</h3>

    <!-- Bộ lọc trạng thái -->
    <div class="mb-3 d-flex gap-2 flex-wrap">
      <button 
        class="btn btn-outline-primary"
        :class="{ active: filterStatus === null }"
        @click="filterStatus = null; fetchOrders()"
      >
        Tất cả
      </button>
      <button 
        v-for="s in statuses" 
        :key="s.value" 
        class="btn btn-outline-primary"
        :class="{ active: filterStatus === s.value }"
        @click="filterStatus = s.value; fetchOrders()"
      >
        {{ s.text }}
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="alert alert-info text-center">
      Đang tải đơn hàng...
    </div>

    <!-- Không có đơn hàng -->
    <div v-else-if="orders.length === 0" class="alert alert-warning text-center">
      Bạn chưa có đơn hàng nào.
    </div>

    <!-- Danh sách đơn hàng -->
    <div v-else>
      <div v-for="order in orders" :key="order.id" class="card mb-4 shadow-sm">
        <!-- Header đơn hàng -->
        <div class="card-header d-flex justify-content-between flex-wrap align-items-center">
          <div>
            <strong>Mã đơn hàng:</strong> #{{ order.id }} <br>
            <strong>Trạng thái:</strong> {{ getStatusText(order.order_status) }}
          </div>
          <div>
            <strong>Ngày đặt:</strong> {{ formatDate(order.created_at) }} <br>
            <strong>Giờ đặt:</strong> {{ formatTime(order.created_at) }}
          </div>
        </div>

        <hr class="my-1">

        <!-- Body: thông tin sản phẩm và tổng -->
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
          <div>
            <strong>Số sản phẩm:</strong> {{ order.product_count }}
          </div>
          <div>
            <strong>Tổng tiền:</strong> {{ formatPrice(order.total_price) }} đ
          </div>
          <div>
            <button 
              class="btn btn-danger btn-sm"
              :disabled="order.order_status !== 0 && order.order_status !== 1"
              @click="cancelOrder(order.id)"
            >
              Hủy đơn
            </button>
             <!-- Nút xác nhận đã nhận hàng -->
  <button 
    class="btn btn-success btn-sm"
    v-if="order.order_status === 2"
    @click="updateOrderStatus(order.id, 3)" 
  >
    Đã nhận được hàng
  </button>

  <!-- Nút đổi trả -->
  <button 
    class="btn btn-warning btn-sm"
    v-if="order.order_status === 3"
    @click="updateOrderStatus(order.id, 4)" 
  >
    Đổi trả
  </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import axios from "axios"

const orders = ref([])
const loading = ref(false)
const filterStatus = ref(null)

const statuses = [
  {value: 0, text:"Chờ thanh toán"},
  { value: 1, text: "Đang xử lý" },
  { value: 2, text: "Đang giao" },
  { value: 3, text: "Hoàn tất" },
  { value: 4, text: "Đổi trả" },
  { value: 5, text: "Đã hủy" },
  
]

const fetchOrders = async () => {
  loading.value = true
  try {
    let url = "/api/user/orders"
    if (filterStatus.value !== null) {
      url += `?status=${filterStatus.value}`
    }
    const res = await axios.get(url)
    orders.value = res.data.orders
  } catch (err) {
    console.error(err)
    alert("❌ Lỗi khi tải đơn hàng!")
  } finally {
    loading.value = false
  }
}
const getStatusText = (status) => {
  const statusObj = statuses.find(s => s.value === status)
  return statusObj ? statusObj.text : "Không xác định"
}
const cancelOrder = async (orderId) => {
  if (!confirm("Bạn có chắc muốn hủy đơn hàng này?")) return
  try {
    await axios.post(`/api/order/${orderId}/cancel`)
    alert("✅ Hủy đơn thành công!")
    fetchOrders()
  } catch (err) {
    console.error(err)
    alert("❌ Lỗi khi hủy đơn!")
  }
}
const updateOrderStatus = async (orderId, statusWant) => {
  try {
    const res = await axios.post(`/api/order/${orderId}/status`, {
      status_want: statusWant
    })
    alert(res.data.message)
    fetchOrders() // load lại danh sách đơn hàng
  } catch (err) {
    console.error(err)
    alert(err.response?.data?.message || "❌ Lỗi khi cập nhật trạng thái!")
  }
}

const formatPrice = (price) => {
  return new Intl.NumberFormat("vi-VN").format(price)
}

const formatDate = (datetime) => {
  if (!datetime) return "-"
  return new Date(datetime).toLocaleDateString("vi-VN", { day: "2-digit", month: "2-digit", year: "numeric" })
}

const formatTime = (datetime) => {
  if (!datetime) return "-"
  return new Date(datetime).toLocaleTimeString("vi-VN", { hour: "2-digit", minute: "2-digit" })
}

onMounted(() => {
  fetchOrders()
})
</script>

<style>
.card-header {
  font-weight: 600;
}
.btn.active {
  background-color: #0d6efd;
  color: white;
}
</style>
