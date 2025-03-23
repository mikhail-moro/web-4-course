<template>
    <div class="landing-page">
        <!-- Фоновая секция (полноэкранная) -->
        <div class="hero-section">
            <div class="overlay"></div>
            <div class="content">
                <h1><span>ÉLITE</span> GASTRO</h1>
                <p>Откройте для себя изысканные блюда и атмосферу высшего уровня.</p>
            </div>
        </div>

        <!-- Раздел информации -->
        <section class="info-section">
            <div class="info-block">
                <img src="https://i.pinimg.com/originals/d7/f5/66/d7f5667c31446af13d0dd73a6306c014.jpg" alt="Dining">
                <div class="text">
                    <h2>Приватный зал</h2>
                    <p>Роскошный зал на 10 мест, идеально подходит для частных мероприятий и особых случаев.</p>
                </div>
            </div>

            <div class="info-block reverse">
                <div class="text">
                    <h2>Основной зал</h2>
                    <p>Открытая кухня, изысканная атмосфера, вместимость до 25 гостей.</p>
                </div>
                <img src="https://cdn1.flamp.ru/0c7b2e54fff7bb7b9b03ec21825a4c21.jpg" alt="Crown">
            </div>
        </section>
    </div>
</template>

<style scoped>
.landing-page {
    font-family: 'Playfair Display', serif;
    color: white;
    text-align: center;
    width: 100vw;
}

.hero-section {
    position: relative;
    width: 100vw;
    height: 50vh;
    background: url('https://source.unsplash.com/1920x1080/?restaurant,dark') center/cover no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
}

.content {
    position: relative;
    z-index: 2;
    max-width: 80%;
}

h1 {
    font-size: 50px;
    font-weight: bold;
    text-transform: uppercase;
}

h1 span {
    font-style: italic;
    color: #d4af37;
}

p {
    font-size: 18px;
    margin-top: 10px;
}

/* --- Информационные секции (на всю ширину) --- */
.info-section {
    background: white;
    color: black;
    display: flex;
    flex-direction: column;
    gap: 100px;
    width: 100vw;
    padding-top: 50px;
    padding-bottom: 50px;
}

.info-block {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
}

.info-block img {
    width: 50%;
    height: auto;
    border-radius: 10px;
}

.text {
    width: 45%;
    text-align: left;
}

h2 {
    font-size: 32px;
    font-weight: bold;
    margin-bottom: 10px;
}
</style>