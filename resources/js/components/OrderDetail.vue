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
              <p><strong>Ngày đặt:</strong> {{ formatDate(order.created_at) }}</p>
            </div>
          </div>
        </div>

        <!-- Người nhận -->
        <div class="col-md-3">
          <div class="card shadow-sm h-100">
            <div class="card-header bg-light fw-bold">Người nhận</div>
            <div class="card-body">
              <p><strong>Tên:</strong> {{ order.name_recipient || "-" }}</p>
              <p><strong>ĐT:</strong> {{ order.phone_recipient || "-" }}</p>
              <p><strong>Địa chỉ:</strong> {{ order.shipping_address || "-" }}</p>
            </div>
          </div>
        </div>

        <!-- Thanh toán -->
        <div class="col-md-3">
          <div class="card shadow-sm h-100">
            <div class="card-header bg-light fw-bold">Thanh toán</div>
            <div class="card-body">
              <p>
                {{ order.payment_status === 1 ? "Thanh toán online" : "Trả tiền mặt" }}
              </p>
              <p><strong>Voucher:</strong> {{ order.voucher.code|| "Không có" }} giảm {{order.voucher.sale  }}% tối đa {{ formatPrice(order.voucher.max_discount) }}đ</p>
            </div>
          </div>
        </div>

        <!-- Tổng kết -->
        <div class="col-md-3">
          <div class="card shadow-sm h-100">
            <div class="card-body text-end">
              <p class="mb-1"><strong>Tạm tính:</strong> {{ formatPrice(order.total_price) }} đ</p>
              <p class="mb-1"><strong>Phí ship:</strong> {{ formatPrice(order.shipping_fee || 0) }} đ</p>
              <!-- <p class="mb-1"><strong>Giảm giá:</strong> {{ order.voucher.sale}} %</p> -->
              <hr>
              <h6 class="text-danger fw-bold">
                Tổng: {{ formatPrice(Number(order.total_price || 0) + Number(order.shipping_fee || 0)) }} đ
              </h6>
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
              :src="item.product?.image_url || '/images/no-image.png'" 
              alt="product" 
              class="me-3 rounded" 
              style="width: 60px; height: 60px; object-fit: cover;"
            >
            <!-- Thông tin sản phẩm -->
            <div class="flex-grow-1">
              <h6 class="mb-1">{{ item.product?.name || "Sản phẩm không khả dụng" }}</h6>
              <small class="text-muted">SL: {{ item.quantity }}</small>
            </div>
            <!-- Giá -->
            <div class="text-end">
              <p class="mb-1 text-danger">{{ formatPrice(item.product?.price || 0) }} đ</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted, watch } from "vue"
import axios from "axios"

const props = defineProps({
  orderId: {
    type: [String, Number],
    required: true
  }
})

const order = ref(null)
const loading = ref(false)

const fetchOrderDetail = async () => {
  loading.value = true
  try {
    const res = await axios.get(`/api/order/${props.orderId}`)
    order.value = res.data.order
    console.log(order.value)
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

onMounted(fetchOrderDetail)
watch(() => props.orderId, fetchOrderDetail)
</script>
