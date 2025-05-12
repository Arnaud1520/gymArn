<template>
  <div class="login-page">
    <div class="login-container">
      <div class="login-box">
        <h2 class="title">Connexion</h2>
        <form @submit.prevent="login" class="login-form">
          <div class="form-group">
            <label>Email :</label>
            <input type="email" v-model="email" required />
          </div>
          <div class="form-group">
            <label>Mot de passe :</label>
            <input type="password" v-model="password" required />
          </div>
          <button type="submit" class="submit-button">Se connecter</button>
          <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>

          <!-- Liens supplémentaires -->
          <div class="extra-links">
            <router-link to="/forgot-password" class="link">Mot de passe oublié ?</router-link>
            <router-link to="/register" class="link">Pas encore inscrit ?</router-link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      email: '',
      password: '',
      errorMessage: ''
    };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('http://127.0.0.1:8000/api/login', {
          email: this.email,
          password: this.password
        });

        localStorage.setItem('token', response.data.token);
        this.$router.push('/dashboard'); // Redirige après connexion
      } catch (error) {
        this.errorMessage = "Identifiants incorrects";
      }
    }
  }
};
</script>

<style>
@import '@/assets/Login.css'; /* Import du fichier CSS externe */
</style>
