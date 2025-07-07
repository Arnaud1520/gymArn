<template>
  <div class="calendar-wrapper">
    <FullCalendar :options="calendarOptions" class="custom-calendar" />

    <!-- Modal -->
    <div v-if="selectedSeance" class="modal-overlay">
      <div class="modal-content">
        <button @click="selectedSeance = null" class="close-button">✕</button>

        <h2 class="modal-title">Détails de la séance</h2>
        <p><strong>Programme :</strong> {{ selectedSeance.programme.name }}</p>
        <p><strong>Date :</strong> {{ formatDateFr(selectedSeance.date) }}</p>

        <div class="modal-actions">
          <router-link
            :to="`/programme/${selectedSeance.programme.id}`"
            class="btn-link"
          >
            Détail du programme
          </router-link>

          <button @click="deleteSeance" class="btn-delete">
            🗑 Supprimer
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import dayGridPlugin from '@fullcalendar/daygrid'
import FullCalendar from '@fullcalendar/vue3'
import axios from 'axios'
import { onMounted, ref, watchEffect } from 'vue'
import { useRouter } from 'vue-router'

const selectedSeance = ref(null)
const calendarEvents = ref([])
const router = useRouter()

const calendarOptions = ref({
  plugins: [dayGridPlugin],
  initialView: 'dayGridMonth',
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth',
  },
  events: calendarEvents.value,
  eventContent: function (arg) {
    return { html: arg.event.title }
  },
  eventClick: function (info) {
    selectedSeance.value = info.event.extendedProps.seanceData
  },
})

watchEffect(() => {
  calendarOptions.value.events = calendarEvents.value
})

const formatDateFr = (isoDate) => {
  return new Date(isoDate).toLocaleString('fr-FR', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
  })
}

const deleteSeance = async () => {
  if (!selectedSeance.value) return

  const confirmDelete = confirm("Es-tu sûr de vouloir supprimer cette séance ?")
  if (!confirmDelete) return

  const token = localStorage.getItem('token')

  try {
    await axios.delete(`http://localhost:8000/api/seances/${selectedSeance.value.id}`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })

    // Supprime de la liste d'événements
    calendarEvents.value = calendarEvents.value.filter(
      (event) => event.seanceData.id !== selectedSeance.value.id
    )

    selectedSeance.value = null // Ferme la modale
  } catch (error) {
    console.error("Erreur lors de la suppression de la séance :", error)
    alert("La suppression a échoué.")
  }
}

onMounted(async () => {
  const token = localStorage.getItem('token')

  try {
    const me = await axios.get('http://localhost:8000/api/user/me', {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })
    const userId = me.data.id

    const response = await axios.get('http://localhost:8000/api/seances', {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })

    const filteredSeances = response.data.member.filter(
      (seance) => seance.user['@id'] === `/api/users/${userId}`
    )

    console.log("🎯 Séances filtrées :", filteredSeances)

    calendarEvents.value = filteredSeances.map((seance) => ({
      title: `• ${seance.programme.name}`,
      start: seance.date,
      seanceData: seance,
    }))

    console.log("✅ Événements à injecter dans FullCalendar :", calendarEvents.value)
  } catch (error) {
    console.error('Erreur lors du chargement des séances :', error)
  }
})
</script>

<style scoped>
.calendar-wrapper {
  background-color: #1e1e1e;
  color: white;
  padding: 2rem;
  margin: 2rem auto;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(255, 255, 255, 0.05);
  max-width: 100%;
  width: 100%;
}

.custom-calendar :deep(.fc) {
  font-family: 'Segoe UI', sans-serif;
  background-color: #1e1e1e;
  color: white;
  border: none;
}

.custom-calendar :deep(.fc-toolbar-title) {
  font-size: 1.5rem;
  font-weight: bold;
  color: white;
}

.custom-calendar :deep(.fc-button) {
  background-color: #3668E8;
  border: none;
  color: white;
  font-weight: bold;
  padding: 0.3rem 0.8rem;
  border-radius: 6px;
}

.custom-calendar :deep(.fc-button:hover),
.custom-calendar :deep(.fc-button:focus) {
  background-color: #3668E8;
}

.custom-calendar :deep(.fc-daygrid-day-number) {
  color: #aaa;
}

.custom-calendar :deep(.fc-event) {
  background-color: #3668E8;
  color: white;
  border: none;
  padding: 2px 4px;
  font-weight: 600;
  border-radius: 6px;
}

.modal-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.7);
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content {
  background-color: #121212;
  color: white;
  padding: 2rem;
  border-radius: 12px;
  width: 90%;
  max-width: 500px;
  position: relative;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.8);
}

.modal-title {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 1rem;
  color: #3668E8;
}

.close-button {
  position: absolute;
  top: 12px;
  right: 12px;
  background: none;
  border: none;
  font-size: 1.25rem;
  color: #aaa;
  cursor: pointer;
}

.close-button:hover {
  color: #3668E8;
}

.modal-actions {
  margin-top: 2rem;
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}

.btn-link {
  background-color: #3668E8;
  color: black;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: bold;
  transition: background 0.2s ease;
}

.btn-link:hover {
  background-color: #2e5ed1;
}

.btn-delete {
  background-color: #d9534f;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
  border: none;
  transition: background 0.2s ease;
}

.btn-delete:hover {
  background-color: #c9302c;
}
</style>
