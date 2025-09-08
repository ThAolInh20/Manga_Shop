<template>
  <div class="mb-3">
    <label class="form-label">{{ label }}</label>

    <ul class="list-group">
        <li class="list-group-item list-group-item-action" :class="{ active: selected === 0 }">
        <a href="#" @click.prevent="selectCategory(0)">
          Tất cả
        </a>
      </li>
      <li 
        v-for="cat in categories" 
        :key="cat.id" 
        class="list-group-item list-group-item-action"
        :class="{ active: selected === cat.id }"
      >
        <a href="#" @click.prevent="selectCategory(cat.id)">
          {{ cat.name }}
        </a>
      </li>
      
    </ul>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'CategoriesList',
  props: {
    label: { type: String, default: 'Danh mục' },
    modelValue: { type: Number, default: 0 } // id category
  },
  emits: ['update:modelValue', 'change'],
  data() {
    return {
      categories: [],
      selected: this.modelValue
    }
  },
  methods: {
    async fetchCategories() {
      try {
        const res = await axios.get('/api/categories')
        this.categories = res.data
      } catch (err) {
        console.error('Error fetching categories:', err)
      }
    },
    selectCategory(id) {
      this.selected = id
      this.$emit('update:modelValue', id)
      this.$emit('change', id)
    }
  },
  watch: {
    modelValue(val) {
      this.selected = val
    }
  },
  mounted() {
    this.fetchCategories()
  }
}
</script>

<style scoped>
.list-group-item a {
  text-decoration: none;
  color: #495057; /* màu chữ tối nhẹ, dễ nhìn */
  cursor: pointer;
  transition: color 0.2s;
}

.list-group-item a:hover {
  color: #0d6efd; /* xanh dương tươi, khi hover nổi bật */
  text-decoration: underline;
}

.list-group-item.active a {
  font-weight: 600;
  color: #fff; /* chữ trắng nổi bật trên nền xanh */
 
  border-radius: 0.25rem;
  padding: 0.375rem 0.75rem; /* thêm padding để đẹp hơn */
}
</style>
