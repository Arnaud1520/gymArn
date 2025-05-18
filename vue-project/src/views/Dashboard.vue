<template>
  <div class="dashboard">
    <HeaderSection />
    <DashboardProfile />
    <SeanceCalendar />
    <FooterSection />
  </div>
</template>

<script setup>
import axios from 'axios'
import { onMounted } from 'vue'

import DashboardProfile from '@/components/Dashboard/DashboardProfile.vue'
import SeanceCalendar from '@/components/Dashboard/SeanceCalendar.vue'
import FooterSection from '@/components/FooterSection.vue'
import HeaderSection from '@/components/HeaderSection.vue'

// 🔒 Vérification de la connexion
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
  font-family: 'Arial', sans-serif;
}
</style>
