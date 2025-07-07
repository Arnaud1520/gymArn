<template>
  <HeaderSection />
  <div v-if="programme" class="programme-page">
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
import HeaderSection from '@/components/HeaderSection.vue'
import axios from 'axios'

export default {
  components: {
    HeaderSection
  },
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

      const preparedProgrammeExercices = this.programme.programmeExercices.map(item => ({
        id: item.id,
        exercice: typeof item.exercice === 'string' ? item.exercice : item.exercice['@id'],
        series: item.series,
        repetitions: item.repetitions,
        poids: item.poids,
      }))

      try {
        await axios.patch(
          `http://localhost:8000/api/programmes/${id}`,
          { programmeExercices: preparedProgrammeExercices },
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
.programme-page {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #2c3e50;
  padding: 20px;
  max-width: 900px;
  margin: 100px auto 30px;
  background-color: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

h1 {
  text-align: center;
  font-size: 32px;
  margin-bottom: 20px;
  color: #34495e;
}

ul {
  list-style: none;
  padding: 0;
  margin-top: 20px;
}

li {
  background-color: #f9f9f9;
  padding: 16px;
  border-radius: 10px;
  margin-bottom: 15px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
  position: relative;
}

li h3 {
  margin: 0 0 6px;
  font-size: 20px;
  color: #2d3e50;
}

li p {
  margin: 4px 0;
  font-size: 15px;
  color: #555;
}

.btn-edit,
.btn-add,
.btn-remove,
.btn-save {
  background-color: #3498db;
  color: white;
  border: none;
  padding: 10px 16px;
  margin: 12px 6px 0 0;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.btn-edit:hover,
.btn-add:hover,
.btn-save:hover {
  background-color: #2980b9;
}

.btn-remove {
  background-color: #e74c3c;
  margin-top: 10px;
}

.btn-remove:hover {
  background-color: #c0392b;
}

.edit-form {
  margin-top: 30px;
  padding: 20px;
  background-color: #ecf0f1;
  border-radius: 10px;
  border: 1px solid #ccc;
}

.edit-form h3 {
  margin-bottom: 15px;
  font-size: 22px;
  color: #2c3e50;
}

.edit-form input,
.edit-form select {
  display: block;
  width: 100%;
  margin-bottom: 12px;
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #bdc3c7;
  font-size: 15px;
  box-sizing: border-box;
}

div > p {
  text-align: center;
  color: #888;
  font-style: italic;
  margin-top: 40px;
}
</style>
