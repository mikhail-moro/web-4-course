<template>
  <div class="container">
    <h1>Привет, Vue.js в Laravel!</h1>
    <div class="buttons">
      <button @click="showRegister = true">Регистрация</button>
      <button @click="showLogin = true">Вход</button>
    </div>

    <!-- Модальное окно регистрации -->
    <transition name="fade">
      <div v-if="showRegister" class="modal-overlay" @click="closeModals">
        <div class="modal-content" @click.stop>
          <register-component @close="closeModals"></register-component>
        </div>
      </div>
    </transition>

    <!-- Модальное окно входа -->
    <transition name="fade">
      <div v-if="showLogin" class="modal-overlay" @click="closeModals">
        <div class="modal-content" @click.stop>
          <login-component @close="closeModals"></login-component>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import Register from "./Register.vue";
import Login from "./Login.vue";

export default {
  components: {
    "register-component": Register,
    "login-component": Login,
  },
  data() {
    return {
      showRegister: false,
      showLogin: false,
    };
  },
  methods: {
    closeModals() {
      this.showRegister = false;
      this.showLogin = false;
    },
  },
};
</script>

<style scoped>
/* Основной контейнер */
.container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 100vh;
  font-family: 'Playfair Display', serif;
  font-size: 24px;
  color: #ffffff;
  background-color: #282c34;
}

/* Кнопки */
.buttons {
  margin-top: 20px;
}

button {
  margin: 10px;
  padding: 10px 20px;
  font-size: 18px;
  cursor: pointer;
  border: none;
  border-radius: 5px;
  transition: 0.3s;
}

button:first-child {
  background-color: #d4af37;
  color: black;
}

button:last-child {
  background-color: #b8860b;
  color: black;
}

button:hover {
  opacity: 0.8;
}

/* Стили для модальных окон */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8); /* Затемненный фон */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: rgba(40, 40, 40, 0.98);
  padding: 40px;
  border-radius: 12px;
  text-align: center;
  width: 350px;
  color: #d4af37;
  font-family: 'Playfair Display', serif;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
  position: relative;
}

/* Анимация появления */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>
