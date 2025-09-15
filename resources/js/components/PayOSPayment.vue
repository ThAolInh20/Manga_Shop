<template>
  <div class="payos-payment">
    <h5>Thanh toán qua PayOS</h5>

    <div v-if="!qrCode">
      <button class="btn btn-primary" @click="createPayOSPayment">
        Tạo QR & Thanh toán
      </button>
    </div>

    <div v-if="qrCode" class="mt-3">
      <p>Quét QR để thanh toán:</p>
      <img :src="qrCode" alt="QR" />
    </div>

    <div v-if="success" class="alert alert-success mt-3">
      {{ success }}
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue"
import axios from "axios"

const props = defineProps({
  orderId: { type: Number, required: true },
  totalAmount: { type: Number, required: true },
})

const qrCode = ref(null)
const success = ref(null)

const createPayOSPayment = async () => {
    console.log("Tạo thanh toán PayOS cho đơn:", props.orderId, "Số tiền:", props.totalAmount)
  try {
    const res = await axios.post("/api/order/payos/create", {
      order_id: props.orderId,
      amount: props.totalAmount,
    })
    console.log("Kết quả PayOS:", res.data)

    // PayOS trả về checkoutUrl + qrCode (base64)
   if (res.data?.data?.checkoutUrl) {
      // 🔥 Redirect sang trang thanh toán PayOS
      window.location.href = res.data.data.checkoutUrl
    } else {
      alert("Không nhận được checkoutUrl từ PayOS")
    }
  } catch (err) { 
    console.error("Lỗi tạo thanh toán PayOS:", err)
  }
}
</script>

<style scoped>
.payos-payment img {
  width: 200px;
  height: 200px;
  border: 1px solid #ccc;
}
</style>
