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
            <div 
                v-for="table in tables" 
                :key="table.id" 
                class="table-card"
                :class="{ 'reserved-shadow': table.reserved, 'available-shadow': !table.reserved }"
            >
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
            tables: [
                { id: 1, seats: 2, reserved: false, image: '/stolik.jpg' },
                { id: 2, seats: 4, reserved: true, image: '/stolik.jpg' },
                { id: 3, seats: 6, reserved: false, image: '/stolik.jpg' },
                { id: 4, seats: 2, reserved: true, image: '/stolik.jpg' },
                { id: 5, seats: 8, reserved: false, image: '/stolik.jpg' },
                { id: 6, seats: 4, reserved: false, image: '/stolik.jpg' }
            ],
        };
    },
    methods: {
        submitBooking() {
            this.showTables = true;
        },
        reserveTable(table) {
            if (!table.reserved) {
                alert(`Вы забронировали столик №${table.id}`);
                table.reserved = true;
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

input, select {
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
