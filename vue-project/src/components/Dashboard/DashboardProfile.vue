<template>
    <main class="content">
      <h1>Bienvenue, {{ user.name }} 👋</h1>
  
      <section class="profile">
        <h2>Mon Profil</h2>
        <ul>
          <li><strong>Email :</strong> {{ user.email }}</li>
          <li><strong>Téléphone :</strong> {{ user.phone }}</li>
          <li><strong>Sexe :</strong> {{ user.sexe }}</li>
          <li><strong>Poids :</strong> {{ user.poids }} kg</li>
          <li><strong>Taille :</strong> {{ user.taille }} cm</li>
        </ul>
      </section>
    </main>
  </template>
  
  <script setup>
  import axios from 'axios'
import { onMounted, reactive } from 'vue'
  
  const user = reactive({
    name: '',
    email: '',
    phone: '',
    poids: '',
    taille: '',
    sexe: '',
  })
  
  onMounted(async () => {
    try {
      const token = localStorage.getItem('token')
      const { data } = await axios.get('http://localhost:8000/api/user/me', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
  
      Object.assign(user, data)
    } catch (error) {
      console.error('Erreur lors du chargement du profil :', error)
    }
  })
  </script>
  
  <style scoped>
  .content {
    padding: 40px;
    background: #f7f8fc;
  }
  
  .content h1 {
    font-size: 2rem;
    margin-bottom: 30px;
  }
  
  .profile {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    max-width: 400px;
  }
  
  .profile ul {
    list-style: none;
    padding: 0;
  }
  
  .profile li {
    margin-bottom: 10px;
  }
  </style>
  