<template>
    <div class="reservation-page">
        <header>
            <h1>ÉLITE GASTRO</h1>
            <button @click="goToProfile">Профиль</button>
        </header>

        <!-- Форма бронирования -->
        <section class="reservation-form">
            <h2>Забронировать столик</h2>
            <form @submit.prevent="findTables">
                <label>Дата: <input type="date" v-model="date" required></label>
                <label>Время: <input type="time" v-model="time" required></label>
                <label>Гостей: <input type="number" v-model="guests" min="1" required></label>
                <button type="submit">Найти столик</button>
            </form>
        </section>

        <!-- Схема ресторана -->
        <section v-if="tables.length" class="table-layout">
            <h2>Выберите столик</h2>
            <div class="restaurant-map">
                <div v-for="table in tables" :key="table.id" class="table"
                    :class="{ 'free': table.isAvailable, 'occupied': !table.isAvailable }" @click="selectTable(table)">
                    {{ table.id }}
                </div>
            </div>
            <button v-if="selectedTable" @click="confirmReservation">Подтвердить бронирование</button>
        </section>
    </div>
</template>

<script>
export default {
    data() {
        return {
            date: '',
            time: '',
            guests: 1,
            tables: [],
            selectedTable: null,
        };
    },
    methods: {
        findTables() {
            // Пример данных. Позже заменить API-запросом
            this.tables = [
                { id: 1, isAvailable: true },
                { id: 2, isAvailable: false },
                { id: 3, isAvailable: true },
                { id: 4, isAvailable: false },
            ];
        },
        selectTable(table) {
            if (table.isAvailable) {
                this.selectedTable = table.id;
            }
        },
        confirmReservation() {
            alert(`Вы забронировали столик №${this.selectedTable} на ${this.date} в ${this.time}`);
        },
        goToProfile() {
            this.$router.push('/profile');
        }
    }
};
</script>

<style scoped>
.reservation-page {
    text-align: center;
}

header {
    display: flex;
    justify-content: space-between;
    padding: 20px;
    background: black;
    color: white;
}

button {
    background: gold;
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    font-weight: bold;
}

.table-layout {
    margin-top: 20px;
}

.restaurant-map {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.table {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid black;
    cursor: pointer;
    font-size: 18px;
}

.free {
    background: green;
    color: white;
}

.occupied {
    background: red;
    color: white;
    cursor: not-allowed;
}
</style>