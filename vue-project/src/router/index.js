import LoginRegister from '@/components/LoginRegister.vue';
import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '../views/Dashboard.vue';
import ExerciceDetail from '../views/ExerciceDetail.vue'; // ou le chemin correct
import Home from '../views/Home.vue';
import ProgrammeDetail from '../views/ProgrammeDetail.vue';

const routes = [
  { path: '/', name: 'home', component: Home },
  { path: '/dashboard', name: 'dashboard', component: Dashboard },
  { path: '/login-register', name: 'login-register', component: LoginRegister },
  { path: '/programme/:id', name: 'ProgrammeDetail', component: ProgrammeDetail, props: true },
  { path: '/ajouter-programme', name: 'AjouterProgramme', component: () => import('@/views/AjouterProgramme.vue')},
  { path: '/exercices', name: 'Exercices', component: () => import('@/components/Dashboard/Exercice.vue')},
  { path: '/exercice/:id', name: 'ExerciceDetail', component: ExerciceDetail, props: true, // pour recevoir id en props
  },

]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
