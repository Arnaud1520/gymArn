<template>
  <div class="home">
    <HeaderSection />
    <HeroSection />
    <CategoriesSection />
    <AboutSection />
    <ArticlesSection />
    <Testimonials />
    <FooterSection />
  </div>
</template>

<script setup>
import axios from 'axios'
import { onMounted } from 'vue'

import ArticlesSection from '@/components/ArticlesSection.vue'
import FooterSection from '@/components/FooterSection.vue'
import Testimonials from '@/components/Home/Testimonials.vue'
import HeaderSection from '../components/HeaderSection.vue'
import AboutSection from '../components/Home/AboutSection.vue'
import CategoriesSection from '../components/Home/CategoriesSection.vue'
import HeroSection from '../components/Home/HeroSection.vue'

// Vérification de la connexion de l'utilisateur
onMounted(async () => {
 
  const token = localStorage.getItem('token')

  if (!token) {
    console.log('Utilisateur non connecté (pas de token)')
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
    console.warn('Token invalide ou expiré :', error.response?.data || error)
  }
})
</script>

<style scoped>
.home {
  font-family: 'Arial', sans-serif;
}
</style>
