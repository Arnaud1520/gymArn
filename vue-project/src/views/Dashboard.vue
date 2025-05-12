<template>
    <div class="dashboard">
      <h1>Bonjour, {{ user.name }}!</h1>
      <div class="user-info">
        <div class="info-card">
          <h3>Mes Informations</h3>
          <p>Email: {{ user.email }}</p>
          <p>Poids: {{ user.poids }} kg</p>
          <p>Taille: {{ user.taille }} cm</p>
          <p>Sexe: {{ user.sexe }}</p>
        </div>
  
        <div class="info-card">
          <h3>Programmes</h3>
          <ul>
            <li v-for="programme in user.programmes" :key="programme.id">
              {{ programme.name }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import axios from 'axios'
import { onMounted, ref } from 'vue'
  
  const user = ref({
    name: '',
    email: '',
    poids: '',
    taille: '',
    sexe: '',
    programmes: []
  })
  
  onMounted(async () => {
    try {
      // Remplace l'URL par celle de ton API qui renvoie les infos de l'utilisateur
      const response = await axios.get('/api/user/me')
      user.value = response.data
    } catch (error) {
      console.error('Erreur lors de la récupération des informations de l\'utilisateur:', error)
    }
  })
  </script>
  
  <style scoped>
  .dashboard {
    padding: 20px;
    background-color: #f4f4f4;
  }
  
  .user-info {
    display: flex;
    justify-content: space-between;
    gap: 20px;
  }
  
  .info-card {
    background-color: white;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    width: 45%;
  }
  
  .info-card h3 {
    font-size: 1.5em;
    margin-bottom: 15px;
  }
  
  .info-card p {
    margin: 5px 0;
  }
  </style>
  