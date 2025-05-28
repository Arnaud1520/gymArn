<template>
  <div>
    <h2>Liste des programmes</h2>
    <div v-if="loading">Chargement...</div>
    <ul v-else>
      <li v-for="programme in programmes" :key="programme.id">
        {{ programme.name }}
        <button @click="voirDetails(programme.id)">Voir détails</button>
      </li>
    </ul>
  </div>
</template>

<script setup>
import axios from 'axios'
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

const programmes = ref([])
const loading = ref(true)
const router = useRouter()

onMounted(async () => {
  const token = localStorage.getItem('token')

  try {
    const response = await axios.get('http://localhost:8000/api/programmes', {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })
    programmes.value = response.data
  } catch (error) {
    console.error('Erreur lors du chargement des programmes:', error)
  } finally {
    loading.value = false
  }
})

function voirDetails(id) {
  router.push(`/programme/${id}`)
}
</script>
