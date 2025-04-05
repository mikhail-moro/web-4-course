<template>
    <div class="profile-container">
        <h2>Ваш профиль</h2>

        <div class="profile-info">
            <p><strong>Имя:</strong> {{ user.name }}</p>
            <p><strong>Email:</strong> {{ user.email }}</p>
        </div>

        <h3>Мои бронирования</h3>

        <div v-if="reservations.length > 0" class="reservations-list">
            <div v-for="reservation in reservations" :key="reservation.id" class="reservation-card">
                <img :src="getTableImage(reservation)" class="table-image" alt="Столик" />
                <div class="reservation-details">
                    <h4>Столик №{{ reservation.table.number }}</h4>
                    <p><strong>Дата:</strong> {{ formatDate(reservation.start) }}</p>
                    <p><strong>Время:</strong> {{ formatTime(reservation.start) }} – {{ formatTime(reservation.end) }}</p>
                    <p><strong>Гостей:</strong> {{ reservation.guests }}</p>
                    <p><strong>Статус:</strong> {{ reservation.confirmed ? '✅ Подтверждено' : '⏳ Ожидает подтверждения' }}</p>
                    <div class="actions">
                        <button class="edit-btn" @click="openEditModal(reservation)">Редактировать</button>
                        <button class="cancel-btn" @click="cancelReservation(reservation.id)">Отменить</button>
                        <button 
                            v-if="!reservation.confirmed"
                            class="confirm-btn" 
                            @click="openConfirmModal(reservation.id)"
                        >
                            Ввести код
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <p v-else class="no-reservations">Вы пока не забронировали ни одного столика.</p>

        <!-- Модальное окно редактирования -->
        <div v-if="isEditing" class="modal-overlay">
            <div class="modal">
                <h3>Редактировать бронирование</h3>
                <input v-model="edit.start" type="datetime-local" />
                <input v-model="edit.end" type="datetime-local" />
                <input v-model.number="edit.guests" type="number" placeholder="Гостей" min="1" />
                <div class="modal-actions">
                    <button @click="saveEdit">Сохранить</button>
                    <button @click="isEditing = false">Отмена</button>
                </div>
            </div>
        </div>

        <!-- Модальное окно подтверждения -->
        <div v-if="isConfirming" class="modal-overlay">
            <div class="modal">
                <h3>Подтверждение бронирования</h3>
                <input v-model="confirmCode" type="text" placeholder="Введите код из письма" />
                <div class="modal-actions">
                    <button @click="submitConfirmCode">Подтвердить</button>
                    <button @click="isConfirming = false">Отмена</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: {
                name: localStorage.getItem("user_name") || "Загрузка...",
                email: localStorage.getItem("user_email") || "Загрузка..."
            },
            reservations: [],
            isEditing: false,
            edit: {
                id: null,
                start: '',
                end: '',
                guests: 1
            },
            isConfirming: false,
            confirmReservationId: null,
            confirmCode: ''
        };
    },
    methods: {
        async fetchReservations() {
            const token = localStorage.getItem("auth_token");
            try {
                const response = await fetch("/api/reservations", {
                    headers: {
                        Authorization: token,
                        "Content-Type": "application/json"
                    }
                });
                const data = await response.json();
                this.reservations = data.data || data;
            } catch (error) {
                console.error("Ошибка загрузки бронирований:", error);
            }
        },
        async cancelReservation(id) {
            const token = localStorage.getItem("auth_token");
            try {
                await fetch(`/api/reservations/${id}`, {
                    method: "DELETE",
                    headers: {
                        Authorization: token,
                        "Content-Type": "application/json"
                    }
                });
                this.reservations = this.reservations.filter(res => res.id !== id);
            } catch (e) {
                console.error("Ошибка отмены бронирования:", e);
            }
        },
        openEditModal(reservation) {
            this.edit = {
                id: reservation.id,
                guests: reservation.guests,
                start: this.toInputDatetime(reservation.start),
                end: this.toInputDatetime(reservation.end)
            };
            this.isEditing = true;
        },
        async saveEdit() {
            const token = localStorage.getItem("auth_token");
            const payload = {
                guests: this.edit.guests,
                start: this.toMySQLFormat(this.edit.start),
                end: this.toMySQLFormat(this.edit.end)
            };
            try {
                const res = await fetch(`/api/reservations/${this.edit.id}`, {
                    method: "PATCH",
                    headers: {
                        Authorization: token,
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(payload)
                });
                if (!res.ok) {
                    const error = await res.json();
                    alert(error?.errors?.guests?.[0] || 'Ошибка при обновлении');
                    return;
                }
                this.isEditing = false;
                this.fetchReservations();
            } catch (e) {
                console.error("Ошибка обновления:", e);
            }
        },
        openConfirmModal(reservationId) {
            this.confirmReservationId = reservationId;
            this.confirmCode = '';
            this.isConfirming = true;
        },
        async submitConfirmCode() {
            const token = localStorage.getItem("auth_token");
            try {
                const res = await fetch(`/api/confirm/${this.confirmReservationId}`, {
                    method: "POST",
                    headers: {
                        Authorization: token,
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ confirmation_code: this.confirmCode })
                });
                if (!res.ok) {
                    const error = await res.json();
                    alert(error?.message || 'Ошибка подтверждения');
                    return;
                }
                this.isConfirming = false;
                this.fetchReservations();
            } catch (e) {
                console.error("Ошибка подтверждения:", e);
            }
        },
        getTableImage(reservation) {
            return reservation.table?.image ? `/images/${reservation.table.image}` : '/stolik.png';
        },
        formatDate(datetime) {
            return datetime.split(" ")[0];
        },
        formatTime(datetime) {
            return datetime.split(" ")[1].slice(0, 5);
        },
        toInputDatetime(str) {
            const [date, time] = str.split(" ");
            return `${date}T${time.slice(0, 5)}`;
        },
        toMySQLFormat(datetimeLocal) {
            const date = new Date(datetimeLocal);
            const pad = n => n.toString().padStart(2, "0");
            return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())} ${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
        }
    },
    mounted() {
        this.fetchReservations();
    }
};
</script>

<style scoped>
.profile-container {
    margin-top: 120px;
    margin-bottom: 50px;
    padding: 20px;
    background: white;
    border-radius: 15px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
    text-align: center;
}

h2,
h3 {
    color: black;
    font-weight: bold;
    margin-bottom: 15px;
}

.profile-info {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 10px;
    text-align: left;
    font-size: 16px;
    color: #333;
    box-shadow: inset 0px 0px 5px rgba(0, 0, 0, 0.1);
}

.reservations-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 15px;
}

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

.table-image {
    width: 360px;
    height: 250px;
    object-fit: cover;
    border-radius: 8px;
}

.reservation-details {
    flex: 1;
    padding: 0 15px;
    text-align: left;
    color: black;
}

.actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.edit-btn,
.cancel-btn,
.confirm-btn {
    padding: 8px 15px;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    font-weight: bold;
    font-family: 'Playfair Display', serif;
    transition: 0.3s;
    border: 2px solid;
}

.edit-btn {
    background: transparent;
    color: #d4af37;
    border-color: #d4af37;
}

.edit-btn:hover {
    background: #d4af37;
    color: white;
}

.cancel-btn {
    background: transparent;
    color: red;
    border-color: red;
}

.cancel-btn:hover {
    background: red;
    color: white;
}

.confirm-btn {
    background: transparent;
    color: rgb(36, 160, 25);
    border-color: rgb(36, 160, 25);
}

.confirm-btn:hover {
    background: rgb(36, 160, 25);
    color: white;
}

.no-reservations {
    color: gray;
    font-size: 16px;
    margin-top: 15px;
}

.modal-overlay {
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal {
    background: #fff;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    min-width: 320px;
    max-width: 90%;
    text-align: center;
}

.modal h3 {
    font-size: 20px;
    margin-bottom: 15px;
    font-weight: bold;
}

.modal input {
    width: 90%;
    margin: 10px 0;
    padding: 10px;
    border: 2px solid #ccc;
    border-radius: 12px;
    font-size: 16px;
}

.modal-actions {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 15px;
}

.modal-actions button {
    padding: 10px 20px;
    font-size: 16px;
    font-family: 'Playfair Display', serif;
    border: 2px solid #d4af37;
    background-color: transparent;
    color: #d4af37;
    border-radius: 30px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.modal-actions button:hover {
    background-color: #d4af37;
    color: white;
}
</style>
