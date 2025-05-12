<template>
    <div class="register-container">
      <h2>Inscription</h2>
  
      <form @submit.prevent="registerUser">
        <div class="form-group">
          <label for="name">Nom :</label>
          <input type="text" id="name" v-model="name" required />
        </div>
  
        <div class="form-group">
          <label for="email">Email :</label>
          <input type="email" id="email" v-model="email" required />
        </div>
  
        <div class="form-group">
          <label for="password">Mot de passe :</label>
          <input type="password" id="password" v-model="password" required />
        </div>
  
        <div class="form-group">
          <label for="confirmPassword">Confirmer le mot de passe :</label>
          <input type="password" id="confirmPassword" v-model="confirmPassword" required />
        </div>
  
        <div class="form-group">
          <label for="phone">Téléphone :</label>
          <input type="text" id="phone" v-model="phone" />
        </div>
  
        <div class="form-group">
          <label for="poids">Poids (kg) :</label>
          <input type="number" id="poids" v-model="poids" step="0.1" />
        </div>
  
        <div class="form-group">
          <label for="taille">Taille (cm) :</label>
          <input type="number" id="taille" v-model="taille" step="0.1" />
        </div>
  
        <div class="form-group">
          <label for="sexe">Sexe :</label>
          <select id="sexe" v-model="sexe">
            <option value="Homme">Homme</option>
            <option value="Femme">Femme</option>
            <option value="Autre">Autre</option>
          </select>
        </div>
  
        <button type="submit">S'inscrire</button>
  
        <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
        <p v-if="successMessage" class="success">{{ successMessage }}</p>
      </form>
    </div>
  </template>
  
  <script setup>
  import axios from 'axios'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
  
  const name = ref('')
  const email = ref('')
  const password = ref('')
  const confirmPassword = ref('')
  const phone = ref('')
  const poids = ref('')
  const taille = ref('')
  const sexe = ref('')
  const errorMessage = ref('')
  const successMessage = ref('')
  const router = useRouter()
  
  const registerUser = async () => {
    if (password.value !== confirmPassword.value) {
      errorMessage.value = 'Les mots de passe ne correspondent pas !'
      return
    }
  
    try {
      const response = await axios.post('/api/register', {
        name: name.value,
        email: email.value,
        password: password.value,
        phone: phone.value || null,
        poids: poids.value || null,
        taille: taille.value || null,
        sexe: sexe.value || null
      })
  
      successMessage.value = 'Inscription réussie ! Redirection en cours...'
      errorMessage.value = ''
  
      setTimeout(() => {
        router.push('/login') // Redirige vers la page de connexion après succès
      }, 2000)
  
      // Réinitialisation des champs
      name.value = ''
      email.value = ''
      password.value = ''
      confirmPassword.value = ''
      phone.value = ''
      poids.value = ''
      taille.value = ''
      sexe.value = ''
    } catch (error) {
      errorMessage.value = 'Erreur lors de l’inscription. Veuillez réessayer.'
      successMessage.value = ''
    }
  }
  </script>
  
  <style scoped>
  .register-container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
  }
  
  h2 {
    color: #333;
    margin-bottom: 20px;
  }
  
  .form-group {
    margin-bottom: 15px;
    text-align: left;
  }
  
  label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
  }
  
  input, select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  
  button {
    background-color: #007bff;
    color: white;
    padding: 10px;
    border: none;
    width: 100%;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    transition: 0.3s;
  }
  
  button:hover {
    background-color: #0056b3;
  }
  
  .error {
    color: red;
    margin-top: 10px;
  }
  
  .success {
    color: green;
    margin-top: 10px;
  }
  </style>
  