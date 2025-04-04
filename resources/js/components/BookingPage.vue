<template>
    <div class="booking-container">
        <h2 class="texth2">ОНЛАЙН-БРОНИРОВАНИЕ</h2>
        <p class="sub-text">ПОДДЕРЖИВАЕТСЯ OPENTABLE</p>

        <form @submit.prevent="submitBooking" class="booking-form">
            <div class="input-group">
                <span class="icon">📅</span>
                <input type="date" v-model="bookingDate" required />
            </div>

            <div class="input-group">
                <span class="icon">⏰</span>
                <input type="time" v-model="bookingTime" required />
            </div>

            <div class="input-group">
                <span class="icon">👤</span>
                <select v-model="guestCount" required>
                    <option v-for="n in 10" :key="n" :value="n">{{ n }} человек</option>
                </select>
            </div>

            <button type="submit">НАЙТИ СТОЛИК</button>
        </form>

        <!-- Карточки столиков -->
        <div v-if="showTables" class="tables-container">
            <div v-for="table in tables" :key="table.id" class="table-card"
                :class="{ 'reserved-shadow': table.reserved, 'available-shadow': !table.reserved }">
                <img :src="table.image" class="table-image" alt="Столик">
                <div class="table-info">
                    <h3>Столик №{{ table.id }}</h3>
                    <p>👥 {{ table.seats }} мест(а)</p>
                    <button v-if="!table.reserved" @click="reserveTable(table)">Забронировать</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            bookingDate: '',
            bookingTime: '',
            guestCount: 2,
            showTables: false,
            tables: [],
            bookingStart: '',
            bookingEnd: ''
        };
    },
    methods: {
        async submitBooking() {
            try {
                const startDate = new Date(`${this.bookingDate}T${this.bookingTime}`);
                const endDate = new Date(startDate.getTime() + 2 * 60 * 60 * 1000);

                const start = startDate.toISOString().replace('T', ' ').slice(0, 19);
                const end = endDate.toISOString().replace('T', ' ').slice(0, 19);

                this.bookingStart = start;
                this.bookingEnd = end;

                const token = localStorage.getItem('auth_token');

                const res = await fetch(
                    `http://127.0.0.1:8000/api/tables?guests=${this.guestCount}&start=${start}&end=${end}`,
                    {
                        headers: {
                            Authorization: token
                        }
                    }
                );

                if (!res.ok) throw new Error('Ошибка при загрузке столиков');
                this.tables = await res.json();
                this.showTables = true;
            } catch (e) {
                console.error('Ошибка получения столиков:', e);
                alert('Не удалось загрузить столики');
            }
        },

        async reserveTable(table) {
            if (table.reserved) return;

            try {
                const token = localStorage.getItem('auth_token');

                const payload = {
                    table_id: table.id,
                    guests: this.guestCount,
                    start: this.bookingStart,
                    end: this.bookingEnd
                };

                const res = await fetch('http://127.0.0.1:8000/api/reservations', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Authorization: token
                    },
                    body: JSON.stringify(payload)
                });

                if (!res.ok) {
                    const err = await res.json();
                    throw new Error(err.message || 'Ошибка бронирования');
                }

                alert(`Столик №${table.id} успешно забронирован!`);
                table.reserved = true;
            } catch (e) {
                console.error('Ошибка при бронировании:', e);
                alert('Не удалось забронировать столик');
            }
        }
    }
};
</script>


<style scoped>
.booking-container {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 20px;
    background: white;
    margin-top: 82px;
}

h2 {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 5px;
    color: black;
}

.texth2 {
    color: black;
}

.sub-text {
    font-size: 14px;
    color: gray;
    margin-bottom: 20px;
}

.booking-form {
    display: flex;
    gap: 10px;
    align-items: center;
    justify-content: center;
}

.input-group {
    display: flex;
    align-items: center;
    background: #f8f8f8;
    border-radius: 5px;
    padding: 5px 10px;
}

.icon {
    font-size: 16px;
    margin-right: 5px;
}

input,
select {
    border: none;
    background: transparent;
    font-size: 14px;
    padding: 5px;
    outline: none;
}

button {
    background: black;
    color: white;
    font-size: 14px;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
}

/* Карточки столиков */
.tables-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
}

.table-card {
    width: 230px;
    background: #f8f8f8;
    border-radius: 8px;
    overflow: hidden;
    text-align: center;
    padding-bottom: 10px;
    transition: 0.3s;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}

/* Тень для забронированных */
.reserved-shadow {
    box-shadow: 0px 4px 15px rgba(216, 24, 24, 0.6);
}

/* Тень для доступных */
.available-shadow {
    box-shadow: 0px 4px 15px rgba(21, 163, 21, 0.562);
}

.table-image {
    width: 100%;
    height: 140px;
    object-fit: cover;
}

.table-info {
    padding: 10px;
}

h3 {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 5px;
    color: black;
}

p {
    font-size: 14px;
    margin: 5px 0;
    color: black;
}

.table-card button {
    background: green;
    color: white;
    border: none;
    padding: 8px;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 10px;
}

.table-card button:hover {
    background: darkgreen;
}

/* Адаптивность */
@media (max-width: 768px) {
    .tables-container {
        flex-direction: column;
        align-items: center;
    }

    .table-card {
        width: 90%;
    }
}
</style>
