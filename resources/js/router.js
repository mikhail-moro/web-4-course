import { createRouter, createWebHistory } from 'vue-router';
import LandingPage from './components/LandingPage.vue';
import BookingPage from './components/BookingPage.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';

const routes = [
    { path: '/', component: LandingPage },
    { path: '/register', component: Register },
    { path: '/login', component: Login },
    { path: '/booking', component: BookingPage },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
