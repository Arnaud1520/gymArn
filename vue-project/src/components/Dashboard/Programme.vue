<template>
    <div class="p-4">
      <h2 class="text-xl font-bold mb-4">Mes Programmes</h2>
  
      <!-- Formulaire d'ajout -->
      <div class="mb-4 flex gap-2">
        <input
          v-model="newProgrammeName"
          placeholder="Nom du programme"
          class="border rounded px-2 py-1"
        />
        <button @click="createProgramme" class="bg-green-600 text-white px-3 py-1 rounded">
          Ajouter
        </button>
      </div>
  
      <!-- Liste des programmes -->
      <ul class="space-y-2">
        <li
          v-for="programme in programmes"
          :key="programme.id"
          class="flex justify-between items-center border-b pb-2"
        >
          <div class="flex gap-2">
            <input
              v-model="programme.name"
              @blur="updateProgramme(programme)"
              class="border px-2 py-1"
            />
          </div>
          <button
            @click="deleteProgramme(programme.id)"
            class="text-red-500 hover:underline"
          >
            Supprimer
          </button>
        </li>
      </ul>
    </div>
  </template>
  
  <script setup>
  import axios from 'axios'
import { onMounted, ref } from 'vue'
  
  const programmes = ref([])
  const newProgrammeName = ref('')
  
  const fetchProgrammes = async () => {
    try {
      const response = await axios.get('http://localhost:8000/api/programmes')
      programmes.value = response.data
    } catch (error) {
      console.error('Erreur lors du chargement des programmes :', error)
    }
  }
  
  const createProgramme = async () => {
    if (!newProgrammeName.value.trim()) return
    try {
      const response = await axios.post('http://localhost:8000/api/programmes', {
        name: newProgrammeName.value,
      })
      programmes.value.push(response.data)
      newProgrammeName.value = ''
    } catch (error) {
      console.error('Erreur lors de la création du programme :', error)
    }
  }
  
  const updateProgramme = async (programme) => {
    try {
      await axios.put(`http://localhost:8000/api/programmes/${programme.id}`, {
        name: programme.name,
      })
    } catch (error) {
      console.error('Erreur lors de la mise à jour :', error)
    }
  }
  
  const deleteProgramme = async (id) => {
    try {
      await axios.delete(`http://localhost:8000/api/programmes/${id}`)
      programmes.value = programmes.value.filter(p => p.id !== id)
    } catch (error) {
      console.error('Erreur lors de la suppression :', error)
    }
  }
  
  onMounted(fetchProgrammes)
  </script>
  