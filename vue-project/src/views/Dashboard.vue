<template>
  <div class="dashboard">
    <HeaderSection />
    <DashboardProfile />
    <SeanceCalendar />

    <div class="programme-exercice">
      <Programmes />
      <Exercices />
    </div>

    <FooterSection />
  </div>
</template>

<script setup>
import axios from 'axios'
import { onMounted } from 'vue'

import DashboardProfile from '@/components/Dashboard/DashboardProfile.vue'
import Exercices from '@/components/Dashboard/Exercice.vue'
import Programmes from '@/components/Dashboard/Programme.vue'
import SeanceCalendar from '@/components/Dashboard/SeanceCalendar.vue'
import FooterSection from '@/components/FooterSection.vue'
import HeaderSection from '@/components/HeaderSection.vue'

onMounted(async () => {
  const token = localStorage.getItem('token')

  if (!token) {
    console.log('Utilisateur non connecté')
    return
  }

  try {
    const response = await axios.get('http://localhost:8000/api/user/me', {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })
    console.log('Utilisateur connecté :', response.data)
  } catch (error) {
    console.warn('Token invalide :', error.response?.data || error)
  }
})
</script>

<style scoped>
.dashboard {
  font-family: 'Segoe UI', sans-serif;
  background-color: #121212;
  color: white;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  padding-top: 80px; /* pour ne pas cacher le haut sous le header fixe */
}

/* Style flex entre Programmes et Exercices */
.programme-exercice {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  padding: 20px 40px;
  justify-content: space-between;
}

/* Pour adapter l'affichage responsive */
.programme-exercice > * {
  flex: 1 1 45%;
  background-color: #1e1e1e;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
  border-left: 5px solid #FFD600;
}

@media (max-width: 768px) {
  .programme-exercice {
    flex-direction: column;
  }
}
</style>
