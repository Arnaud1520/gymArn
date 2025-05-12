<template>
    <div class="register-container">
      <div class="overlay"></div> <!-- Overlay sombre -->
      <div class="register-box">
        <h2 class="title">Inscription</h2>
        <form @submit.prevent="register" class="register-form">
          <div class="form-group">
            <label for="name">Nom :</label>
            <input type="text" v-model="name" id="name" required />
          </div>
          <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" v-model="email" id="email" required />
          </div>
          <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" v-model="password" id="password" required />
          </div>
          <div class="form-group">
            <label for="phone">Téléphone :</label>
            <input type="tel" v-model="phone" id="phone" />
          </div>
          <div class="form-group">
            <label for="poids">Poids (kg) :</label>
            <input type="number" v-model="poids" id="poids" step="0.1" />
          </div>
          <div class="form-group">
            <label for="taille">Taille (cm) :</label>
            <input type="number" v-model="taille" id="taille" step="0.1" />
          </div>
          <div class="form-group">
            <label for="sexe">Sexe :</label>
            <select v-model="sexe" id="sexe" required>
              <option value="Homme">Homme</option>
              <option value="Femme">Femme</option>
            </select>
          </div>
          <button type="submit" class="submit-button">S'inscrire</button>
          <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
  
          <!-- Liens supplémentaires -->
          <div class="extra-links">
            <router-link to="/login" class="link">Déjà inscrit ?</router-link>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        name: '',
        email: '',
        password: '',
        phone: '',
        poids: null,
        taille: null,
        sexe: 'Homme',
        errorMessage: ''
      };
    },
    methods: {
      async register() {
        try {
          const response = await axios.post('http://127.0.0.1:8000/api/register', {
            name: this.name,
            email: this.email,
            password: this.password,
            phone: this.phone,
            poids: this.poids,
            taille: this.taille,
            sexe: this.sexe
          });
  
          this.$router.push('/login'); // Rediriger après inscription
        } catch (error) {
          this.errorMessage = "Erreur lors de l'inscription. Veuillez réessayer.";
        }
      }
    }
  };
  </script>
  
  <style scoped>
  @import '@/assets/Register.css'; /* Import du fichier CSS externe */
  </style>
  