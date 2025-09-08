import './bootstrap';
import { createApp } from 'vue';

// Import component
import SuggestProducts from './components/suggestProducts.vue';
import Message from './components/message.vue';
import FilterField from './components/FilterField.vue';
import AllProducts from './components/AllProducts.vue';
import CategoriesList from './components/CategoriesList.vue';
import WishlishProduct from './components/WishlishProduct.vue';
import Alert from './components/Alert.vue';

const app = createApp({});
// const pinia = createPinia()

// Đăng ký component toàn cục
app.component('Message', Message);
app.component('FilterField', FilterField);
app.component('SuggestProducts', SuggestProducts);
app.component('AllProducts', AllProducts);
app.component('CategoriesList',CategoriesList);
app.component('WishlistProduct',WishlishProduct);
app.component('Alert',Alert);


// Mount Vue vào #app (trong Blade)
// app.use(pinia);
app.mount('#app');