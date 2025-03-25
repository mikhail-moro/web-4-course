<template>
    <header class="header">
        <div class="logo">ÉLITE GASTRO</div>
        <nav>
            <button class="profile-btn" @click="$router.push('/profile')">Профиль</button>
            <button class="auth-btn" @click="$emit('logout')">Выйти</button>
        </nav>
    </header>
</template>

<style scoped>
.header {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 50px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    font-family: 'Playfair Display', serif;
}

.logo {
    font-size: 26px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 2px;
}

nav {
    display: flex;
    gap: 15px;
    margin-right: 100px;
}

/* Кнопка профиля */
.profile-btn {
    background: transparent;
    color: #d4af37;
    padding: 10px 20px;
    border: 2px solid #d4af37;
    border-radius: 30px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    font-family: 'Playfair Display', serif;
    transition: 0.3s;
}

.profile-btn:hover {
    background: #d4af37;
    color: black;
}

/* Кнопка выхода */
.auth-btn {
    background: transparent;
    color: #d4af37;
    padding: 10px 20px;
    border: 2px solid #d4af37;
    border-radius: 30px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    font-family: 'Playfair Display', serif;
    transition: 0.3s;
}

.auth-btn:hover {
    background: #d4af37;
    color: black;
}
</style>
