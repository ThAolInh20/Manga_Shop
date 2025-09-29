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
<div 
  v-else 
  class="d-flex overflow-auto gap-2 pb-2 related-scroll"
  ref="scrollContainer"
>
  <div v-for="p in products" :key="p.id" class="flex-shrink-0" style="width: 160px;">
    <div class="card h-100 shadow-sm text-center">
      <img
        v-if="p.images"
        :src="'/storage/' + p.images"
        class="card-img-top img-fluid"
        alt="Ảnh sản phẩm"
      />
      <div class="card-body p-2">
        <h6 class="card-title small mb-1">{{ p.name }}</h6>
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
     // chuyển wheel dọc thành cuộn ngang
    const el = this.$refs.scrollContainer;
    if (el) {
      el.addEventListener("wheel", (e) => {
        if (e.deltaY !== 0) {
          e.preventDefault();
          el.scrollLeft += e.deltaY;
        }
      });
    }
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
.related-scroll {
  scroll-behavior: smooth;
  scrollbar-width: thin;
}
.related-scroll::-webkit-scrollbar {
  height: 6px;
}
.related-scroll::-webkit-scrollbar-thumb {
  background: #bbb;
  border-radius: 4px;
}


</style>
