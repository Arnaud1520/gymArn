import LoginRegister from '@/components/LoginRegister.vue'
import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/Dashboard.vue'
import Home from '../views/Home.vue'
import ProgrammeDetail from '../views/ProgrammeDetail.vue'; // Import du composant détail

const routes = [
  { path: '/', name: 'home', component: Home },
  { path: '/dashboard', name: 'dashboard', component: Dashboard },
  { path: '/login-register', name: 'login-register', component: LoginRegister },
  { 
    path: '/programme/:id',    // Route dynamique avec paramètre id
    name: 'ProgrammeDetail',
    component: ProgrammeDetail,
    props: true               // Pour passer le paramètre id en prop au composant
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
