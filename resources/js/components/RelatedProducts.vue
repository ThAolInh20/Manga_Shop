<template>
  <div class="related-products card mt-4">
    <h4 class="mb-3">📚 Sản phẩm liên quan</h4>

    <!-- Loading -->
    <div v-if="loading" class="text-muted">Đang tải...</div>

    <!-- Không có dữ liệu -->
    <div v-else-if="products.length === 0" class="text-danger">
      Không tìm thấy sản phẩm liên quan.
    </div>

    <!-- Danh sách -->
    <div v-else class="row g-2">
  <div v-for="p in products" :key="p.id" class="col-6 col-md-2">
    <div class="card h-100 shadow-sm text-center">
      <img
        v-if="p.images"
        :src="'/storage/' + p.images"
        class="card-img-top img-fluid"
        alt="Ảnh sản phẩm"
      />
      <div class="card-body p-2">
        <h6 class="card-title small mb-1">{{ p.name }}</h6>
        <!-- <p class="card-text text-muted mb-2 small">
          {{ formatPrice(p.price) }} đ
        </p> -->
        <a
          :href="`/products/${p.id}`"
          class="btn btn-sm btn-outline-primary"
        >
          Xem
        </a>
      </div>
    </div>
  </div>
</div>

  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "RelatedProducts",
  props: {
    productId: {
      type: Number,
      required: true,
    },
    column: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      loading: true,
      products: [],
    };
  },
  methods: {
    async fetchRelated() {
      this.loading = true;
      try {
        const res = await axios.get(`/api/products/${this.productId}/related`, {
          params: { column: this.column },
        });
         console.log("API response:", res.data);
        if (res.data.status === "success") {
          this.products = res.data.related;
        } else {
          this.products = [];
        }
      } catch (err) {
        console.error(err);
        this.products = [];
      } finally {
        this.loading = false;
      }
    },
    formatPrice(price) {
      return new Intl.NumberFormat("vi-VN").format(price);
    },
  },
  mounted() {
    this.fetchRelated();
  },
};
</script>

<style scoped>
.related-products .card {
  font-size: 0.85rem;
  max-width: 160px;
  margin: auto;
}
.related-products img {
  max-height: 120px;
  object-fit: cover;
}
.related-products .btn {
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
}
.related-products {
  margin-top: 20px; /* hoặc 2rem cho thoáng */
}

</style>
