import './bootstrap';
import { createApp } from 'vue';

// Import component
import SuggestProducts from './components/suggestProducts.vue';
import Message from './components/message.vue';
import FilterField from './components/FilterField.vue';

const app = createApp({});

// Đăng ký component toàn cục
app.component('Message', Message);
app.component('FilterField', FilterField);
app.component('SuggestProducts', SuggestProducts);

// Mount Vue vào #app (trong Blade)
app.mount('#app');