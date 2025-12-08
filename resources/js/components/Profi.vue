<template>
  <div class="profile-container container">
    <h3 class="mb-4">Hồ sơ cá nhân</h3>

    <!-- Thông báo -->
    <div v-if="loading" class="alert alert-info">Đang tải...</div>
    <div v-else-if="error" class="alert alert-danger">{{ error }}</div>
    <div v-else-if="success" class="alert alert-success">{{ success }}</div>

    <div class="row">
      <!-- Cột trái: Form -->
      <div class="col-md-6">
        <form v-if="account" @submit.prevent="updateProfile">
          <!-- Email -->
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" :value="account.email" class="form-control" readonly>
          </div>

          <!-- Tên -->
          <div class="mb-3">
            <label class="form-label">Tên</label>
            <input type="text" v-model="form.name" class="form-control" required>
          </div>

          <!-- SĐT -->
          <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input type="text" v-model="form.phone" class="form-control">
          </div>

          <!-- Địa chỉ -->
          <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="address" v-model="form.address" class="form-control">
          </div>

          <!-- Giới tính -->
          <div class="mb-3">
            <label class="form-label">Giới tính</label>
            <select name="gender" v-model="form.gender" class="form-select">
              <option value="">Chọn giới tính</option>
              <option value="male">Nam</option>
              <option value="female">Nữ</option>
              <option value="other">Khác</option>
            </select>
          </div>

          <!-- Ngày sinh -->
          <div class="mb-3">
            <label class="form-label">Ngày sinh</label>
            <input type="date" name="date" v-model="form.birth" class="form-control">
          </div>

          <button type="submit" class="btn btn-primary">💾 Lưu thay đổi</button>
        </form>

        <!-- Trạng thái tài khoản -->
        <div v-if="account" class="mt-4">
          <span v-if="!account.is_active" class="text-danger d-block mb-2">
            ⚠️ Tài khoản đang chờ xóa
          </span>

          <!-- Hai nút riêng biệt -->
          <div class="d-flex gap-2">
            <button
              v-if="account.is_active"
              class="btn btn-danger"
              @click="showDeactivateModal = true"
            >
              🧨 Yêu cầu hủy tài khoản
            </button>

            <button
              v-else
              class="btn btn-success"
              @click="showReactivateModal = true"
            >
              🔄 Khôi phục tài khoản
            </button>
          </div>
        </div>
      </div>

      <!-- Cột phải: Địa chỉ giao hàng -->
      <div class="col-md-6">
        <div class="card shadow-sm p-3">
          <h5 class="mb-3">🏠 Địa chỉ giao hàng</h5>
          <shipping-address
            v-if="account"
            :account_id="account.id"
            @address-selected="handleSelectedAddress"
          />
        </div>
      </div>
    </div>

    <!-- 🧩 Modal xác nhận HỦY tài khoản -->
    <div
      v-if="showDeactivateModal"
      class="modal fade show d-block"
      tabindex="-1"
      style="background-color: rgba(0,0,0,0.5);"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">⚠️ Xác nhận hủy tài khoản</h5>
            <button type="button" class="btn-close" @click="showDeactivateModal = false"></button>
          </div>
          <div class="modal-body">
            <p>🕒 Sau khi gửi yêu cầu, bạn sẽ được <strong>gọi xác nhận trong vòng 7 ngày</strong>.</p>
            <p>❌ Khi tài khoản bị xóa:</p>
            <ul>
              <li>Tất cả đơn hàng của bạn sẽ bị xóa.</li>
              <li>Mọi thông tin cá nhân sẽ bị xóa <strong>vĩnh viễn và không thể khôi phục</strong>.</li>
            </ul>
            <p class="text-secondary fst-italic">
              💡 Bạn có thể <strong>hủy yêu cầu xóa tài khoản</strong> bất cứ khi nào bạn vẫn còn đăng nhập được vào trang web.
            </p>
            <p class="fw-bold text-danger mt-3">Bạn có chắc chắn muốn tiếp tục?</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="showDeactivateModal = false">Hủy</button>
            <button class="btn btn-danger" @click="confirmDeactivate">Xác nhận hủy tài khoản</button>
          </div>
        </div>
      </div>
    </div>

    <!-- 🧩 Modal xác nhận KHÔI PHỤC tài khoản -->
    <div
      v-if="showReactivateModal"
      class="modal fade show d-block"
      tabindex="-1"
      style="background-color: rgba(0,0,0,0.5);"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-success">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title">🔄 Khôi phục tài khoản</h5>
            <button type="button" class="btn-close" @click="showReactivateModal = false"></button>
          </div>
          <div class="modal-body">
            <p>Bạn có muốn <strong>hủy yêu cầu xóa</strong> và khôi phục lại tài khoản của mình không?</p>
            <p class="text-secondary fst-italic">Sau khi khôi phục, bạn có thể sử dụng lại tài khoản như bình thường.</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="showReactivateModal = false">Đóng</button>
            <button class="btn btn-success" @click="confirmReactivate">Khôi phục</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import axios from "axios"

const account = ref(null)
const loading = ref(false)
const error = ref(null)
const success = ref(null)

const showDeactivateModal = ref(false)
const showReactivateModal = ref(false)

const form = ref({
  name: "",
  phone: "",
  address: "",
  gender: "",
  birth: ""
})

const fetchProfile = async () => {
  loading.value = true
  try {
    const res = await axios.get("/api/user/profi")
    account.value = res.data.account
    form.value = {
      name: account.value.name || "",
      phone: account.value.phone || "",
      address: account.value.address || "",
      gender: account.value.gender || "",
      birth: account.value.birth || ""
    }
  } catch (err) {
    error.value = err.response?.data?.message || err.message
  } finally {
    loading.value = false
  }
}

const updateProfile = async () => {
  try {
    await axios.put(`/api/user/profi/${account.value.id}`, form.value)
    success.value = "Cập nhật thông tin thành công!"
    fetchProfile()
  } catch (err) {
    error.value = err.response?.data?.message || err.message
  }
}

const deactivateAccount = async () => {
  try {
    await axios.put("/api/user/deactivate")
    success.value = "Tài khoản đã yêu cầu hủy thành công"
    showDeactivateModal.value = false
    fetchProfile()
  } catch (err) {
    error.value = err.response?.data?.message || err.message
  }
}

const reactivateAccount = async () => {
  try {
    await axios.put("/api/user/deactivate")
    success.value = "Tài khoản đã được khôi phục"
    showReactivateModal.value = false
    fetchProfile()
  } catch (err) {
    error.value = err.response?.data?.message || err.message
  }
}

const confirmDeactivate = () => deactivateAccount()
const confirmReactivate = () => reactivateAccount()

onMounted(() => {
  fetchProfile()
})
</script>

<style scoped>
.profile-container {
  max-width: 1200px;
  margin: auto;
}

.modal-content {
  border-radius: 1rem;
}
</style>
