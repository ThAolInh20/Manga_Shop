<template>
  <div class="momo-payment">
    <h5>Thanh toán MoMo Dev</h5>

    <div v-if="!qrCode">
      <button class="btn btn-primary" @click="createMomoPayment">
        Tạo QR & Thanh toán
      </button>
    </div>

    <div v-if="qrCode" class="mt-3">
      <p>Quét QR để thanh toán :</p>
      <img :src="qrCode" alt="QR" />
      
      <button class="btn btn-success mt-2" @click="confirmPayment">
        Xác nhận đã thanh toán (dev)
      </button>
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
const payUrl = ref(null)
const success = ref(null)

const createMomoPayment = async () => {
  console.log("Tạo thanh toán MoMo cho đơn:", props.orderId, "Số tiền:", props.totalAmount)
  try {
    const res = await axios.post("/api/order/momo-dev", {
      order_id: props.orderId,
      amount: props.totalAmount,
      order_info: `Thanh toán đơn #${props.orderId}`,
    })
    console.log("Kết quả tạo thanh toán MoMo Dev:", res.data)
    qrCode.value = res.data.qr_url
    // payUrl.value = res.data.payUrl
  } catch (err) {
    console.error("Lỗi tạo thanh toán MoMo Dev:", err)
  }
}

const confirmPayment = async () => {
  try {
    const res = await axios.post("/api/order/momo-dev/confirm", {
      order_id: props.orderId
    })
    success.value = res.data.message
  } catch (err) {
    console.error("Lỗi xác nhận thanh toán:", err)
  }
}
</script>

<style scoped>
.momo-payment img {
  width: 200px;
  height: 200px;
  border: 1px solid #ccc;
}
</style>
