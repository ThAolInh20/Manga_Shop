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
      <!-- Chọn địa chỉ giao hàng -->
      <div class="card shadow-sm mb-3">
        <div class="card-header fw-bold bg-light">Địa chỉ giao hàng</div>
        <div class="card-body">
          <shipping-address 
            :account_id="order.account_id" 
            :order-id="order.id" 
            @address-selected="handleSelectedAddress" 
          />
          <div v-if="!selectedShipping" class="text-danger mt-2">
            ⚠ Vui lòng chọn địa chỉ giao hàng
          </div>
        <p><strong>Phí ship:</strong> {{ formatPrice(selectedShipping?.shipping_fee || 0) }} đ</p>

        </div>
      </div>

      <!-- Voucher + Phí ship -->
      <div class="card shadow-sm mb-3">
        <div class="card-header fw-bold bg-light">Mã giảm giá</div>
        <div class="card-body">
          
          <list-voucher
  :order-id="order.id" 
  :initial-voucher="order.voucher"
  @applied="onVoucherApplied"
/>
          </div>
         
      </div>

      <!-- Danh sách sản phẩm -->
      <div class="card shadow-sm mb-3">
        <div class="card-header fw-bold bg-light">Sản phẩm</div>
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
        <div class="card-footer text-end">
          <strong>Tạm tính:</strong> {{ formatPrice(order.subtotal_price) }} đ
        </div>
      </div>

      <!-- Sticky checkout summary -->
<div class="checkout-summary fixed-bottom bg-white shadow p-3 border-top">
  <div class="d-flex justify-content-between align-items-center flex-wrap">
    
    <!-- Bên trái: Giá -->
    <div class="me-3 flex-grow-1">
      <p class="mb-1"><strong>Tạm tính:</strong> {{ formatPrice(order.subtotal_price) }} đ</p>
      <p class="mb-1"><strong>Giảm giá:</strong> -{{ formatPrice(discount) }} đ</p>
      <p class="mb-1"><strong>Phí ship:</strong> {{ formatPrice(selectedShipping?.shipping_fee || 0) }} đ</p>
      <h5 class="fw-bold text-danger mb-0">Thành tiền: {{ formatPrice(finalTotal) }} đ</h5>
    </div>

    <!-- Bên phải: Chọn phương thức & nút -->
    <div class="ms-3 d-flex align-items-center flex-nowrap">
      <select v-model="paymentMethod" class="form-select me-2" :disabled="!selectedShipping">
        <option value="cod">Thanh toán khi nhận hàng</option>
        <option value="bank">Chuyển khoản ngân hàng</option>
      </select>

      <div v-if="paymentMethod === 'bank' && order && selectedShipping">
        <PayOSPayment 
          :order-id="order.id" 
          :total-amount="finalTotal"
        />
      </div>
      <div v-else>
        <button class="btn btn-primary" @click="confirmCOD()">Thanh toán</button>
      </div>
    </div>
    
  </div>

  <!-- Alert nếu chưa chọn địa chỉ -->
  <div v-if="!selectedShipping" class="alert alert-warning mt-2 mb-0 py-1 text-center">
    🚚 Vui lòng chọn địa chỉ giao hàng trước khi thanh toán
  </div>
</div>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted, watch, computed } from "vue"
import axios from "axios"
import ListVoucher from "./ListVoucher.vue"
// import VoucherInput from './VoucherInput.vue'


const props = defineProps({
  orderId: {
    type: [String, Number],
    required: true
  }
})

const order = ref(null)
const loading = ref(false)
const selectedShipping = ref(null)
// const discount = ref(0)
// const paymentMethod = ref('cod') // default
const paymentMethod = ref('cod')
const subtotal = computed(() => order?.subtotal_price || 0)

const voucherCode = ref(order.voucher?.code || "")

// Khi con emit địa chỉ
const handleSelectedAddress = (addr) => {
  selectedShipping.value = addr
}

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

const discount = computed(() => {
  const currentOrder = order.value
  if (!currentOrder || !currentOrder.voucher) return 0

  const subtotalVal = currentOrder.subtotal_price || 0
  const percentDiscount = (subtotalVal * currentOrder.voucher.sale) / 100
  return Math.min(percentDiscount, currentOrder.voucher.max_discount, subtotalVal)
})



const formatPrice = (price) => new Intl.NumberFormat("vi-VN").format(price)



const onVoucherApplied = (voucher) => {
  order.value.voucher = voucher
   // cập nhật voucher mới
  // console.log(order.value.voucher)
}
const finalTotal = computed(() => {
  const shippingFee = Number(selectedShipping.value?.shipping_fee || 0)
  const sub = Number(order.value.subtotal_price || 0)
  const disc = Number(discount.value || 0)
  return Math.max(sub - disc + shippingFee, 0)

})

const confirmCOD = async () => {
  if (!order.value) return;
  if (!selectedShipping.value) {
    alert("🚚 Vui lòng chọn địa chỉ giao hàng trước khi thanh toán!");
    return;
  }
  try {
    console.log(order)
    const res = await axios.post('/api/order/cod-confirm', {
      order_id: order.value.id
    });

    if (res.data.success) {
      order.value.order_status = 1;
      window.location.reload();
      alert(res.data.message);
    }
  } catch (err) {
    console.error(err);
    alert("Xác nhận COD thất bại.");
  }
};
onMounted(fetchOrderDetail)
watch(() => props.orderId, fetchOrderDetail)
</script>
<style>
.checkout-summary {
  bottom: 0;
  left: 0;
  width: 100%;
  z-index: 1050;
}

.container {
  padding-bottom: 140px; /* đảm bảo content không bị che footer */
}

.checkout-summary p,
.checkout-summary h5 {
  margin: 0;
}

</style>
