<template>
    <div class="login-card">
        <button class="close-btn" @click="$emit('close')">×</button>
        <h2>Вход</h2>
        <form @submit.prevent="login">
            <input v-model="email" type="email" placeholder="Email" required />
            <input v-model="password" type="password" placeholder="Пароль" required />
            <button class="login" type="submit">Войти</button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            email: "",
            password: "",
        };
    },
    methods: {
        login() {
            alert(`Добро пожаловать, ${this.email}!`);
            this.$emit("close");
            this.email = "";
            this.password = "";
        },
    },
};
</script>

<style scoped>
/* Центрирование модального окна */
.login-card {
    position: relative;
    padding: 40px;
    padding-top: 20px;
    border-radius: 12px;
    text-align: center;
    color: #b8860b;
    font-family: 'Playfair Display', serif;
    background: rgba(25, 25, 25, 0.98); /* Чуть светлее, чтобы выделить форму */
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5); /* Эффект объёма */
}

/* Заголовок */
h2 {
    margin-bottom: 20px;
    font-size: 28px;
    font-weight: bold;
    color: #f1c40f; /* Золотистый */
}

/* Стили для полей ввода */
input {
    width: 80%;
    padding: 14px;
    margin: 10px 0;
    border: 2px solid #b8860b;
    background: transparent;
    color: white;
    font-size: 16px;
    border-radius: 6px;
    outline: none;
    transition: 0.3s;
}

input::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

/* Эффект фокуса */
input:focus {
    border-color: #f1c40f;
    background: rgba(255, 255, 255, 0.05);
}

/* Кнопка */
.login {
    width: 60%;
    padding: 14px;
    margin-top: 15px;
    background-color: #b8860b;
    color: black;
    border: none;
    cursor: pointer;
    font-weight: bold;
    border-radius: 6px;
    transition: 0.3s;
}

button:hover {
    background-color: #9e760a;
}

.close-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 24px;
    background: none;
    border: none;
    color: #b8860b;
    cursor: pointer;
    transition: 0.3s;
    border-radius: 5px;
}

.close-btn:hover {
    color: #f1c40f;
    transform: scale(1.2);
}
</style>
