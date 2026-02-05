import './bootstrap';
import { createApp } from 'vue';
import HomePage from './pages/HomePage.vue';
import NotificationCenter from './components/NotificationCenter.vue';

const app = createApp(HomePage);
app.component('NotificationCenter', NotificationCenter);
app.mount('#app');
