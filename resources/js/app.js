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
import Cart from './components/Cart.vue';
import UserOrder from './components/UserOrder.vue';
import OrderDetail from './components/OrderDetail.vue';
import ShippingAddress from './components/ShippingAddress.vue';
import OrderCheckout from './components/OrderCheckout.vue';
import MomoPayment from './components/MomoPayment.vue';
import PayOSPayment from './components/PayOSPayment.vue';
import ListVoucher from './components/ListVoucher.vue';

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
app.component('Cart',Cart);
app.component('UserOrder',UserOrder);
app.component('OrderDetail',OrderDetail);
app.component('ShippingAddress',ShippingAddress);
app.component('OrderCheckout',OrderCheckout);
app.component('MomoPayment',MomoPayment);
app.component('PayOSPayment',PayOSPayment);
app.component('ListVoucher',ListVoucher)


// Mount Vue vào #app (trong Blade)
// app.use(pinia);
app.mount('#app');