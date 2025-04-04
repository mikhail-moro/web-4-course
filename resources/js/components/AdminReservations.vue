<template>
    <div class="admin-reservations">
        <router-link to="/admin" class="btn-back">← Назад к панели администратора</router-link>
        <h2 class="title">Управление бронированиями</h2>

        <!-- Форма добавления -->
        <form @submit.prevent="createReservation" class="form-grid">
            <input v-model.number="newReservation.user_id" type="number" placeholder="ID пользователя" class="input" required />
            <input v-model.number="newReservation.table_id" type="number" placeholder="ID столика" class="input" required />
            <input v-model="newReservation.start" type="datetime-local" class="input" required />
            <input v-model="newReservation.end" type="datetime-local" class="input" required />
            <input v-model.number="newReservation.guests" type="number" placeholder="Количество гостей" class="input" required />
            <button type="submit" class="btn full-span">Добавить бронирование</button>
        </form>

        <!-- Список -->
        <h3 class="subtitle">Список бронирований</h3>
        <div v-if="reservations.length" class="table-list">
            <div v-for="res in reservations" :key="res.id" class="table-card">
                <div>
                    <p class="font-semibold">ID: {{ res.id }}</p>
                    <p class="text-sm text-gray-500">Пользователь: {{ res.user_id }} | Столик: {{ res.table_id }}</p>
                    <p class="text-sm">
                        Начало: {{ res.start }}<br />
                        Конец: {{ res.end }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <button @click="startEditing(res)" class="btn-secondary">Редактировать</button>
                    <button @click="deleteReservation(res.id)" class="btn-danger">Удалить</button>
                </div>
            </div>
        </div>
        <p v-else class="text-gray-500">Нет активных бронирований.</p>

        <!-- Модальное окно -->
        <div v-if="isEditing" class="modal-overlay">
            <div class="modal">
                <h3 class="modal-title">Редактировать бронирование</h3>
                <input v-model.number="editReservationData.user_id" type="number" class="input" placeholder="ID пользователя" />
                <input v-model.number="editReservationData.table_id" type="number" class="input" placeholder="ID столика" />
                <input v-model="editReservationData.start" type="datetime-local" class="input" />
                <input v-model="editReservationData.end" type="datetime-local" class="input" />
                <input v-model.number="editReservationData.guests" type="number" class="input" placeholder="Количество гостей" />
                <div class="btn-group">
                    <button @click="saveReservationEdit" class="btn">Сохранить</button>
                    <button @click="isEditing = false" class="btn-secondary">Отмена</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'AdminReservations',
    data() {
        return {
            reservations: [],
            newReservation: {
                user_id: '',
                table_id: '',
                guests: '',
                start: '',
                end: ''
            },
            editReservationData: {
                id: null,
                user_id: '',
                table_id: '',
                guests: '',
                start: '',
                end: ''
            },
            isEditing: false
        };
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
        async fetchReservations() {
            try {
                const res = await axios.get('http://127.0.0.1:8000/api/admin/reservations', this.getAuthHeader());
                this.reservations = res.data.data;
            } catch (e) {
                console.error('Ошибка загрузки бронирований:', e.response?.data || e.message);
            }
        },
        async createReservation() {
            try {
                const { user_id, table_id, guests, start, end } = this.newReservation;

                const formattedStart = new Date(start).toISOString().replace('T', ' ').slice(0, 19);
                const formattedEnd = new Date(end).toISOString().replace('T', ' ').slice(0, 19);

                const payload = {
                    user_id: Number(user_id),
                    table_id: Number(table_id),
                    guests: Number(guests),
                    start: formattedStart,
                    end: formattedEnd,
                    confirmation_code: `CONF-${Math.floor(Math.random() * 100000)}`,
                    confirmed: true
                };

                await axios.post('http://127.0.0.1:8000/api/admin/reservations', payload, this.getAuthHeader());

                this.newReservation = {
                    user_id: '',
                    table_id: '',
                    guests: '',
                    start: '',
                    end: ''
                };

                this.fetchReservations();
            } catch (e) {
                console.error('Ошибка создания бронирования:', e.response?.data || e.message);
            }
        },
        async deleteReservation(id) {
            try {
                await axios.delete(`http://127.0.0.1:8000/api/admin/reservations/${id}`, this.getAuthHeader());
                this.fetchReservations();
            } catch (e) {
                console.error('Ошибка удаления:', e.response?.data || e.message);
            }
        },
        startEditing(res) {
            this.editReservationData = {
                id: res.id,
                user_id: res.user_id,
                table_id: res.table_id,
                guests: res.guests,
                start: res.start.replace(' ', 'T'),
                end: res.end.replace(' ', 'T')
            };
            this.isEditing = true;
        },
        async saveReservationEdit() {
            try {
                const { id, user_id, table_id, guests, start, end } = this.editReservationData;

                const formattedStart = new Date(start).toISOString().replace('T', ' ').slice(0, 19);
                const formattedEnd = new Date(end).toISOString().replace('T', ' ').slice(0, 19);

                const payload = {
                    user_id: Number(user_id),
                    table_id: Number(table_id),
                    guests: Number(guests),
                    start: formattedStart,
                    end: formattedEnd
                };

                await axios.patch(`http://127.0.0.1:8000/api/admin/reservations/${id}`, payload, this.getAuthHeader());
                this.isEditing = false;
                this.fetchReservations();
            } catch (e) {
                console.error('Ошибка обновления:', e.response?.data || e.message);
            }
        }
    },
    mounted() {
        this.fetchReservations();
    }
};
</script>

<style scoped>
.admin-reservations {
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
</style>