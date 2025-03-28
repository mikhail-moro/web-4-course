import { createApp } from 'vue';
import App from './components/App.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';
import router from './router'; // Подключаем маршрутизатор
import Cookies from 'js-cookie';

const app = createApp(App);

app.component("register-component", Register);
app.component("login-component", Login);

app.use(router);
app.mount('#app');
