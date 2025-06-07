<template>
  <div v-if="programme">
    <h1>{{ programme.name }}</h1>

    <ul>
      <li v-for="item in programme.programmeExercices" :key="item.id">
        <h3>{{ item.exercice.name }}</h3>
        <p>Séries : {{ item.series }}</p>
        <p>Répétitions : {{ item.repetitions }}</p>
        <p>Poids : {{ item.poids !== null ? item.poids + ' kg' : 'Pas de poids renseigné' }}</p>
      </li>
    </ul>
  </div>
  <div v-else>
    <p>Chargement...</p>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      programme: null,
      error: null,
    }
  },
  async created() {
    const programmeId = this.$route.params.id
    const token = localStorage.getItem('token') // ou autre endroit où tu stockes le token

    try {
      const response = await axios.get(`http://localhost:8000/api/programmes/${programmeId}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      this.programme = response.data
    } catch (error) {
      console.warn('Erreur lors du chargement du programme :', error.response?.data || error)
      this.error = 'Impossible de charger le programme'
    }
  },
}
</script>

<style scoped>
/* Conteneur principal */
div {
  max-width: 600px;
  margin: 40px auto;
  padding: 20px;
  background-color: #f9fafb;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #333;
}

/* Titre du programme */
h1 {
  font-size: 2.5rem;
  font-weight: 700;
  margin-bottom: 30px;
  color: #2c3e50;
  text-align: center;
}

/* Liste des exercices */
ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

/* Chaque exercice */
li {
  background-color: white;
  padding: 20px;
  margin-bottom: 18px;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(44, 62, 80, 0.1);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

/* Effet au survol */
li:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(44, 62, 80, 0.15);
}

/* Nom de l'exercice */
h3 {
  margin-top: 0;
  margin-bottom: 12px;
  color: #34495e;
  font-size: 1.4rem;
  font-weight: 600;
}

/* Détails des séries, répétitions, poids */
p {
  margin: 6px 0;
  font-size: 1rem;
  color: #555;
}

/* Message de chargement ou erreur */
p {
  text-align: center;
  font-style: italic;
  color: #999;
}
</style>
