<template>
  <div class="container py-4">
    <h3 class="mb-4">Chi tiết đơn hàng #{{ orderId }}</h3>

    <!-- Loading -->
    <div v-if="loading" class="alert alert-info text-center">Đang tải...</div>

    <!-- Không có đơn -->
    <div v-else-if="!order" class="alert alert-warning text-center">
      Không tìm thấy đơn hàng
    </div>

    <!-- Thông tin đơn hàng -->
    <div v-else>
      <!-- Grid hiển thị 4 thẻ ngang hàng -->
      <div class="row g-3 mb-4">
        <!-- Đơn hàng -->
        <div class="col-md-3">
          <div class="card shadow-sm h-100">
            <div class="card-body">
              <p><strong>Mã đơn:</strong> #{{ order.id }}</p>
              <p><strong>Trạng thái:</strong> {{ getStatusText(order.order_status) }}</p>
              <p><strong>Cập nhật lúc:</strong> {{ formatDate(order.updated_at) }}</p>

              <p><strong>Ngày đặt:</strong> {{ formatDate(order.created_at) }}</p>
            </div>
          </div>
        </div>

        <!-- Người nhận -->
        <div class="col-md-3">
          <div class="card shadow-sm h-100">
            <div class="card-header bg-light fw-bold">Người nhận</div>
            <div class="card-body">
              <p><strong>Tên:</strong> {{order.shipping? order.shipping.name_recipient : "-" }}</p>
              <p><strong>ĐT:</strong> {{order.shipping? order.shipping.phone_recipient : "-" }}</p>
              <p><strong>Địa chỉ:</strong> {{order.shipping? order.shipping.shipping_address : "-" }}</p>
            </div>
          </div>
        </div>

        <!-- Thanh toán -->
        <div class="col-md-3">
          <div class="card shadow-sm h-100">
            <div class="card-header bg-light fw-bold">Thanh toán</div>
            <div class="card-body">
              <p v-if="order.order_status != 0&&order.order_status != 5" >
                {{ order.payment_status === 1 ? "Thanh toán online" : "Trả tiền mặt" }}
              </p>
              <p v-else>
                  Chưa thanh toán
              </p>
              
              <p v-if="order.voucher"><strong>Voucher:</strong> {{ order.voucher.code|| "Không có" }} giảm {{order.voucher.sale  }}% tối đa {{ formatPrice(order.voucher.max_discount) }}đ</p>
            </div>
          </div>
        </div>

        <!-- Tổng kết -->
        <!-- Tổng kết -->
<div class="col-md-3">
  <div class="card shadow-sm h-100">
    <div class="card-body">
      <p class="mb-1"><strong>Tạm tính:</strong> {{ formatPrice(order.subtotal_price) }} đ</p>
      <p class="mb-1"><strong>Phí ship:</strong> {{ order.shipping ? formatPrice(order.shipping.shipping_fee || 0) : 0 }} đ</p>
      <p v-if="order.voucher"><strong>Giảm giá:</strong> -{{ formatPrice(discount) }}</p>
      <hr>
      <h6 class="text-danger fw-bold">Tổng: {{ formatPrice(Number(order.total_price || 0)) }} đ</h6>

      <!-- Nút hành động -->
      <div class="d-flex gap-2 flex-wrap mt-2">
        <!-- Thanh toán -->
        <a
          v-if="order.order_status === 0"
          :href="`/order/checkout/${order.id}`"
          class="btn btn-sm btn-primary"
        >
          Thanh toán
        </a>

        <!-- Hủy đơn -->
        <button
          v-if="order.order_status === 0"
          class="btn btn-sm btn-outline-danger"
          @click="openCancelModal(order.id)"
        >
          Hủy đơn
        </button>

        <!-- Mua lại -->
        <button
          v-if="order.order_status === 5"
          class="btn btn-sm btn-outline-secondary"
          @click="recallOrder(order.id)"
        >
          Mua lại
        </button>

        <!-- Xác nhận nhận hàng -->
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

      <!-- Danh sách sản phẩm -->
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-light fw-bold">Sản phẩm</div>
        <div class="list-group list-group-flush">
          <div 
            v-for="item in order.product_orders" 
            :key="item.id" 
            class="list-group-item d-flex align-items-center"
          >
            <!-- Ảnh sản phẩm -->
            <img 
              :src="item.product.images ? `/storage/${item.product.images}` : '/storage/products/default.png'"
              alt="product" 
              class="me-3 rounded" 
              style="width: 60px; height: 60px; object-fit: cover;"
            >
            <!-- <img
            :src="item.product.images ? `/storage/${product.images}` : '/storage/products/default.png'"
            class="card-img-top product-img"
            alt="product"
            @click="viewDetail(product)"
            style="cursor: pointer"
          > -->
            <!-- Thông tin sản phẩm -->
            <div class="flex-grow-1">
              <h6 class="mb-1">{{ item.product?.name || "Sản phẩm không khả dụng" }}</h6>
              <small class="text-muted">SL: {{ item.quantity }}</small>
            </div>
            <!-- Giá -->
            <div class="text-end">
              <p class="mb-1 text-danger">{{ formatPrice(item.product?.price_sale || 0) }} đ</p>
            </div>
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
import { ref, onMounted, watch, computed } from "vue"
import axios from "axios"

const props = defineProps({
  orderId: {
    type: [String, Number],
    required: true
  }
})
const reasons = [
  "Đặt nhầm sản phẩm",
  "Muốn thay đổi địa chỉ",
  "Tìm được giá rẻ hơn",
  "Thay đổi ý định mua",
  "Khác"
]
const order = ref(null)
const loading = ref(false)

const cancelReason = ref("")
const cancelOther = ref("")
const orderIdToCancel = ref(null)

const fetchOrderDetail = async () => {
  loading.value = true
  try {
    const res = await axios.get(`/api/order/${props.orderId}`)
    order.value = res.data.order
  } catch (err) {
    console.error(err)
    order.value = null
  } finally {
    loading.value = false
  }
}

const formatPrice = (price) => new Intl.NumberFormat("vi-VN").format(price)

const formatDate = (datetime) => {
  if (!datetime) return "-"
  return new Date(datetime).toLocaleDateString("vi-VN", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric"
  })
}

const getStatusText = (status) => {
  const statuses = {
    0: "Chờ thanh toán",
    1: "Đang xử lý",
    2: "Đang giao",
    3: "Hoàn tất",
    4: "Đổi trả",
    5: "Đã hủy"
  }
  return statuses[status] || "Không xác định"
}

const discount = computed(() => {
  if (!order.value || !order.value.voucher) return 0
  const sale = order.value.voucher.sale || 0
  const maxDiscount = order.value.voucher.max_discount || 0
  const subtotal = order.value.subtotal_price || 0
  const discountValue = (subtotal * sale) / 100
  return Math.min(discountValue, maxDiscount)
})

// Xác nhận nhận hàng
const updateOrderStatus = async (orderId, statusWant) => {
  try {
    const res = await axios.post(`/api/order/${orderId}/status`, {
      status_want: statusWant
    })
    alert(res.data.message)
    fetchOrderDetail()
  } catch (err) {
    console.error(err)
    alert(err.response?.data?.message || "❌ Lỗi khi cập nhật trạng thái!")
  }
}

// Mua lại đơn hàng
const recallOrder = async (orderId) => {
  if (!confirm("Bạn có chắc muốn mua lại đơn hàng này?")) return
  try {
    await axios.post(`/api/order/${orderId}/recall`)
    fetchOrderDetail()

    const payOnline = confirm("Bạn có muốn thanh toán bây giờ không?")
    if (payOnline) {
      window.location.href = `/order/checkout/${orderId}`
    }
  } catch (err) {
    console.error(err)
    alert(err.response?.data?.message || "Đã có lỗi xảy ra!")
  }
}

// Mở modal hủy đơn
const openCancelModal = (orderId) => {
  orderIdToCancel.value = orderId
  cancelReason.value = ""
  cancelOther.value = ""
  const modal = new bootstrap.Modal(document.getElementById("cancelModal"))
  modal.show()
}

// Xác nhận hủy đơn
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
    fetchOrderDetail()
    bootstrap.Modal.getInstance(document.getElementById("cancelModal")).hide()
  } catch (err) {
    console.error(err)
    alert("❌ Lỗi khi hủy đơn!")
  }
}

onMounted(fetchOrderDetail)
watch(() => props.orderId, fetchOrderDetail)
</script>

