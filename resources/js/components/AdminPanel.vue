<template>
    <div class="admin-panel">
        <h1>Панель администратора</h1>
        <div class="card-grid">
            <router-link class="card" to="/admin/users">Пользователи</router-link>
            <router-link class="card" to="/admin/tables">Столики</router-link>
            <router-link class="card" to="/admin/reservations">Бронирования</router-link>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AdminPanel',
};
</script>

<style scoped>
.admin-panel {
    max-width: 960px;
    margin: 60px auto;
    padding: 20px;
    text-align: center;
    font-family: 'Segoe UI', sans-serif;
}

h1 {
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 30px;
    color: #d4af37;;
}

.card-grid {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.card {
    background-color: #f9f9f9;
    padding: 20px 30px;
    border-radius: 12px;
    text-decoration: none;
    color: #2c3e50;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    transition: all 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    background-color: #eef2f7;
}
</style>