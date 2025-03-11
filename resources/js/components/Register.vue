<template>
    <div class="register-card">
        <button class="close-btn" @click="$emit('close')">×</button>
        <h2>Регистрация</h2>
        <form @submit.prevent="register">
            <input v-model="name" type="text" placeholder="Ваше Имя" required />
            <input v-model="email" type="email" placeholder="Email" required />
            <input v-model="password" type="password" placeholder="Пароль" required />
            <input v-model="confirmPassword" type="password" placeholder="Подтвердите пароль" required />
            <p v-if="passwordMismatch" class="error">Пароли не совпадают!</p>
            <button class="register" type="submit" :disabled="passwordMismatch">Зарегистрироваться</button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: "",
            email: "",
            password: "",
            confirmPassword: "",
        };
    },
    computed: {
        passwordMismatch() {
            return this.password && this.confirmPassword && this.password !== this.confirmPassword;
        },
    },
    methods: {
        register() {
            if (this.passwordMismatch) {
                alert("Пароли не совпадают!");
                return;
            }

            // Отправка данных на сервер
            alert(`Пользователь ${this.name} зарегистрирован!`);
            this.$emit("close");

            // Очищаем форму после успешной регистрации
            this.name = "";
            this.email = "";
            this.password = "";
            this.confirmPassword = "";
        },
    },
};
</script>

<style scoped>
/* Центрирование модального окна */
.register-card {
    position: relative;
    padding: 40px;
    padding-top: 20px;
    border-radius: 12px;
    text-align: center;
    color: #d4af37;
    font-family: 'Playfair Display', serif;
    background: rgba(25, 25, 25, 0.98);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
}

/* Заголовок */
h2 {
    margin-bottom: 20px;
    font-size: 28px;
    font-weight: bold;
    color: #f1c40f;
}

/* Стили для полей ввода */
input {
    width: 80%;
    padding: 12px;
    margin: 8px 0;
    border: 2px solid #d4af37;
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

/* Ошибка паролей */
.error {
    color: red;
    font-size: 14px;
    margin: 5px 0;
}

/* Кнопка */
.register {
    width: 60%;
    padding: 14px;
    margin-top: 15px;
    background-color: #d4af37;
    color: black;
    border: none;
    cursor: pointer;
    font-weight: bold;
    border-radius: 6px;
    transition: 0.3s;
}

button:hover {
    background-color: #b68f2d;
}

button:disabled {
    background-color: gray;
    cursor: not-allowed;
}

/* Кнопка закрытия */
.close-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 24px;
    background: none;
    border: none;
    color: #d4af37;
    cursor: pointer;
    transition: 0.3s;
    border-radius: 5px;
}

.close-btn:hover {
    color: #f1c40f;
    transform: scale(1.2);
}
</style>
