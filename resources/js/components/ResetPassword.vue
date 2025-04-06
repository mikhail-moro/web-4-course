<template>
    <div class="reset-container">
        <h2>Сброс пароля</h2>
        <form @submit.prevent="submit">
            <input v-model="email" type="email" placeholder="Email" required />
            <input v-model="password" type="password" placeholder="Новый пароль" @input="validatePassword" required />
            <input v-model="password_confirmation" type="password" placeholder="Подтвердите пароль" required />

            <p v-if="passwordError" class="error">{{ passwordError }}</p>

            <button type="submit">Сбросить пароль</button>
        </form>

        <p v-if="message" class="success">{{ message }}</p>
        <p v-if="error" class="error">{{ error }}</p>
    </div>
</template>

<script>
export default {
    data() {
        return {
            email: '',
            password: '',
            password_confirmation: '',
            message: '',
            error: '',
            passwordError: ''
        };
    },
    methods: {
        validatePassword() {
            const specialChars = /[!@#$%^&*]/;
            const uppercaseLetters = /[A-Z]/g;

            if (this.password.length < 6) {
                this.passwordError = 'Пароль должен содержать минимум 6 символов';
            } else if ((this.password.match(uppercaseLetters) || []).length < 2) {
                this.passwordError = 'Пароль должен содержать минимум 2 заглавные буквы';
            } else if (!specialChars.test(this.password)) {
                this.passwordError = 'Пароль должен содержать хотя бы 1 специальный символ (!@#$%^&*)';
            } else {
                this.passwordError = '';
            }
        },

        async submit() {
            const token = this.$route.query.token;

            if (!token) {
                this.error = 'Отсутствует токен сброса пароля.';
                return;
            }

            this.validatePassword();
            if (this.passwordError) return;

            try {
                const res = await fetch('/api/reset-password', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        token,
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.password_confirmation
                    })
                });

                const data = await res.json();

                if (!res.ok) {
                    this.error = data.message || 'Ошибка при сбросе пароля';
                    return;
                }

                this.message = 'Пароль успешно сброшен!';
                this.error = '';
            } catch (e) {
                this.error = 'Сервер не отвечает';
                console.error(e);
            }
        }
    }
};
</script>

<style scoped>
.reset-container {
    margin-top: 100px;
    margin-bottom: 20px;
    max-width: 400px;
    margin-inline: auto;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    color: black;
}

input {
    width: 90%;
    margin: 10px 0;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ccc;
    font-size: 16px;
}

button {
    width: 70%;
    padding: 12px;
    background-color: #d4af37;
    border: none;
    color: white;
    border-radius: 10px;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
    margin-top: 10px;
}

button[disabled] {
    background-color: #ccc;
    cursor: not-allowed;
}

.success {
    margin-top: 15px;
    color: green;
    font-weight: bold;
}

.error {
    margin-top: 10px;
    color: red;
    font-weight: bold;
}
</style>