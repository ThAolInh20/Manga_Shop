import './bootstrap';
import { createApp } from 'vue';

// Import component
import SuggestProducts from './components/suggestProducts.vue';
import Message from './components/message.vue';

const app = createApp({});

// Đăng ký component toàn cục
app.component('Message', Message);
if (document.getElementById('vue-suggest-books')) {
    createApp(SuggestProducts).mount('#vue-suggest-books')
}
// Mount Vue vào #app (trong Blade)
app.mount('#app');