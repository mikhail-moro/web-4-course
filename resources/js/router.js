import { createRouter, createWebHistory } from 'vue-router';
import LandingPage from './components/LandingPage.vue';
import BookingPage from './components/BookingPage.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';
import ProfilePage from './components/ProfilePage.vue';
import AdminPanel from './components/AdminPanel.vue';
import AdminTables from './components/AdminTables.vue';
import AdminUsers from './components/AdminUsers.vue';
import AdminReservations from './components/AdminReservations.vue';

const routes = [
    { path: '/', component: LandingPage },
    { path: '/register', component: Register },
    { path: '/login', component: Login },
    { path: '/booking', component: BookingPage },
    { path: '/profile', component: ProfilePage },
    { path: '/admin', component: AdminPanel },
    { path: '/admin/tables', component: AdminTables },
    { path: '/admin/users', component: AdminUsers },
    { path: '/admin/reservations', component: AdminReservations },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
