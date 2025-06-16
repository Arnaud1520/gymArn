<template>
  <div v-if="programme">
    <h1>{{ programme.name }}</h1>

    <button @click="isEditing = !isEditing" class="btn-edit">
      {{ isEditing ? 'Annuler' : 'Modifier le programme' }}
    </button>

    <ul>
      <li v-for="(item, index) in programme.programmeExercices" :key="index">
        <h3>{{ item.exercice.name }}</h3>
        <p>Séries : {{ item.series }}</p>
        <p>Répétitions : {{ item.repetitions }}</p>
        <p>Poids : {{ item.poids !== null ? item.poids + ' kg' : 'Pas de poids renseigné' }}</p>

        <button v-if="isEditing" @click="removeExercice(index)" class="btn-remove">Retirer</button>
      </li>
    </ul>

    <div v-if="isEditing" class="edit-form">
      <h3>Ajouter un exercice</h3>
      <select v-model="newExercice.exercice">
        <option disabled value="">-- Choisir un exercice --</option>
        <option v-for="ex in exercices" :value="ex['@id']" :key="ex.id">
          {{ ex.name }}
        </option>
      </select>
      <input type="number" v-model.number="newExercice.series" placeholder="Séries" />
      <input type="number" v-model.number="newExercice.repetitions" placeholder="Répétitions" />
      <input type="number" v-model.number="newExercice.poids" placeholder="Poids (kg)" />

      <button @click="addExercice" class="btn-add">Ajouter</button>
      <button @click="submitUpdate" class="btn-save">Enregistrer les modifications</button>
    </div>
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
      exercices: [],
      error: null,
      isEditing: false,
      newExercice: {
        exercice: '',
        series: null,
        repetitions: null,
        poids: null,
      },
    }
  },
  async created() {
    const id = this.$route.params.id
    const token = localStorage.getItem('token')

    try {
      const res = await axios.get(`http://localhost:8000/api/programmes/${id}`, {
        headers: { Authorization: `Bearer ${token}` },
      })
      this.programme = res.data

      const resExos = await axios.get(`http://localhost:8000/api/exercices`, {
        headers: { Authorization: `Bearer ${token}` },
      })
      console.log('Liste des exercices récupérés :', resExos.data)
      this.exercices = resExos.data.member
    } catch (err) {
      console.error('Erreur chargement :', err.response?.data || err)
    }
  },
  methods: {
    addExercice() {
      if (!this.newExercice.exercice) return
      this.programme.programmeExercices.push({ ...this.newExercice })
      this.newExercice = { exercice: '', series: null, repetitions: null, poids: null }
    },
    removeExercice(index) {
      this.programme.programmeExercices.splice(index, 1)
    },
    async submitUpdate() {
  const token = localStorage.getItem('token')
  const id = this.programme.id

  // Prépare les exercices en envoyant l'IRI dans 'exercice'
  const preparedProgrammeExercices = this.programme.programmeExercices.map(item => ({
    id: item.id,
    exercice: typeof item.exercice === 'string' ? item.exercice : item.exercice['@id'], // juste l'IRI
    series: item.series,
    repetitions: item.repetitions,
    poids: item.poids,
  }))

  try {
    await axios.patch(
      `http://localhost:8000/api/programmes/${id}`,
      {
        programmeExercices: preparedProgrammeExercices,
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/merge-patch+json',
        },
      }
    )
    this.isEditing = false
    alert('Modifications enregistrées !')
  } catch (err) {
    console.error('Erreur mise à jour :', err.response?.data || err)
  }
  },
},
}
</script>

<style scoped>
/* Ajout des styles des nouveaux boutons */
.btn-edit,
.btn-add,
.btn-remove,
.btn-save {
  background-color: #3498db;
  color: white;
  border: none;
  padding: 8px 14px;
  margin: 10px 5px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s ease;
}
.btn-edit:hover,
.btn-add:hover,
.btn-remove:hover,
.btn-save:hover {
  background-color: #2980b9;
}
.btn-remove {
  background-color: #e74c3c;
}
.btn-remove:hover {
  background-color: #c0392b;
}
.edit-form {
  margin-top: 30px;
  background-color: #ffffff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(44, 62, 80, 0.1);
}
.edit-form input,
.edit-form select {
  display: block;
  width: 100%;
  margin-bottom: 12px;
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
}
</style>
