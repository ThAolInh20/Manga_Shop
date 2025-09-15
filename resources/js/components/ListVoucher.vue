<template>
  <div class="voucher-input">
    <div class="input-group mb-2">
      <input 
        type="text"
        v-model="voucherCode"
        class="form-control"
        placeholder="Nhập mã voucher"
      />
      <button class="btn btn-outline-primary" @click="applyVoucher">
        Áp dụng
      </button>
    </div>

    <!-- Gợi ý voucher -->
    <ul v-if="filteredVouchers.length" class="list-group list-group-flush mb-2">
      <li 
        v-for="v in filteredVouchers"
        :key="v.code"
        class="list-group-item list-group-item-action"
        @click="selectVoucher(v.code)"
        style="cursor: pointer;"
      >
        {{ v.code }} - Giảm {{ v.sale }}% - Tối đa {{ formatPrice(v.max_discount)}}đ
      </li>
    </ul>
    
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import axios from "axios"

const props = defineProps({
  orderId: { type: [String, Number], required: true },
  initialVoucher: { type: Object, default: null }
})

const emit = defineEmits(['applied'])

const voucherCode = ref(props.initialVoucher?.code || "")
const vouchers = ref([])
const filteredVouchers = ref([])

// Fetch danh sách voucher active
const fetchVouchers = async () => {
  try {
    const res = await axios.get("/api/vouchers/active")
    vouchers.value = res.data || []
    filteredVouchers.value = [...vouchers.value]
  } catch (err) {
    console.error("Lỗi lấy danh sách voucher:", err)
  }
}

// Lọc voucher theo input
const filterVoucherList = () => {
  const code = voucherCode.value.toUpperCase()
  filteredVouchers.value = vouchers.value.filter(v => v.code.includes(code))
}

// Áp dụng voucher
const applyVoucher = async () => {
  if (!voucherCode.value) return
 
  try {
     console.log(voucherCode.value)
    const res = await axios.post(`/api/order/${props.orderId}/apply-voucher`, {
      voucher_code: voucherCode.value
    })

    if (res.data.success) {
      emit("applied", res.data.voucher) // gửi voucher mới về parent
      alert(`Áp dụng voucher ${res.data.voucher.code} thành công`)
    } else {
      emit("applied", null)
      alert(res.data.message)
    }
  } catch (err) {
    console.error(err)
    emit("applied", null)
    alert("Không áp dụng được voucher")
  }
}

// Chọn voucher từ gợi ý
const selectVoucher = (code) => {
  voucherCode.value = code
  applyVoucher()
}
const formatPrice = (price) => new Intl.NumberFormat("vi-VN").format(price)


onMounted(fetchVouchers)
</script>
