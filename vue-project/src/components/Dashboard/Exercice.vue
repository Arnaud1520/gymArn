<template>
  <div class="container">
    <h2 class="title">Liste des exercices</h2>

    <div class="button-group">
      <button @click="toggleForm" class="add-btn">Ajouter un exercice</button>
    </div>

    <div v-if="showForm" class="add-form">
      <input v-model="newExerciseName" placeholder="Nom de l'exercice" />
      <input v-model="newExerciseCategorie" placeholder="Catégorie (ex: Pecs, Jambes...)" />
      <input v-model="newExerciseDescription" placeholder="Description de l'exercice" />
      <input type="file" @change="handleFileUpload" />
      <button @click="ajouterExercice" class="submit-btn">Valider</button>
    </div>

    <input
      v-model="searchQuery"
      type="text"
      placeholder="Rechercher un exercice..."
      class="search-bar"
    />

    <div v-if="loading" class="loading">Chargement...</div>

    <div v-else class="table-wrapper">
      <table class="programme-table">
        <thead>
          <tr>
            <th @click="sortBy('name')" class="sortable">
              Nom
              <span v-if="sortKey === 'name'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
            </th>
            <th @click="sortBy('categorie')" class="sortable">
              Catégorie
              <span v-if="sortKey === 'categorie'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
            </th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="exercice in paginatedExercices" :key="getId(exercice)">
            <td>{{ exercice.name }}</td>
            <td>{{ exercice.categorie }}</td>
            <td>{{ exercice.description }}</td>
            <td>
              <button @click="voirDetails(getId(exercice))" class="action-btn">Voir</button>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="paginatedExercices.length === 0" class="no-result">
        Aucun exercice trouvé.
      </div>

      <div class="pagination" v-if="totalPages > 1">
        <button @click="prevPage" :disabled="currentPage === 1">Précédent</button>
        <span>Page {{ currentPage }} / {{ totalPages }}</span>
        <button @click="nextPage" :disabled="currentPage === totalPages">Suivant</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios'
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

const exercices = ref([])
const loading = ref(true)
const searchQuery = ref('')
const sortKey = ref('')
const sortOrder = ref('asc')
const router = useRouter()

const currentPage = ref(1)
const itemsPerPage = 5

const showForm = ref(false)
const newExerciseName = ref('')
const newExerciseCategorie = ref('')
const newExerciseDescription = ref('')
const newExerciseFile = ref(null)

function toggleForm() {
  showForm.value = !showForm.value
}

function handleFileUpload(event) {
  const file = event.target.files[0]
  if (file) {
    newExerciseFile.value = file
  }
}

async function ajouterExercice() {
  if (!newExerciseName.value || !newExerciseCategorie.value || !newExerciseDescription.value || !newExerciseFile.value) {
    alert("Veuillez remplir tous les champs et sélectionner une image.")
    return
  }

  const token = localStorage.getItem('token')
  const formData = new FormData()

  formData.append('name', newExerciseName.value)
  formData.append('categorie', newExerciseCategorie.value)
  formData.append('description', newExerciseDescription.value)
  formData.append('imageFile', newExerciseFile.value)

  try {
    await axios.post('http://localhost:8000/api/exercices', formData, {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'multipart/form-data'
      }
    })

    alert("Exercice ajouté avec succès !")
    newExerciseName.value = ''
    newExerciseCategorie.value = ''
    newExerciseDescription.value = ''
    newExerciseFile.value = null
    showForm.value = false
    fetchExercices()
  } catch (error) {
    console.error("❌ Erreur lors de l'ajout :", error)
    alert("Erreur lors de l'ajout.")
  }
}

async function fetchExercices() {
  const token = localStorage.getItem('token')
  try {
    const response = await axios.get('http://localhost:8000/api/exercices', {
      headers: { Authorization: `Bearer ${token}` },
    })
    exercices.value = response.data.member ?? response.data
  } catch (error) {
    console.error('Erreur lors du chargement des exercices :', error)
  } finally {
    loading.value = false
  }
}

onMounted(fetchExercices)

function voirDetails(id) {
  router.push(`/exercice/${id}`)
}

function getId(exercice) {
  if (exercice.id) return exercice.id
  if (exercice['@id']) return exercice['@id'].split('/').pop()
  return null
}

function sortBy(key) {
  if (sortKey.value === key) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortKey.value = key
    sortOrder.value = 'asc'
  }
  currentPage.value = 1
}

const filteredAndSortedExercices = computed(() => {
  let result = exercices.value.filter((e) => {
    const nameMatch = e.name?.toLowerCase().includes(searchQuery.value.toLowerCase())
    const categorieMatch = e.categorie?.toLowerCase().includes(searchQuery.value.toLowerCase())
    return nameMatch || categorieMatch
  })

  if (sortKey.value === 'name' || sortKey.value === 'categorie') {
    result.sort((a, b) => {
      const aVal = a[sortKey.value]?.toLowerCase() ?? ''
      const bVal = b[sortKey.value]?.toLowerCase() ?? ''
      return sortOrder.value === 'asc'
        ? aVal.localeCompare(bVal)
        : bVal.localeCompare(aVal)
    })
  }

  return result
})

const totalPages = computed(() => {
  return Math.ceil(filteredAndSortedExercices.value.length / itemsPerPage)
})

const paginatedExercices = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredAndSortedExercices.value.slice(start, start + itemsPerPage)
})

function nextPage() {
  if (currentPage.value < totalPages.value) currentPage.value++
}

function prevPage() {
  if (currentPage.value > 1) currentPage.value--
}
</script>

<style scoped>
.container {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
  background-color: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.title {
  font-size: 1.8rem;
  font-weight: 700;
  color: #0a0a0a;
  margin-bottom: 1.5rem;
  text-align: center;
}

.button-group {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  margin-bottom: 1rem;
  justify-content: center;
}

.add-btn {
  background-color: #10b981;
  color: white;
  padding: 0.6rem 1.2rem;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.add-btn:hover {
  background-color: #059669;
}

.add-form {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.add-form input {
  flex: 1 1 100%;
  min-width: 100px;
  padding: 0.5rem;
  border-radius: 6px;
  border: 1px solid #d1d5db;
}

.submit-btn {
  background-color: #10b981;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  border: none;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.submit-btn:hover {
  background-color: #059669;
}

.search-bar {
  width: 100%;
  padding: 0.75rem;
  margin-bottom: 1.5rem;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 1rem;
}

.loading {
  text-align: center;
  font-size: 1.1rem;
  color: #3b82f6;
}

.table-wrapper {
  overflow-x: auto;
}

.programme-table {
  width: 100%;
  border-collapse: collapse;
  border: 1px solid #e5e7eb;
}

.programme-table th,
.programme-table td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid #e5e7eb;
  color: #111827;
}

.programme-table thead {
  background-color: #f0f4ff;
}

.programme-table tr:hover {
  background-color: #f1f5ff;
  transition: background-color 0.2s ease-in-out;
}

.sortable {
  cursor: pointer;
  user-select: none;
}

.action-btn {
  background-color: #2563eb;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-size: 0.9rem;
  transition: background-color 0.2s ease;
  cursor: pointer;
  border: none;
}

.action-btn:hover {
  background-color: #1e40af;
}

.no-result {
  text-align: center;
  padding: 1rem;
  color: #6b7280;
  font-style: italic;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1rem;
  margin-top: 1.5rem;
  flex-wrap: wrap;
}

.pagination button {
  background-color: #1d4ed8;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.pagination button:disabled {
  background-color: #93c5fd;
  cursor: not-allowed;
}

@media screen and (max-width: 600px) {
  .add-form {
    flex-direction: column;
  }

  .programme-table th,
  .programme-table td {
    font-size: 0.9rem;
    padding: 0.5rem;
  }

  .add-btn,
  .submit-btn,
  .action-btn {
    width: 100%;
    font-size: 1rem;
  }

  .pagination {
    flex-direction: column;
  }
}


.container {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
  background-color: #1e293b; /* bleu très foncé (dark slate blue) */
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
}

/* Titre principal */
.title {
  font-size: 1.8rem;
  font-weight: 700;
  color: white; /* texte en blanc */
  margin-bottom: 1.5rem;
  text-align: center;
}

/* Tableau - en-tête et lignes */
.programme-table thead {
  background-color: #334155; /* un bleu-gris foncé */
}

.programme-table th,
.programme-table td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid #475569;
  color: white; /* texte en blanc */
}

.programme-table tr:hover {
  background-color: #475569; /* hover plus clair */
  transition: background-color 0.2s ease-in-out;
}

/* Boutons - tu peux garder tes couleurs existantes */
.add-btn {
  background-color: #10b981;
  color: white;
  /* ... */
}

.add-btn.secondary {
  background-color: #3b82f6;
}
</style>






















