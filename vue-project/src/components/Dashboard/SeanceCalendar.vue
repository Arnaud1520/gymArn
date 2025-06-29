<template>
  <div class="calendar-wrapper">
    <FullCalendar :options="calendarOptions" class="custom-calendar" />

    <!-- Modal -->
    <div v-if="selectedSeance" class="modal-overlay">
      <div class="modal-content">
        <button
          @click="selectedSeance = null"
          class="close-button"
        >
          ✕
        </button>

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
.modal-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content {
  background-color: white;
  padding: 2rem;
  border-radius: 8px;
  width: 90%;
  max-width: 500px;
  position: relative;
  z-index: 1001;
}

.calendar-wrapper {
  max-width: 100%; /* au lieu de 800px */
  width: 100%;
  margin: 2rem auto;
  padding: 2rem;
  background: #f9f9f9;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.custom-calendar :deep(.fc) {
  font-size: 14px;
}

.custom-calendar :deep(.fc-toolbar-title) {
  font-size: 1.25rem;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 50;
}

.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  width: 90%;
  max-width: 500px;
  position: relative;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.modal-title {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.close-button {
  position: absolute;
  top: 12px;
  right: 12px;
  background: none;
  border: none;
  font-size: 1.25rem;
  color: #888;
  cursor: pointer;
}

.close-button:hover {
  color: #e53e3e;
}

.modal-actions {
  margin-top: 2rem;
  display: flex;
  justify-content: flex-end;
}

.btn-link {
  background-color: #2563eb;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  text-decoration: none;
  transition: background 0.2s ease;
}

.btn-link:hover {
  background-color: #1e40af;
}

</style>
