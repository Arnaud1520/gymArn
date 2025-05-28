<template>
  <main class="content">
    <h1>Bienvenue, {{ user.name }} 👋</h1>

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
  padding: 40px;
  background: #f7f8fc;
}

.content h1 {
  font-size: 2rem;
  margin-bottom: 30px;
}

.profile-container {
  display: flex;
  gap: 30px;
  flex-wrap: wrap;
}

.profile {
  background: white;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  max-width: 400px;
  flex: 1;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.edit-btn {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 18px;
}

.profile ul {
  list-style: none;
  padding: 0;
}

.profile li {
  margin-bottom: 10px;
}

.edit-form {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  max-width: 400px;
  flex: 1;
}

.edit-form h3 {
  margin-bottom: 15px;
}

.edit-form label {
  display: block;
  margin-bottom: 10px;
}

.edit-form input,
.edit-form select {
  width: 100%;
  padding: 8px;
  margin-top: 4px;
  border-radius: 6px;
  border: 1px solid #ccc;
}

.save-btn {
  margin-top: 10px;
  padding: 10px 15px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
</style>
