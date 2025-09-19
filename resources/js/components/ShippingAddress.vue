<template>
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
      <span>Địa chỉ giao hàng</span>
      <button class="btn btn-sm btn-primary" @click="openAddModal">+ Thêm địa chỉ</button>
    </div>
    <div class="card-body">
      <!-- Nếu đã có địa chỉ -->
      <div v-if="addresses.length > 0" class="mb-3">

        <label v-if="this.orderId" class="form-label">Chọn địa chỉ có sẵn</label>
        <label v-else class="form-label">Địa chỉ của bạn</label>


        <div v-for="addr in addresses" :key="addr.id" class="form-check d-flex justify-content-between align-items-center">
          <div>
            <input v-if="this.orderId"
              class="form-check-input me-2" 
              type="radio" 
              name="selectedAddress" 
              :id="'addr-' + addr.id" 
              :value="addr.id" 
              v-model="selectedAddressId"
              @change="emitSelectedAddress"
            >
            <label class="form-check-label" :for="'addr-' + addr.id">
              {{ addr.name_recipient }} - {{ addr.phone_recipient }} - Địa chỉ: {{ addr.shipping_address }}
            </label>
          </div>
         <div class="d-flex gap-2">
          <button type="button" class="btn btn-sm btn-warning" @click="openEditModal(addr)">Sửa</button>
          <button type="button" class="btn btn-sm btn-danger" @click="deleteAddress(addr.id)">Xóa</button>
        </div>
          
        </div>
      </div>
      <div v-else class="text-muted">Chưa có địa chỉ nào. Hãy thêm địa chỉ mới.</div>
    </div>

    <!-- Modal thêm/sửa địa chỉ -->
    <div class="modal fade" id="addressModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ isEdit ? "Sửa địa chỉ" : "Thêm địa chỉ" }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Tên người nhận</label>
              <input type="text" v-model="form.name_recipient" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Số điện thoại</label>
              <input type="text" v-model="form.phone_recipient" class="form-control">
            </div>

            <!-- Tỉnh / Thành phố -->
            <div class="mb-3">
              <label class="form-label">Tỉnh / Thành phố</label>
              <select v-model="selected.province" class="form-select" @change="loadDistricts">
                <option value="">-- Chọn tỉnh --</option>
                <option v-for="p in provinces" :key="p.ProvinceID" :value="p">{{ p.ProvinceName }}</option>
              </select>
            </div>

            <!-- Quận / Huyện -->
            <div class="mb-3">
              <label class="form-label">Quận / Huyện</label>
              <select v-model="selected.district" class="form-select" @change="loadWards" :disabled="!districts.length">
                <option value="">-- Chọn huyện --</option>
                <option v-for="d in districts" :key="d.DistrictID" :value="d">{{ d.DistrictName }}</option>
              </select>
            </div>

            <!-- Xã / Phường -->
            <div class="mb-3">
              <label class="form-label">Xã / Phường</label>
              <select v-model="selected.ward" class="form-select" @change="updateAddress" :disabled="!wards.length">
                <option value="">-- Chọn xã --</option>
                <option v-for="w in wards" :key="w.WardCode" :value="w">{{ w.WardName }}</option>
              </select>
            </div>

            <!-- Số nhà -->
            <div class="mb-3">
              <label class="form-label">Số nhà, đường</label>
              <input type="text" v-model="street" class="form-control" @input="updateAddress" placeholder="Ví dụ: 123 Lê Lợi">
            </div>

            <!-- Hiển thị địa chỉ đầy đủ -->
            <div class="alert alert-info">
              <strong>Địa chỉ đầy đủ:</strong><br>
              {{ form.shipping_address || "Chưa nhập địa chỉ" }}
            </div>

            <!-- <div class="mb-3">
              <label class="form-label">Phí ship</label>
              <input type="number" v-model="form.shipping_fee" class="form-control" readonly>
            </div> -->
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            <button type="button" class="btn btn-primary" @click="saveAddress">
              {{ isEdit ? "Cập nhật" : "Thêm mới" }}
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from "axios";
import Modal from "bootstrap/js/dist/modal";

export default {
  name: "ShippingAddress",
  props: {
    accountId: { type: Number, required: true },
    orderId: { type: Number, required: false }
  },
  data() {
    return {
      addresses: [],
      selectedAddressId: null,
      provinces: [],
      districts: [],
      wards: [],
      street: "",
      selected: { province: null, district: null, ward: null },
      form: {
        name_recipient: "",
        phone_recipient: "",
        shipping_address: "",
        shipping_fee: 0
      },
      isEdit: false,
      editingId: null,
      modalInstance: null
    };
  },
  methods: {
    async loadProvinces() {
      const res = await axios.get("/api/provinces");
      this.provinces = res.data;
    },
    async loadDistricts() {
      this.districts = [];
      this.wards = [];
      this.selected.district = null;
      this.selected.ward = null;

      if (this.selected.province) {
        const res = await axios.get(`/api/districts/${this.selected.province.ProvinceID}`);
        this.districts = res.data;
      }
      this.updateAddress();
    },
    async loadWards() {
      this.wards = [];
      this.selected.ward = null;

      if (this.selected.district) {
        const res = await axios.get(`/api/wards/${this.selected.district.DistrictID}`);
        this.wards = res.data;
      }
      this.updateAddress();
    },
    updateAddress() {
      let parts = [
        this.street,
        this.selected.ward?.WardName,
        this.selected.district?.DistrictName,
        this.selected.province?.ProvinceName,
      ].filter(Boolean);

      this.form.shipping_address = parts.join(", ");

      // Phí ship tạm
      if (!this.selected.province) {
        this.form.shipping_fee = 100000;
      } else {
        const name = this.selected.province.ProvinceName.toLowerCase();
        this.form.shipping_fee = (name.includes("hà nội") || name.includes("đà nẵng")) ? 50000 : 100000;
      }
    },
     async deleteAddress(id) {
        if (!confirm("Bạn có chắc muốn xóa địa chỉ này?")) return;

        try {
          await axios.delete(`/api/shippings/${id}`);
          this.addresses = this.addresses.filter(a => a.id !== id);

          // Nếu đang chọn address vừa xóa, reset
          if (this.selectedAddressId === id) {
            this.selectedAddressId = this.addresses.length ? this.addresses[0].id : null;
            this.emitSelectedAddress();
          }

          alert("Xóa địa chỉ thành công!");
        } catch (error) {
          console.error("Lỗi khi xóa địa chỉ:", error.response?.data || error.message);
          alert("Không thể xóa địa chỉ, vui lòng thử lại!");
        }
      },
    async fetchAddresses() {
      const res = await axios.get("/api/shippings");
      this.addresses = res.data;
      if (this.addresses.length > 0) {
        this.selectedAddressId = this.addresses[0].id;
        this.emitSelectedAddress();
      }
    },
    openAddModal() {
      this.isEdit = false;
      this.editingId = null;
      this.form = { name_recipient: "", phone_recipient: "", shipping_address: "", shipping_fee: 0 };
      this.street = "";
      this.selected = { province: null, district: null, ward: null };
      this.modalInstance.show();
    },
    openEditModal(addr) {
      this.isEdit = true;
      this.editingId = addr.id;
      this.form = { ...addr };
      const parts = addr.shipping_address.split(",").map(p => p.trim());
      this.street = parts[0] || "";

      // tìm province
      this.selected.province = this.provinces.find(p => parts.includes(p.ProvinceName)) || null;

      if (this.selected.province) {
        axios.post(`/api/districts/${this.selected.province.ProvinceID}`).then(res => {
          this.districts = res.data;
          this.selected.district = this.districts.find(d => parts.includes(d.DistrictName)) || null;

          if (this.selected.district) {
            axios.post(`/api/wards/${this.selected.district.DistrictID}`).then(res2 => {
              this.wards = res2.data;
              this.selected.ward = this.wards.find(w => parts.includes(w.WardName)) || null;
              this.updateAddress();
            });
          } else {
            this.updateAddress();
          }
        });
      } else {
        this.updateAddress();
      }

      this.modalInstance.show();
    },
    async saveAddress() {
      try {
        if (this.isEdit) {
          const res = await axios.put(`/api/shippings/${this.editingId}`, this.form);
          const index = this.addresses.findIndex(a => a.id === this.editingId);
          this.addresses[index] = res.data;
        } else {
          const payload = { ...this.form, account_id: this.accountId };
          const res = await axios.post("/api/shippings", payload);
          this.addresses.push(res.data);
          this.selectedAddressId = res.data.id;
        }
        this.emitSelectedAddress();
        this.modalInstance.hide();
      } catch (error) {
         const message = error.response?.data?.message || 
                    error.response?.data || 
                    error.message || 
                    "Không thể lưu địa chỉ, vui lòng thử lại!";
        console.error("Lỗi khi lưu địa chỉ:", error.response?.data || error.message);
        // alert("Không thể lưu địa chỉ, vui lòng kiểm tra lại!");
        alert(message);
      }
    },
    emitSelectedAddress() {
      const addr = this.addresses.find(a => a.id === this.selectedAddressId);
      this.$emit("address-selected", addr || null);

      if (this.orderId && addr) {
        axios.put(`/api/order/${this.orderId}/update-shipping`, { shipping_id: addr.id })
          .then(res => console.log("Cập nhật shipping_id thành công:", res.data))
          .catch(err => console.error("Lỗi cập nhật shipping_id:", err.response?.data || err.message));
      }
    }
  },
 
  mounted() {
    this.fetchAddresses();
    this.loadProvinces();
    this.modalInstance = new Modal(document.getElementById("addressModal"));
  }
};
</script>
