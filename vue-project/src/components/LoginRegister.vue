<template>
    <div class="login-register-container">
      <!-- Partie gauche -->
      <div class="left-side"></div>
  
      <!-- Partie droite -->
      <div class="right-side">
        <div class="logo-container">
          <img src="@/assets/haltere.png" alt="Logo Haltère" class="logo-halter" />
          <h2 class="gymarn-logo">GymArn</h2>
        </div>
  
        <div class="button-container">
          <button
            class="btn"
            :class="{ 'active': activeTab === 'login' }"
            @click="activeTab = 'login'"
          >
            Connexion
          </button>
          <button
            class="btn"
            :class="{ 'active': activeTab === 'register' }"
            @click="activeTab = 'register'"
          >
            Inscription
          </button>
        </div>
  
        <div v-if="activeTab === 'login'" class="form-container">
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
          </form>
        </div>
  
        <div v-if="activeTab === 'register'" class="form-container">
          <form @submit.prevent="registerUser">
            <div class="form-columns">
              <!-- Colonne 1 -->
              <div class="form-column">
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
                  <label for="confirmPassword">Confirmer mot de passe :</label>
                  <input type="password" id="confirmPassword" v-model="confirmPassword" required />
                </div>
              </div>
  
              <!-- Colonne 2 -->
              <div class="form-column">
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
              </div>
            </div>
  
            <button type="submit" class="submit-button">S'inscrire</button>
            <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
            <p v-if="successMessage" class="success-message">{{ successMessage }}</p>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import axios from 'axios';
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';
  
  const name = ref('');
  const email = ref('');
  const password = ref('');
  const confirmPassword = ref('');
  const phone = ref('');
  const poids = ref('');
  const taille = ref('');
  const sexe = ref('');
  const errorMessage = ref('');
  const successMessage = ref('');
  const activeTab = ref('login');
  const router = useRouter();
  
  const passwordMismatch = computed(() => {
    return password.value && confirmPassword.value && password.value !== confirmPassword.value;
  });
  
const login = async () => {
  try {
    const response = await axios.post('http://localhost:8000/api/login', {
      email: email.value,
      password: password.value,
    });

    localStorage.setItem('token', response.data.token);

    // Récupérer les infos utilisateur
    const userRes = await axios.get('http://localhost:8000/api/user/me', {
      headers: {
        Authorization: `Bearer ${response.data.token}`
      }
    });

    localStorage.setItem('userId', userRes.data.id);
    localStorage.setItem('userName', userRes.data.name);
    // Tu peux stocker plus si tu veux

    router.push('/');
  } catch (error) {
    errorMessage.value = 'Identifiants incorrects';
  }
};


  
  const registerUser = async () => {
    if (passwordMismatch.value) {
      alert('Les mots de passe ne correspondent pas.');
      return;
    }
  
    try {
      const response = await axios.post('http://localhost:8000/api/register', {
        name: name.value,
        email: email.value,
        password: password.value,
        phone: phone.value || null,
        poids: poids.value || null,
        taille: taille.value || null,
        sexe: sexe.value || null,
      });
  
      const { name: registeredName } = response.data;
  
      successMessage.value = 'Inscription réussie ! Redirection en cours...';
      errorMessage.value = '';
  
      setTimeout(() => {
        router.push({ name: 'home', params: { name: registeredName } });
      }, 1500);
  
    } catch (error) {
      console.error('Erreur lors de l\'inscription :', error.response?.data || error);
      alert('Erreur lors de l\'inscription.');
    }
  };
  </script>
  
  <style scoped>
  /* Garde ton style existant */
  .login-register-container {
    display: flex;
    min-height: 100vh;
  }
  
  .left-side {
    flex: 1;
    background-image: url('@/assets/loginbg1.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
  }
  
  .right-side {
    flex: 1;
    background: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px;
  }
  
  .logo-container {
    text-align: center;
    margin-bottom: 30px;
  }
  
  .logo-halter {
    width: 100px;
    margin-bottom: 10px;
  }
  
  .gymarn-logo {
    font-size: 2rem;
    font-weight: bold;
    color: #00bfff;
  }
  
  .button-container {
    display: flex;
    justify-content: space-around;
    width: 100%;
    margin-bottom: 30px;
  }
  
  .button-container .btn {
    background: none;
    border: 2px solid #00bfff;
    color: #00bfff;
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 1rem;
    cursor: pointer;
    transition: 0.3s;
  }
  
  .button-container .btn:hover {
    background: #00bfff;
    color: #fff;
  }
  
  .button-container .btn.active {
    border-color: #0056b3;
    color: #0056b3;
  }
  
  .form-container {
    width: 100%;
    max-width: 800px;
    padding: 20px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  }
  
  /* Ajout du style pour deux colonnes */
  .form-columns {
    display: flex;
    gap: 20px;
  }
  
  .form-column {
    flex: 1;
  }
  
  /* Garde le style des champs */
  .form-group {
    margin-bottom: 15px;
  }
  
  input, select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 1rem;
  }
  
  input:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
  }
  
  .submit-button {
    margin-top: 20px;
    width: 100%;
    background-color: #007bff;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 8px;
    font-size: 1.2rem;
    cursor: pointer;
  }
  
  .submit-button:hover {
    background-color: #0056b3;
  }
  
  .error-message {
    color: red;
    margin-top: 10px;
  }
  
  .success-message {
    color: green;
    margin-top: 10px;
  }

  @media (max-width: 768px) {
  .login-register-container {
    flex-direction: column;
  }

  .left-side {
    display: none; 
  }

  .right-side {
    flex: none;
    width: 100%;
    padding: 10px;
  }

  .button-container {
    flex-direction: column;
    gap: 10px;
    align-items: center;
  }

  .button-container .btn {
    width: 80%;
    font-size: 1rem;
  }

  .form-columns {
    flex-direction: column;
    gap: 0;
  }

  .form-container {
    padding: 10px;
  }

  .submit-button {
    font-size: 1rem;
    padding: 10px;
  }

  .logo-halter {
    width: 80px;
  }

  .gymarn-logo {
    font-size: 1.5rem;
  }
}

  </style>
  