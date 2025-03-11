import { createApp } from 'vue';
import App from './components/App.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';

const app = createApp(App);

app.component("register-component", Register);
app.component("login-component", Login);

app.mount("#app");
