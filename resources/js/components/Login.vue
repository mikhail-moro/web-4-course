<template>
    <div class="login-card">
        <button class="close-btn" @click="$emit('close')">×</button>
        <h2>Вход</h2>

        <form @submit.prevent="login">
            <input v-model="email" type="email" placeholder="Email" required />
            <input v-model="password" type="password" placeholder="Пароль" required />
            <button class="login" type="submit">Войти</button>
        </form>

        <p v-if="loginError" class="error">{{ loginError }}</p>

        <button class="forgot-btn" @click="forgotPassword">Забыли пароль?</button>

        <p v-if="resetMessage" class="success">{{ resetMessage }}</p>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            email: '',
            password: '',
            loginError: '',
            resetMessage: ''
        };
    },
    methods: {
        async login() {
            try {
                const response = await axios.post('/api/login', {
                    email: this.email,
                    password: this.password,
                });

                const token = response.data.token;
                const user = response.data.user;

                // Сохраняем данные в localStorage
                localStorage.setItem('auth_token', token);
                localStorage.setItem('user_id', user.id);
                localStorage.setItem('user_name', user.name);
                localStorage.setItem('user_email', user.email);
                localStorage.setItem('user_role', user.role_id);

                this.$emit('auth-changed', true);
                this.$emit('close');

                if (user.role_id === 1) {
                    this.$router.push('/admin');
                } else {
                    this.$router.push('/booking');
                }

            } catch (error) {
                this.loginError = 'Неверный email или пароль';
                console.error('Ошибка входа:', error);
            }
        },

        async forgotPassword() {
            if (!this.email) {
                this.loginError = 'Введите email для сброса пароля.';
                return;
            }

            try {
                await axios.post('/api/forgot-password', {
                    email: this.email
                });

                this.resetMessage = 'Ссылка для сброса отправлена на почту.';
                this.loginError = '';

                // Можно сразу открыть форму сброса в новой вкладке (если frontend SPA)
                // Или оставить это на переход по ссылке из письма
                // this.$router.push('/reset-password');

            } catch (error) {
                this.loginError = 'Ошибка при отправке письма. Проверьте email.';
                console.error('Ошибка сброса пароля:', error);
            }
        }
    }
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
    background: rgba(25, 25, 25, 0.98);
    /* Чуть светлее, чтобы выделить форму */
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
    /* Эффект объёма */
}

/* Заголовок */
h2 {
    margin-bottom: 20px;
    font-size: 28px;
    font-weight: bold;
    color: #f1c40f;
    /* Золотистый */
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

button.forgot-btn {
    margin-top: 15px;
    background: transparent;
    border: none;
    color: #f1c40f;
    cursor: pointer;
    text-decoration: underline;
}

</style>
