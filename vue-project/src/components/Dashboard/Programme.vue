<template>
  <div class="container">
    <h2 class="title">Liste des séances</h2>

    <input
      v-model="searchQuery"
      type="text"
      placeholder="Rechercher par nom ou utilisateur..."
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
            <th @click="sortBy('user')" class="sortable">
              Créé par
              <span v-if="sortKey === 'user'">{{ sortOrder === 'asc' ? '▲' : '▼' }}</span>
            </th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="programme in paginatedProgrammes"
            :key="programme.id"
          >
            <td>{{ programme.name }}</td>
            <td>{{ programme.user?.name || 'Utilisateur inconnu' }}</td>
            <td>
              <button @click="voirDetails(programme.id)" class="action-btn">
                Voir détails
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="paginatedProgrammes.length === 0" class="no-result">
        Aucun programme trouvé.
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

const programmes = ref([])
const loading = ref(true)
const searchQuery = ref('')
const sortKey = ref('')
const sortOrder = ref('asc')
const router = useRouter()

const currentPage = ref(1)
const itemsPerPage = 5

onMounted(async () => {
  const token = localStorage.getItem('token')
  try {
    const response = await axios.get('http://localhost:8000/api/programmes', {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })
    programmes.value = response.data.member ?? response.data
  } catch (error) {
    console.error('Erreur lors du chargement des programmes :', error)
  } finally {
    loading.value = false
  }
})

function voirDetails(id) {
  router.push(`/programme/${id}`)
}

function sortBy(key) {
  if (sortKey.value === key) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortKey.value = key
    sortOrder.value = 'asc'
  }
  currentPage.value = 1 // reset to page 1 when sorting
}

const filteredAndSortedProgrammes = computed(() => {
  let result = programmes.value.filter((p) => {
    const search = searchQuery.value.toLowerCase()
    return (
      p.name.toLowerCase().includes(search) ||
      p.user?.name?.toLowerCase().includes(search)
    )
  })

  if (sortKey.value === 'name') {
    result.sort((a, b) =>
      sortOrder.value === 'asc'
        ? a.name.localeCompare(b.name)
        : b.name.localeCompare(a.name)
    )
  } else if (sortKey.value === 'user') {
    result.sort((a, b) => {
      const nameA = a.user?.name || ''
      const nameB = b.user?.name || ''
      return sortOrder.value === 'asc'
        ? nameA.localeCompare(nameB)
        : nameB.localeCompare(nameA)
    })
  }

  return result
})

const totalPages = computed(() => {
  return Math.ceil(filteredAndSortedProgrammes.value.length / itemsPerPage)
})

const paginatedProgrammes = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredAndSortedProgrammes.value.slice(start, end)
})

function nextPage() {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
  }
}

function prevPage() {
  if (currentPage.value > 1) {
    currentPage.value--
  }
}
</script>

<style scoped>
.container {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
  background-color: #ffffff;
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

.programme-table thead {
  background-color: #f0f4ff;
}

.programme-table th,
.programme-table td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid #e5e7eb;
  color: #111827;
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
</style>
