import './bootstrap';
import { createApp } from 'vue';
import BlogApp from './components/BlogApp.vue';
import NotificationCenter from './components/NotificationCenter.vue';

const app = createApp(BlogApp);
app.component('NotificationCenter', NotificationCenter);
app.mount('#app');
