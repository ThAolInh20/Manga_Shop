<template>
  <div class="profile-container container py-4">
    <h3 class="mb-4">Hồ sơ cá nhân</h3>

    <div v-if="loading" class="alert alert-info">Đang tải...</div>
    <div v-if="error" class="alert alert-danger">{{ error }}</div>

    <form v-if="account" @submit.prevent="updateProfile">
      <!-- Email (readonly) -->
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" :value="account.email" class="form-control" readonly>
      </div>

      <!-- Tên -->
      <div class="mb-3">
        <label class="form-label">Tên</label>
        <input type="text" v-model="form.name" class="form-control" required>
      </div>

      <!-- Số điện thoại -->
      <div class="mb-3">
        <label class="form-label">Số điện thoại</label>
        <input type="text" v-model="form.phone" class="form-control">
      </div>

      <!-- Địa chỉ -->
      <div class="mb-3">
        <label class="form-label">Địa chỉ</label>
        <input type="text" v-model="form.address" class="form-control">
      </div>

      <!-- Giới tính -->
      <div class="mb-3">
        <label class="form-label">Giới tính</label>
        <select v-model="form.gender" class="form-select">
          <option value="">Chọn giới tính</option>
          <option value="male">Nam</option>
          <option value="female">Nữ</option>
          <option value="other">Khác</option>
        </select>
      </div>

      <!-- Ngày sinh -->
      <div class="mb-3">
        <label class="form-label">Ngày sinh</label>
        <input type="date" v-model="form.birth" class="form-control">
      </div>

      <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
    </form>
    <span v-if="account && !account.is_active" class="text-danger">Tài khoản đang chờ xóa</span>
    <div class="mt-4">
  <button 
    v-if="account && account.is_active"
    class="btn btn-danger"
    @click="deactivateAccount"
  >
    Hủy tài khoản
  </button>

  <button
    v-else
    class="btn btn-success"
    @click="reactivateAccount"
  >
    Khôi phục tài khoản
  </button>
</div>

    <div v-if="success" class="alert alert-success mt-3">{{ success }}</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import axios from "axios"
// import router from "@/router" // nếu dùng vue-router để redirect sau khi hủy

const account = ref(null)
const loading = ref(false)
const error = ref(null)
const success = ref(null)

const form = ref({
  name: "",
  phone: "",
  address: "",
  gender: "",
  birth: ""
})

const fetchProfile = async () => {
  loading.value = true
  error.value = null
  try {
    const res = await axios.get("/api/user/profi")
    account.value = res.data.account
    
    form.value.name = account.value.name || ""
    form.value.phone = account.value.phone || ""
    form.value.address = account.value.address || ""
    form.value.gender = account.value.gender || ""
    form.value.birth = account.value.birth || ""
  } catch (err) {
    error.value = err.response?.data?.message || err.message
  } finally {
    loading.value = false
  }
}

const updateProfile = async () => {
  try {
    const payload = {
      name: form.value.name,
      phone: form.value.phone,
      address: form.value.address,
      gender: form.value.gender,
      birth: form.value.birth
    }
    await axios.put(`/api/user/profi/${account.value.id}`, payload)
    success.value = "Cập nhật thông tin thành công!"
    fetchProfile()
  } catch (err) {
    error.value = err.response?.data?.message || err.message
  }
}

// Nút hủy tài khoản
const deactivateAccount = async () => {
  if (!confirm("Bạn có chắc muốn hủy tài khoản?")) return
  try {
    await axios.put("/api/user/deactivate")
    success.value = "Tài khoản đã yêu cầu hủy thành công"
    // Tùy chọn: logout và redirect về login
    fetchProfile()
  } catch (err) {
    error.value = err.response?.data?.message || err.message
  }
}
const reactivateAccount = async () => {
  if (!confirm("Bạn có chắc muốn khôi phục tài khoản?")) return
  try {
    await axios.put("/api/user/deactivate")
    success.value = "Tài khoản đã được khôi phục"
    fetchProfile() // load lại dữ liệu
  } catch (err) {
    error.value = err.response?.data?.message || err.message
  }
}

onMounted(() => {
  fetchProfile()
})
</script>

<style scoped>
.profile-container {
  max-width: 600px;
  margin: auto;
}
</style>
