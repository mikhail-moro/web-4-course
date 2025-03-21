<template>
  <div id="app">
    <!-- Хедер -->
    <Header @showRegister="showRegister = true" @showLogin="showLogin = true" />

    <!-- Главная страница -->
    <LandingPage />

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

    <AboutRestaurant />
    <Footer />
  </div>
</template>

<script>
import Register from "./Register.vue";
import Login from "./Login.vue";
import Header from "./Header.vue";
import LandingPage from "./LandingPage.vue";
import AboutRestaurant from "./AboutRestaurant.vue";
import Footer from "./Footer.vue";

export default {
  components: {
    "register-component": Register,
    "login-component": Login,
    Header,
    LandingPage,
    AboutRestaurant,
    Footer,
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

<style>
html,
body {
  margin: 0;
  padding: 0;
  width: 100vw;
  height: 100vh;
  background-color: #282c34;
  overflow-x: hidden;
}

#app {
  display: flex;
  flex-direction: column;
  align-items: center;
  color: white;
  font-family: 'Playfair Display', serif;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
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
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
