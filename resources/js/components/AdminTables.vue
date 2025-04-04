<template>
    <div class="admin-tables">
        <router-link to="/admin" class="btn-back">← Назад к панели администратора</router-link>
        <h2 class="title">Управление столиками</h2>

        <!-- Форма добавления -->
        <form @submit.prevent="createTable" class="form-grid">
            <input v-model="newTable.number" type="text" placeholder="Номер столика" class="input" required />
            <input v-model.number="newTable.seats" type="number" placeholder="Количество мест" class="input" required />
            <button type="submit" class="btn full-span">Добавить столик</button>
        </form>

        <!-- Таблица -->
        <div>
            <h3 class="subtitle">Список столиков</h3>
            <div v-if="tables.length > 0" class="table-list">
                <div v-for="table in tables" :key="table.id" class="table-card">
                    <div>
                        <p class="font-semibold">Столик №{{ table.number }}</p>
                        <p class="text-sm text-gray-500">Мест: {{ table.seats }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button @click="startEditing(table)" class="btn-secondary">Редактировать</button>
                        <button @click="deleteTable(table.id)" class="btn-danger">Удалить</button>
                    </div>
                </div>
            </div>
            <p v-else class="text-gray-500">Нет доступных столиков.</p>
        </div>

        <!-- Модальное окно редактирования -->
        <div v-if="isEditing" class="modal-overlay">
            <div class="modal">
                <h3 class="modal-title">Редактировать столик</h3>
                <input v-model="editTableData.number" class="input" type="text" placeholder="Номер столика" />
                <input v-model.number="editTableData.seats" class="input" type="number" placeholder="Количество мест" />
                <div class="btn-group">
                    <button @click="saveTableEdit" class="btn">Сохранить</button>
                    <button @click="isEditing = false" class="btn-secondary">Отмена</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'AdminTables',
    data() {
        return {
            tables: [],
            newTable: {
                number: '',
                seats: 0
            },
            editTableData: {
                id: null,
                number: '',
                seats: 0
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
                    'Content-Type': 'application/json',
                }
            };
        },
        async fetchTables() {
            try {
                const response = await axios.get('/api/admin/tables', this.getAuthHeader());
                this.tables = response.data.data;
            } catch (error) {
                console.error('Ошибка загрузки столиков:', error.response?.data || error.message);
            }
        },
        async createTable() {
            try {
                await axios.post('/api/admin/tables', this.newTable, this.getAuthHeader());
                this.newTable = { number: '', seats: 0 };
                this.fetchTables();
            } catch (error) {
                console.error('Ошибка добавления столика:', error.response?.data || error.message);
            }
        },
        async deleteTable(id) {
            try {
                await axios.delete(`/api/admin/tables/${id}`, this.getAuthHeader());
                this.fetchTables();
            } catch (error) {
                console.error('Ошибка удаления столика:', error.response?.data || error.message);
            }
        },
        startEditing(table) {
            this.editTableData = { ...table };
            this.isEditing = true;
        },
        async saveTableEdit() {
            try {
                await axios.patch(`/api/admin/tables/${this.editTableData.id}`, {
                    number: this.editTableData.number,
                    seats: this.editTableData.seats
                }, this.getAuthHeader());
                this.isEditing = false;
                this.fetchTables();
            } catch (error) {
                console.error('Ошибка при сохранении изменений:', error.response?.data || error.message);
            }
        }
    },
    mounted() {
        this.fetchTables();
    }
};
</script>

<style scoped>
.admin-tables {
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
