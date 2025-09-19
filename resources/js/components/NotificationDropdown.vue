<template>
  <div class="nav-item dropdown" ref="dropdown" >
    <a class="nav-link dropdown-toggle hide-arrow p-0" href="#" @click.prevent="toggleDropdown">
      <i class="bx bx-bell fs-4"></i>
      <span v-if="unreadCount > 0"
            class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
        {{ unreadCount }}
      </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end"
    v-show="show"
    @scroll.passive="onScroll"
    style="max-height: 300px; overflow-y: auto; overflow-x: hidden; white-space: normal; left: -50px;">
  <li v-for="notif in notifications" :key="notif.id"
    class="dropdown-item"
    :class="notif.is_important ? 'fw-bold text-danger' : ''">
  <strong>{{ notif.title }}</strong><br>
  <span class="text-muted">{{ notif.content }}</span>
</li>
  <li v-if="notifications.length === 0" class="dropdown-item text-muted">Không có thông báo nào</li>
  <li v-if="loading" class="dropdown-item text-center">Đang tải...</li>
</ul>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      notifications: [],
      page: 1,
      perPage: 10,
      total: 0,
      show: false,
      loading: false,
    }
  },
  computed: {
    unreadCount() {
      return this.notifications.filter(n => !n.is_read).length;
    }
  },
  methods: {
    toggleDropdown() {
      this.show = !this.show;
      if (this.show && this.notifications.length === 0) {
        this.fetchNotifications();
      }
    },
    fetchNotifications() {
      if (this.loading) return;
      this.loading = true;

      axios.get('/api/notifications', { params: { page: this.page, per_page: this.perPage } })
        .then(res => {
          this.notifications.push(...res.data.data);
          this.total = res.data.total;
          this.page++;
        })
        .finally(() => this.loading = false);
    },
    onScroll(event) {
      const el = event.target;
      if (el.scrollTop + el.clientHeight >= el.scrollHeight - 5) {
        // Nếu chưa load hết
        if (this.notifications.length < this.total) {
          this.fetchNotifications();
        }
      }
    }
  }
}
</script>

<style scoped>
.dropdown-menu {
  max-height: 300px;
  overflow-y: auto;
  display: block;
  position: absolute;
  width: 300px;
  z-index: 1000;
}
</style>
