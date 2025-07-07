import LoginRegister from '@/components/LoginRegister.vue'
import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/Dashboard.vue'
import ExerciceDetail from '../views/ExerciceDetail.vue'
import Home from '../views/Home.vue'
import ProgrammeDetail from '../views/ProgrammeDetail.vue'

const routes = [
  { path: '/', name: 'home', component: Home },
  { path: '/dashboard', name: 'dashboard', component: Dashboard, meta: { requiresAuth: true } },
  { path: '/login-register', name: 'login-register', component: LoginRegister },
  { path: '/programme/:id', name: 'ProgrammeDetail', component: ProgrammeDetail, props: true },
  { path: '/ajouter-programme', name: 'AjouterProgramme', component: () => import('@/views/AjouterProgramme.vue') },
  { path: '/exercices', name: 'Exercices', component: () => import('@/components/Dashboard/Exercice.vue') },
  { path: '/exercice/:id', name: 'ExerciceDetail', component: ExerciceDetail, props: true },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// 💡 Vérification globale de l'authentification
router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('token')

  if (to.meta.requiresAuth && !isAuthenticated) {
    next({ name: 'login-register' })
  } else {
    next()
  }
})

export default router
