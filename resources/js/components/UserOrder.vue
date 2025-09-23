<template>
  <div class="container py-4">
    <h3 class="mb-4 text-center fw-bold">Đơn hàng của bạn</h3>

    <!-- Bộ lọc trạng thái -->
         <div class="mb-4 text-center">
            <div class="btn-group flex-wrap">
   <button 
  class="btn btn-outline-primary"
  :class="{ active: filterStatus === null }"
  @click="filterStatus = null; fetchOrders()"
>
  Tất cả ({{ Object.values(orderCounts).reduce((a, b) => a + b, 0) }})
</button>

<button 
  v-for="s in statuses" 
  :key="s.value" 
  class="btn btn-outline-primary"
  :class="{ active: filterStatus === s.value }"
  @click="filterStatus = s.value; fetchOrders()"
>
  {{ s.text }} ({{ orderCounts[s.value] || 0 }})
</button>
         </div>
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
      <div v-for="order in orders" :key="order.id" class="card mb-4 shadow-sm border-0 rounded-3">
        <!-- Header -->
        <a 
          :href="`/order/${order.id}`"  
          class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap text-decoration-none text-dark rounded-top"
        >
          <div>
            <strong>Mã đơn:</strong> #{{ order.id }} <br>
            <strong>Trạng thái:</strong> <span class="badge bg-info text-dark">{{ getStatusText(order.order_status) }}</span>
          </div>
          <div class="text-end">
            <strong>Ngày:</strong> {{ formatDate(order.created_at) }} <br>
            <strong>Giờ:</strong> {{ formatTime(order.created_at) }}
          </div>
        </a>
        <hr>
        <!-- Body -->
        <div class="card-body d-flex align-items-center">
          
           <div >
            <strong>Sản phẩm:</strong> {{ order.product_count }}
          </div>

          <!-- Tổng tiền luôn ở giữa, fix cứng -->
          <div class="ms-fixed">
            <strong>Tổng tiền:</strong>
            <span class="text-danger fw-bold">{{ formatPrice(order.total_price) }} đ</span>
          </div>
          <div class="d-flex gap-2 flex-wrap ms-auto">
            <!-- Nút thanh toán -->
            <a 
              v-if="order.order_status === 0" 
              :href="`/order/checkout/${order.id}`" 
              class="btn btn-sm btn-primary"
            >
              Thanh toán
            </a>

            <!-- Nút hủy đơn -->
            <button 
              v-if="order.order_status === 0"
              class="btn btn-sm btn-outline-danger"
              @click="openCancelModal(order.id)"
            >
              Hủy đơn
            </button>

            <!-- Nút mua lại -->
            <button 
              v-if="order.order_status == 5"
              class="btn btn-sm btn-outline-secondary"
              @click="recallOrder(order.id)"
            >
              Mua lại
            </button>

            <!-- Nút xác nhận nhận hàng -->
            <button 
              v-if="order.order_status === 2"
              class="btn btn-sm btn-success"
              @click="updateOrderStatus(order.id, 3)" 
            >
              Đã nhận hàng
            </button>
            <button 
              v-if="order.order_status === 2"
              class="btn btn-sm btn-warning"
              @click="updateOrderStatus(order.id, 4)" 
            >
              Đổi trả
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal hủy đơn -->
  <div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 rounded-3">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Xác nhận hủy đơn</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p class="fw-bold">Vui lòng chọn lý do hủy đơn:</p>
          
          <div class="form-check mb-2" v-for="reason in reasons" :key="reason">
            <input 
              class="form-check-input" 
              type="radio" 
              :id="reason" 
              :value="reason" 
              v-model="cancelReason"
              name="cancelReason"
            >
            <label class="form-check-label" :for="reason">
              {{ reason }}
            </label>
          </div>

          <!-- Nếu chọn Khác -->
          <textarea 
            v-if="cancelReason === 'Khác'" 
            v-model="cancelOther" 
            class="form-control mt-2" 
            placeholder="Nhập lý do khác..."
          ></textarea>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          <button class="btn btn-danger" @click="confirmCancelOrder">Xác nhận hủy</button>
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

const cancelReason = ref("")
const cancelOther = ref("")
const orderIdToCancel = ref(null)
const orderCounts = ref({}) // lưu số lượng theo từng trạng thái

const fetchStats = async () => {
  try {
    const res = await axios.get("/api/orders/stats")
    orderCounts.value = res.data.counts
  } catch (err) {
    console.error(err)
  }
}

const statuses = [
  {value: 0, text:"Chờ thanh toán"},
  { value: 1, text: "Đang xử lý" },
  { value: 2, text: "Đang giao" },
  { value: 3, text: "Hoàn tất" },
  { value: 4, text: "Đổi trả" },
  { value: 5, text: "Đã hủy" },
  {value:6,text:"Hoàn tiền"}
  
]
const reasons = [
  "Đặt nhầm sản phẩm",
  "Muốn thay đổi địa chỉ",
  "Tìm được giá rẻ hơn",
  "Thay đổi ý định mua",
  "Khác"
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
// const cancelOrder = async (orderId) => {
//   if (!confirm("Bạn có chắc muốn hủy đơn hàng này?")) return
//   try {
//     await axios.post(`/api/order/${orderId}/cancel`)
//     alert("✅ Hủy đơn thành công!")
//     fetchOrders()
//   } catch (err) {
//     console.error(err)
//     alert("❌ Lỗi khi hủy đơn!")
//   }
// }
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
const recallOrder = async (orderId) => {
  if (!confirm("Bạn có chắc muốn mua lại đơn hàng này?")) return
  try {
    await axios.post(`/api/order/${orderId}/recall`)
    
    fetchOrders()
    
    const payOnline = confirm("Bạn có muốn thanh toán online ngay bây giờ không?")
    if (payOnline) {
      // Giả sử bạn có route để redirect đến trang checkout
      window.location.href = `/order/checkout/${orderId}`
    }
  } catch (err) {
    console.error(err)
    alert(err.response?.data?.message || "Đã có lỗi xảy ra!")
    
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
const openCancelModal = (orderId) => {
  orderIdToCancel.value = orderId
  cancelReason.value = ""
  cancelOther.value = ""
  const modal = new bootstrap.Modal(document.getElementById("cancelModal"))
  modal.show()
}
const confirmCancelOrder = async () => {
  if (!cancelReason.value) {
    alert("⚠️ Vui lòng chọn lý do hủy!")
    return
  }

  const reasonText = cancelReason.value === "Khác" ? cancelOther.value : cancelReason.value
  console.log("📌 Lý do hủy:", reasonText) // chỉ log ra, không lưu DB

  try {
    await axios.post(`/api/order/${orderIdToCancel.value}/cancel`)
    alert("✅ Hủy đơn thành công!")
    fetchOrders()
    bootstrap.Modal.getInstance(document.getElementById("cancelModal")).hide()
  } catch (err) {
    console.error(err)
    alert("❌ Lỗi khi hủy đơn!")
  }
}
const countByStatus = (status) => {
  return orders.value.filter(o => o.order_status === status).length
}
onMounted(() => {
  fetchOrders()
  fetchStats()
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
.ms-fixed {
  margin-left: 300px; /* cách trái 300px */
}
</style>
