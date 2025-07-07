<template>
  <HeaderSection />
  <div class="page-container">
    <div v-if="exercice">
      <h1>{{ exercice.name }}</h1>
      <p>Catégorie : {{ exercice.categorie }}</p>
      <p>Description : {{ exercice.description }}</p>
      <div class="image-container" v-if="exercice.image">
        <img :src="getImageUrl(exercice.image)" alt="Image exercice" />
      </div>

      <button @click="isEditing = !isEditing" class="btn-edit">
        {{ isEditing ? 'Annuler' : 'Modifier l’exercice' }}
      </button>

      <div v-if="isEditing" class="edit-form">
        <input v-model="form.name" placeholder="Nom de l’exercice" />
        <input v-model="form.categorie" placeholder="Catégorie" />
        <textarea v-model="form.description" placeholder="Description"></textarea>

        <div>
          <label>Changer l’image :</label>
          <input type="file" @change="handleFileUpload" />
          <button v-if="form.imageFile" @click="uploadImage" class="btn-save" style="margin-top: 10px;">
            Upload Image
          </button>
        </div>

        <button @click="submitUpdate" class="btn-save" style="margin-top: 20px;">Enregistrer les modifications</button>
      </div>
    </div>

    <div v-else>
      <p>Chargement...</p>
    </div>
  </div>
</template>


<script setup>
import HeaderSection from '@/components/HeaderSection.vue'
import axios from 'axios'
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const exercice = ref(null)
const isEditing = ref(false)
const form = ref({
  name: '',
  categorie: '',
  description: '',
  imageFile: null
})

// URL de base pour accéder aux images 
const IMAGE_BASE_URL = 'http://localhost:8000/uploads/exercices/' // ou le dossier où tes images sont stockées

function getImageUrl(filename) {
  return IMAGE_BASE_URL + filename
}

function handleFileUpload(event) {
  form.value.imageFile = event.target.files[0]
}

async function fetchExercice() {
  const token = localStorage.getItem('token')
  try {
    const res = await axios.get(`http://localhost:8000/api/exercices/${route.params.id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    exercice.value = res.data
    form.value.name = res.data.name
    form.value.categorie = res.data.categorie
    form.value.description = res.data.description
    form.value.imageFile = null
  } catch (error) {
    console.error("Erreur chargement exercice :", error)
  }
}

async function submitUpdate() {
  const token = localStorage.getItem('token')
  try {
    await axios.patch(`http://localhost:8000/api/exercices/${route.params.id}`, {
      name: form.value.name,
      categorie: form.value.categorie,
      description: form.value.description
    }, {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/merge-patch+json'
      }
    })
    alert('Informations mises à jour avec succès !')
    await fetchExercice()
    isEditing.value = false
  } catch (error) {
    console.error('Erreur mise à jour exercice :', error.response?.data || error)
  }
}

async function uploadImage() {
  if (!form.value.imageFile) return alert("Veuillez sélectionner une image")

  const token = localStorage.getItem('token')
  const formData = new FormData()
  formData.append('imageFile', form.value.imageFile)

  try {
    // Ici, tu dois avoir un endpoint dans Symfony pour gérer uniquement l'upload d'image, par ex:
    // POST /api/exercices/{id}/upload-image
    await axios.post(`http://localhost:8000/api/exercices/${route.params.id}/upload-image`, formData, {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'multipart/form-data'
      }
    })
    alert('Image uploadée avec succès !')
    form.value.imageFile = null
    await fetchExercice()
  } catch (error) {
    console.error('Erreur upload image :', error.response?.data || error)
  }
}

onMounted(fetchExercice)
</script>

<style scoped>
.btn-edit,
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
.btn-save:hover {
  background-color: #2980b9;
}
.edit-form {
  margin-top: 20px;
  padding: 20px;
  background: #f9f9f9;
  border-radius: 10px;
}
.edit-form input,
.edit-form textarea {
  display: block;
  width: 100%;
  margin-bottom: 12px;
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
}

.image-container {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.image-container img {
  max-width: 600px;
  width: 100%;
  height: auto;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s ease;
}

.image-container img:hover {
  transform: scale(1.02);
}

/* Conteneur principal */
div[v-cloak] {
  display: none;
}

.page-container {
  max-width: 900px;
  margin: 40px auto;
  padding: 20px;
  padding-top: 100px; /* Ajout pour éviter que la navbar cache le haut */
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Titre */
h1 {
  text-align: center;
  color: #2c3e50;
  margin-bottom: 10px;
}

/* Texte général */
p {
  font-size: 16px;
  color: #555;
  margin: 8px 0;
  text-align: center;
}

/* Image exercice */
.image-container {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.image-container img {
  max-width: 600px;
  width: 100%;
  height: auto;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s ease;
}

.image-container img:hover {
  transform: scale(1.02);
}

/* Boutons */
.btn-edit,
.btn-save {
  background-color: #3498db;
  color: white;
  border: none;
  padding: 10px 18px;
  margin: 12px 8px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.btn-edit:hover,
.btn-save:hover {
  background-color: #2980b9;
}

/* Formulaire d'édition */
.edit-form {
  margin-top: 30px;
  padding: 20px;
  background: #f2f4f8;
  border-radius: 10px;
  border: 1px solid #dcdde1;
}

.edit-form input,
.edit-form textarea {
  display: block;
  width: 100%;
  margin-bottom: 16px;
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-size: 15px;
}

/* Input file */
.edit-form input[type="file"] {
  padding: 6px;
}

/* Texte de chargement */
div p {
  text-align: center;
  color: #888;
  font-style: italic;
}


</style>
