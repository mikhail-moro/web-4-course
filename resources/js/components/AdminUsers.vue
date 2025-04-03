<template>
    <div class="admin-users">
        <router-link to="/admin" class="btn-back">← Назад к панели администратора</router-link>
        <h2 class="title">Управление пользователями</h2>

        <!-- Форма регистрации -->
        <form @submit.prevent="register" class="form-grid">
            <input v-model="name" type="text" placeholder="Имя" class="input" required />
            <input v-model="email" type="email" placeholder="Email" class="input" required />
            <input v-model="password" type="password" placeholder="Пароль" class="input" required @input="validatePassword" />
            <input v-model="confirmPassword" type="password" placeholder="Подтвердите пароль" class="input" required />

            <p v-if="passwordMismatch" class="error">Пароли не совпадают!</p>
            <p v-if="passwordError" class="error">{{ passwordError }}</p>

            <button type="submit" class="btn full-span"
                :disabled="passwordMismatch || !!passwordError">Зарегистрировать пользователя</button>
        </form>

        <!-- Список пользователей -->
        <div>
            <h3 class="subtitle">Список пользователей</h3>
            <div v-if="users.length > 0" class="table-list">
                <div v-for="user in users" :key="user.id" class="table-card">
                    <div>
                        <p class="font-semibold">{{ user.name }}</p>
                        <p class="text-sm text-gray-500">{{ user.email }} — Роль: {{ user.role_id }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button @click="startEditing(user)" class="btn-secondary">Редактировать</button>
                        <button @click="deleteUser(user.id)" class="btn-danger">Удалить</button>
                    </div>
                </div>
            </div>
            <p v-else class="text-gray-500">Нет пользователей.</p>
        </div>

        <!-- Модальное окно редактирования -->
        <div v-if="isEditing" class="modal-overlay">
            <div class="modal">
                <h3 class="modal-title">Редактировать пользователя</h3>
                <input v-model="editUserData.name" class="input" type="text" placeholder="Имя" />
                <input v-model="editUserData.email" class="input" type="email" placeholder="Email" />
                <select v-model="editUserData.role_id" class="input">
                    <option value="1">Админ</option>
                    <option value="2">Пользователь</option>
                </select>
                <div class="btn-group">
                    <button @click="saveUserEdit" class="btn">Сохранить</button>
                    <button @click="isEditing = false" class="btn-secondary">Отмена</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'AdminUsers',
    data() {
        return {
            users: [],
            name: '',
            email: '',
            password: '',
            confirmPassword: '',
            passwordError: '',
            editUserData: {
                id: null,
                name: '',
                email: '',
                role_id: 2
            },
            isEditing: false
        };
    },
    computed: {
        passwordMismatch() {
            return this.password && this.confirmPassword && this.password !== this.confirmPassword;
        },
    },
    methods: {
        getAuthHeader() {
            const token = localStorage.getItem('auth_token');
            return {
                headers: {
                    Authorization: token,
                    'Content-Type': 'application/json'
                }
            };
        },
        async fetchUsers() {
            try {
                const res = await axios.get('http://127.0.0.1:8000/api/admin/users', this.getAuthHeader());
                this.users = res.data.data;
            } catch (error) {
                console.error('Ошибка загрузки пользователей:', error.response?.data || error.message);
            }
        },
        validatePassword() {
            const specialChars = /[!@#$%^&*]/;
            const uppercaseLetters = /[A-Z]/g;

            if (this.password.length < 6) {
                this.passwordError = "Пароль должен содержать минимум 6 символов";
            } else if ((this.password.match(uppercaseLetters) || []).length < 2) {
                this.passwordError = "Пароль должен содержать минимум 2 заглавные буквы";
            } else if (!specialChars.test(this.password)) {
                this.passwordError = "Пароль должен содержать хотя бы 1 специальный символ (!@#$%^&*)";
            } else {
                this.passwordError = "";
            }
        },
        async register() {
            if (!this.passwordMismatch && !this.passwordError) {
                try {
                    const response = await axios.post('http://127.0.0.1:8000/api/register', {
                        name: this.name,
                        email: this.email,
                        password: this.password
                    });

                    console.log('Успешная регистрация:', response.data.user);
                    this.name = '';
                    this.email = '';
                    this.password = '';
                    this.confirmPassword = '';
                    this.fetchUsers();
                } catch (error) {
                    console.error('Ошибка при регистрации:', error.response?.data || error.message);
                    alert("Ошибка при регистрации. Проверьте данные.");
                }
            }
        },
        async deleteUser(id) {
            try {
                await axios.delete(`http://127.0.0.1:8000/api/admin/users/${id}`, this.getAuthHeader());
                this.fetchUsers();
            } catch (error) {
                console.error('Ошибка удаления пользователя:', error.response?.data || error.message);
            }
        },
        startEditing(user) {
            this.editUserData = { ...user };
            this.isEditing = true;
        },
        async saveUserEdit() {
            try {
                await axios.patch(`http://127.0.0.1:8000/api/admin/users/${this.editUserData.id}`, {
                    name: this.editUserData.name,
                    email: this.editUserData.email,
                    role_id: this.editUserData.role_id
                }, this.getAuthHeader());
                this.isEditing = false;
                this.fetchUsers();
            } catch (error) {
                console.error('Ошибка редактирования пользователя:', error.response?.data || error.message);
            }
        }
    },
    mounted() {
        this.fetchUsers();
    }
};
</script>

<style scoped>
.admin-users {
    max-width: 800px;
    margin: 120px auto 60px;
    padding: 24px;
    background: #f1f1f1;
    border-radius: 20px;
    font-family: 'Segoe UI', sans-serif;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
}

.title {
    font-size: 1.75rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 24px;
}

.btn-back {
    display: inline-block;
    margin-bottom: 16px;
    padding: 8px 14px;
    background-color: transparent;
    border: 2px solid #2c3e50;
    color: #2c3e50;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease-in-out;
}

.btn-back:hover {
    background-color: #2c3e50;
    color: white;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 32px;
}

.full-span {
    grid-column: span 2;
}

.input {
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #ccc;
    outline: none;
    transition: border-color 0.2s;
    margin: 5px;
    font-size: 16px;
}

.input:focus {
    border-color: #999;
}

.btn {
    background-color: #2c3e50;
    color: white;
    padding: 10px;
    border-radius: 10px;
    font-weight: 500;
    transition: background-color 0.2s;
    width: 100%;
}

.btn:hover {
    background-color: #1f2e3d;
}

.btn-secondary {
    background-color: #e2e8f0;
    color: #2c3e50;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    transition: background-color 0.2s;
    border: 1px solid #ccc;
    margin-right: 10px;
}

.btn-secondary:hover {
    background-color: #cbd5e1;
}

.btn-danger {
    background-color: #ef4444;
    color: white;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    transition: background-color 0.2s;
}

.btn-danger:hover {
    background-color: #dc2626;
}

.subtitle {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 16px;
}

.table-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.table-card {
    background: white;
    border-radius: 12px;
    padding: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    color: black;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.4);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
}

.modal {
    background: white;
    padding: 32px;
    border-radius: 16px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    text-align: center;
    color: black;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2c3e50;
}

.modal input {
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #ccc;
    outline: none;
    font-size: 16px;
    transition: border-color 0.2s;
}

.modal input:focus {
    border-color: #666;
}

.btn-group {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    margin-top: 10px;
}

.text-gray-500 {
    color: #666;
}

.error {
    color:#ef4444;
}
</style>