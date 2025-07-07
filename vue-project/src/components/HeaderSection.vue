<template>
  <header class="header">
    <div class="logo">GYMARN</div>

    <nav class="nav">
      <ul :class="{ 'is-open': isNavOpen }">
        <li><a href="/">Accueil</a></li>
        <li><a href="#">À propos</a></li>
        <li><a href="/dashboard">Dashboard</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </nav>

    <div class="auth-buttons">
      <a v-if="!isLoggedIn" href="/login-register" class="btn-login">
        Se connecter
      </a>
      <button v-else @click="logout" class="btn-logout">
        Se déconnecter
      </button>
    </div>

    <button class="btn-hamburger" @click="toggleNav" aria-label="Toggle navigation">
      <span class="hamburger-icon"></span>
    </button>
  </header>
</template>

<script>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

export default {
  setup() {
    const isNavOpen = ref(false)
    const isLoggedIn = ref(false)
    const router = useRouter()

    const toggleNav = () => {
      isNavOpen.value = !isNavOpen.value
    }

    const logout = () => {
      localStorage.removeItem('token')
      isLoggedIn.value = false
      router.push('/login-register')
    }

    onMounted(() => {
      isLoggedIn.value = !!localStorage.getItem('token')
      if (isLoggedIn.value && router.currentRoute.value.path === '/login-register') {
        router.push('/')
      }
    })

    return {
      isNavOpen,
      isLoggedIn,
      toggleNav,
      logout
    }
  }
}
</script>

<style scoped>
.header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 999;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 40px;
  background: #121212;
  color: #fff;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
}

.logo {
  font-weight: 900;
  font-size: 28px;
  color: #FFFF; 
  text-transform: uppercase;
  letter-spacing: 1px;
}

.nav ul {
  list-style: none;
  display: flex;
  gap: 30px;
  margin: 0;
  padding: 0;
}

.nav ul li a {
  color: #ffffff;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s;
}

.nav ul li a:hover {
  color: #FFD600;
}

.auth-buttons {
  display: flex;
  align-items: center;
  gap: 15px;
}

.btn-login {
  background-color: #3668E8;
  color: #121212;
  padding: 10px 20px;
  border-radius: 30px;
  font-weight: bold;
  text-decoration: none;
  transition: background-color 0.3s;
}

.btn-login:hover {
  background-color: #e0c000;
}

.btn-logout {
  background-color: #FF3B3B;
  color: #fff;
  padding: 10px 20px;
  border-radius: 30px;
  border: none;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-logout:hover {
  background-color: #cc2f2f;
}

.btn-hamburger {
  display: none;
  background: none;
  border: none;
  cursor: pointer;
}

.hamburger-icon {
  width: 25px;
  height: 3px;
  background-color: #FFD600;
  display: block;
  position: relative;
  transition: 0.3s;
}

.hamburger-icon::before,
.hamburger-icon::after {
  content: "";
  width: 25px;
  height: 3px;
  background-color: #FFD600;
  position: absolute;
  transition: 0.3s;
}

.hamburger-icon::before {
  top: -8px;
}

.hamburger-icon::after {
  top: 8px;
}

.nav ul.is-open {
  display: block;
}

@media (max-width: 768px) {
  .nav ul {
    display: none;
    flex-direction: column;
    background-color: #1f1f1f;
    position: absolute;
    top: 70px;
    left: 0;
    right: 0;
    padding: 20px;
  }

  .nav ul.is-open {
    display: flex;
  }

  .btn-hamburger {
    display: block;
  }
}
</style>
