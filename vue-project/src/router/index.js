import LoginRegister from '@/components/LoginRegister.vue'
import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/Dashboard.vue'
import Home from '../views/Home.vue'

const routes = [
  { path: '/', name: 'home', component: Home },
  { path: '/dashboard', name: 'dashboard', component: Dashboard },
  {path: '/login-register', name: 'login-register', component: LoginRegister, // Le composant que tu as créé
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
