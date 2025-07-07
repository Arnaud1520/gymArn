<template>
  <main class="content">
    <h1>Bienvenue, {{ user.name }}</h1>

    <div class="profile-container">
      <section class="profile">
        <div class="header">
          <h2>Mon Profil</h2>
          <button @click="editMode = !editMode" class="edit-btn" title="Modifier">
            ✏️
          </button>
        </div>
        <ul>
          <li><strong>Email :</strong> {{ user.email }}</li>
          <li><strong>Téléphone :</strong> {{ user.phone }}</li>
          <li><strong>Sexe :</strong> {{ user.sexe }}</li>
          <li><strong>Poids :</strong> {{ user.poids }} kg</li>
          <li><strong>Taille :</strong> {{ user.taille }} cm</li>
        </ul>
      </section>

      <section v-if="editMode" class="edit-form">
        <h3>Modifier mon profil</h3>
        <form @submit.prevent="updateProfile">
          <label>Téléphone : <input v-model="editUser.phone" type="text" /></label>
          <label>Sexe :
            <select v-model="editUser.sexe">
              <option value="Homme">Homme</option>
              <option value="Femme">Femme</option>
              <option value="Autre">Autre</option>
            </select>
          </label>
          <label>Poids (kg) : <input v-model.number="editUser.poids" type="number" /></label>
          <label>Taille (cm) : <input v-model.number="editUser.taille" type="number" /></label>

          <button type="submit" class="save-btn">Enregistrer</button>
        </form>
      </section>
    </div>
  </main>
</template>

<script setup>
import axios from 'axios'
import { onMounted, reactive, ref } from 'vue'

const user = reactive({
  name: '',
  email: '',
  phone: '',
  poids: '',
  taille: '',
  sexe: '',
})

const editUser = reactive({
  phone: '',
  poids: '',
  taille: '',
  sexe: '',
})

const editMode = ref(false)

const fetchUser = async () => {
  try {
    const token = localStorage.getItem('token')
    const { data } = await axios.get('http://localhost:8000/api/user/me', {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })

    Object.assign(user, data)
    Object.assign(editUser, {
      phone: data.phone,
      poids: data.poids,
      taille: data.taille,
      sexe: data.sexe,
    })
  } catch (error) {
    console.error('Erreur lors du chargement du profil :', error)
  }
}

const updateProfile = async () => {
  try {
    const token = localStorage.getItem('token')
    const { data } = await axios.put('http://localhost:8000/api/user/update', editUser, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })

    Object.assign(user, data)
    editMode.value = false
  } catch (error) {
    console.error('Erreur lors de la mise à jour du profil :', error)
  }
}

onMounted(fetchUser)
</script>

<style scoped>
.content {
  min-height: 100vh;
  background: #1a1a1a;
  color: #f1f1f1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.content h1 {
  font-size: 2.5rem;
  margin-bottom: 2rem;
  color: white;
  text-align: center;
}

.profile-container {
  display: flex;
  flex-direction: column;
  gap: 2rem;
  align-items: center;
  justify-content: center;
  width: 100%;
  max-width: 800px;
}

.profile,
.edit-form {
  background: #2c2c2c;
  color: #f1f1f1;
  padding: 2rem;
  border-radius: 16px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
  width: 100%;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.edit-btn {
  background: #3668E8;
  border: none;
  border-radius: 6px;
  padding: 5px 10px;
  cursor: pointer;
  color: #1a1a1a;
  font-weight: bold;
  transition: background 0.3s ease;
}
.edit-btn:hover {
  background: #3668E8;
}

.profile ul {
  list-style: none;
  padding: 0;
}
.profile li {
  margin-bottom: 0.75rem;
}

.edit-form h3 {
  margin-bottom: 1rem;
  color: white;
}

.edit-form label {
  display: block;
  margin-bottom: 1rem;
}

.edit-form input,
.edit-form select {
  width: 100%;
  padding: 0.5rem;
  border-radius: 8px;
  border: none;
  background: #444;
  color: white;
}

.save-btn {
  margin-top: 1rem;
  padding: 0.75rem 1.5rem;
  background-color: #3668E8;
  color: #1a1a1a;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.3s ease;
}
.save-btn:hover {
  background-color: #3668E8;
}
</style>

