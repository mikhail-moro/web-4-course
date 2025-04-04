<template>
    <div class="profile-container">
        <h2>Ваш профиль</h2>

        <!-- Блок информации о пользователе -->
        <div class="profile-info">
            <p><strong>Имя:</strong> {{ user.name }}</p>
            <p><strong>Email:</strong> {{ user.email }}</p>
        </div>

        <h3>Мои бронирования</h3>

        <!-- Список забронированных столиков -->
        <div v-if="reservations.length > 0" class="reservations-list">
            <div v-for="reservation in reservations" :key="reservation.id" class="reservation-card">
                <img :src="'/stolik.jpg'" class="table-image" alt="Столик">
                <div class="reservation-details">
                    <h4>Столик №{{ reservation.table_id }}</h4>
                    <p><strong>Дата:</strong> {{ formatDate(reservation.start) }}</p>
                    <p><strong>Время:</strong> {{ formatTime(reservation.start) }} – {{ formatTime(reservation.end) }}</p>
                    <p><strong>Гостей:</strong> {{ reservation.guests }}</p>
                    <p><strong>Статус:</strong> {{ reservation.confirmed ? 'Подтверждено' : 'Ожидает подтверждения' }}</p>
                    <div class="actions">
                        <button class="edit-btn" @click="editReservation(reservation.id)">Редактировать</button>
                        <button class="cancel-btn" @click="cancelReservation(reservation.id)">Отменить</button>
                    </div>
                </div>
            </div>
        </div>

        <p v-else class="no-reservations">Вы пока не забронировали ни одного столика.</p>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: {
                name: localStorage.getItem('user_name') || 'Загрузка...',
                email: localStorage.getItem('user_email') || 'Загрузка...'
            },
            reservations: []
        };
    },
    methods: {
        async fetchReservations() {
            const token = localStorage.getItem('auth_token');
            try {
                const response = await fetch('http://127.0.0.1:8000/api/reservations', {
                    headers: {
                        Authorization: token,
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                this.reservations = data.data || data; // поддержка paginate и простого списка
            } catch (error) {
                console.error('Ошибка загрузки бронирований:', error);
            }
        },
        formatDate(datetime) {
            return datetime.split(' ')[0];
        },
        formatTime(datetime) {
            return datetime.split(' ')[1].slice(0, 5);
        },
        async cancelReservation(id) {
            const token = localStorage.getItem('auth_token');
            try {
                await fetch(`http://127.0.0.1:8000/api/reservations/${id}`, {
                    method: 'DELETE',
                    headers: {
                        Authorization: token,
                        'Content-Type': 'application/json'
                    }
                });
                this.reservations = this.reservations.filter(res => res.id !== id);
            } catch (e) {
                console.error('Ошибка отмены бронирования:', e);
            }
        },
        editReservation(id) {
            alert(`Редактирование бронирования столика №${id}`);
        }
    },
    mounted() {
        this.fetchReservations();
    }
};
</script>

<style scoped>
/* Основной контейнер */
.profile-container {
    max-width: 700px;
    margin-top: 120px;
    margin-bottom: 50px;
    padding: 20px;
    background: white;
    border-radius: 15px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
    text-align: center;
}

/* Заголовки */
h2,
h3 {
    color: black;
    font-weight: bold;
    margin-bottom: 15px;
}

/* Блок информации о пользователе */
.profile-info {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 10px;
    text-align: left;
    font-size: 16px;
    color: #333;
    box-shadow: inset 0px 0px 5px rgba(0, 0, 0, 0.1);
}

/* Список бронирований */
.reservations-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 15px;
}

/* Карточка бронирования */
.reservation-card {
    display: flex;
    align-items: center;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    padding: 10px;
    transition: 0.3s;
}

.reservation-card:hover {
    transform: scale(1.02);
}

/* Фото столика */
.table-image {
    width: 120px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
}

/* Детали бронирования */
.reservation-details {
    flex: 1;
    padding: 0 15px;
    text-align: left;
    color: black;
}

/* Блок с кнопками */
.actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

/* Кнопка редактирования */
.edit-btn {
    background: #ffc107;
    color: black;
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
}

/* Кнопка отмены */
.cancel-btn {
    background: red;
    color: white;
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
}

/* Текст "нет бронирований" */
.no-reservations {
    color: gray;
    font-size: 16px;
    margin-top: 15px;
}
</style>
