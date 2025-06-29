<template>
  <div class="dashboard">
    <HeaderSection />
    <DashboardProfile />
    <SeanceCalendar />

    <!-- ✅ Flex horizontal entre Programme et Exercices -->
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

// ✅ Composants
import DashboardProfile from '@/components/Dashboard/DashboardProfile.vue'
import Exercices from '@/components/Dashboard/Exercice.vue'
import Programmes from '@/components/Dashboard/Programme.vue'
import SeanceCalendar from '@/components/Dashboard/SeanceCalendar.vue'
import FooterSection from '@/components/FooterSection.vue'; // ✅ Ajout manquant
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
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

/* ✅ Mise en page côte à côte */
.programme-exercice {
  display: flex;
  gap: 2rem;
  padding: 1rem;
  justify-content: space-between;
}

/* Optionnel : tu peux forcer une taille sur les composants */
.programme-exercice > * {
  flex: 1;
}
</style>
