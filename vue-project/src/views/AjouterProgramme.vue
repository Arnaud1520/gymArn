<template>
  <HeaderSection />
  <div class="form-container">
    <h2>Créer un programme</h2>

    <form @submit.prevent="submitProgramme">
      <div class="form-group">
        <label for="name">Nom du programme :</label>
        <input v-model="programmeName" id="name" required />
      </div>

      <h3>Exercices</h3>
      <div
        v-for="(exercice, index) in exercicesSelectionnes"
        :key="exercice.uid"
        class="exercice-block"
      >
        <select v-model="exercice.apiId" required>
          <option disabled value="">-- Sélectionner un exercice --</option>
          <option v-for="e in exercices" :key="e['@id']" :value="e['@id']">
            {{ e.name }}
          </option>
        </select>

        <input
          v-model.number="exercice.series"
          type="number"
          placeholder="Séries"
          min="1"
          required
        />
        <input
          v-model.number="exercice.repetitions"
          type="number"
          placeholder="Répétitions"
          min="1"
          required
        />
        <input
          v-model.number="exercice.poids"
          type="number"
          placeholder="Poids (kg)"
          min="0"
          step="0.5"
          required
        />

        <button type="button" @click="removeExercice(index)" class="remove-btn">
          Supprimer
        </button>
      </div>

      <button type="button" @click="ajouterExercice" class="add-btn">
        + Ajouter un exercice
      </button>

      <button type="submit" class="submit-btn">Créer le programme</button>
    </form>

    <p v-if="message" class="success-message">{{ message }}</p>
  </div>
</template>

<script setup>
import HeaderSection from '@/components/HeaderSection.vue'
import axios from 'axios'
import { onMounted, ref, watch } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const programmeName = ref('')
const exercices = ref([])
const exercicesSelectionnes = ref([]) // départ vide
const message = ref('')

let uidCounter = 0
function createExerciceSelectionne() {
  return { uid: uidCounter++, apiId: '', series: null, repetitions: null, poids: 0 }
}

watch(
  exercicesSelectionnes,
  (newVal) => {
    console.log('exercicesSelectionnes changed:', JSON.parse(JSON.stringify(newVal)))
  },
  { deep: true }
)

onMounted(async () => {
  const token = localStorage.getItem('token')
  try {
    const response = await axios.get('http://localhost:8000/api/exercices', {
      headers: { Authorization: `Bearer ${token}` }
    })
    exercices.value = response.data['hydra:member'] ?? response.data.member ?? response.data
  } catch (error) {
    console.error('Erreur chargement des exercices :', error)
  }
  ajouterExercice() // initialise avec un exercice vide
})

function ajouterExercice() {
  exercicesSelectionnes.value.push(createExerciceSelectionne())
}

function removeExercice(index) {
  exercicesSelectionnes.value.splice(index, 1)
}

async function submitProgramme() {
  const token = localStorage.getItem('token')
  const userId = localStorage.getItem('userId')

  if (!userId) {
    alert('Utilisateur non connecté.')
    return
  }

  const validExercices = exercicesSelectionnes.value.filter(
    (ex) =>
      ex.apiId &&
      Number(ex.series) > 0 &&
      Number(ex.repetitions) > 0
  )

  if (validExercices.length === 0) {
    alert("Merci d'ajouter au moins un exercice avec séries et répétitions.")
    return
  }

  const payload = {
  name: programmeName.value,
  user: `/api/users/${userId}`, // ou `authStore.user.id`
  programmeExercices: exercicesSelectionnes.value.map((ex) => ({
    exercice: ex.apiId, // anciennement "apiId", maintenant dans "exercice"
    series: ex.series,
    repetitions: ex.repetitions,
    poids: ex.poids
  }))
}

  console.log('programmeName:', programmeName.value)
console.log('payload:', payload)

  try {
    const res = await axios.post('http://localhost:8000/api/programmes', payload, {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/ld+json'
      }
    })
    message.value = 'Programme créé avec succès !'
    setTimeout(() => {
      router.push('/')
    }, 1500)
  } catch (error) {
    console.error('Erreur création programme :', error.response?.data || error.message)
    alert('Erreur lors de la création du programme.')
  }
}
</script>


<style scoped>
.form-container {
  max-width: 700px;
  margin: auto;
  padding: 9rem 2rem 2rem;
  background: #ffffff;
  border-radius: 10px;
  box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
}

h2, h3 {
  text-align: center;
  margin-bottom: 1.5rem;
}

.form-group {
  margin-bottom: 1rem;
}

input, select {
  width: 100%;
  padding: 0.7rem;
  margin-top: 0.5rem;
  margin-bottom: 1rem;
  border: 1px solid #ccc;
  border-radius: 8px;
}

.exercice-block {
  margin-bottom: 2rem;
  padding: 1rem;
  border: 1px solid #ddd;
  border-radius: 8px;
  background: #f9f9f9;
}

.add-btn, .submit-btn, .remove-btn {
  display: inline-block;
  margin-top: 0.5rem;
  padding: 0.6rem 1.2rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
}

.add-btn {
  background-color: #3b82f6;
  color: white;
}

.submit-btn {
  background-color: #10b981;
  color: white;
  width: 100%;
  margin-top: 2rem;
}

.remove-btn {
  background-color: #ef4444;
  color: white;
  margin-top: 0.5rem;
}

.success-message {
  margin-top: 1rem;
  text-align: center;
  color: #10b981;
  font-weight: bold;
}
</style>
