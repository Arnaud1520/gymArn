<template>
  <div>
    <FullCalendar :options="calendarOptions" />

    <!-- Modal -->
    <div v-if="selectedSeance" class="modal-overlay">
      <div class="modal-content">
        <button
          @click="selectedSeance = null"
          class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-lg font-bold"
        >
          ✕
        </button>

        <h2 class="text-xl font-semibold mb-4">Détails de la séance</h2>
        <p><strong>Programme :</strong> {{ selectedSeance.programme.name }}</p>
        <p><strong>Description :</strong> {{ selectedSeance.programme.description }}</p>
        <p><strong>Date :</strong> {{ formatDateFr(selectedSeance.date) }}</p>

        <div class="mt-6 flex justify-between">
          <router-link
            :to="`/programme/${selectedSeance.programme.id}`"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          >
            Détail du programme
          </router-link>
          <button
            @click="inscrireASeance"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
          >
            S'inscrire
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
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

const selectedSeance = ref(null)
const calendarEvents = ref([]) // 🔁 Événements réactifs
const router = useRouter()

const calendarOptions = ref({
  plugins: [dayGridPlugin],
  initialView: 'dayGridMonth',
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth',
  },
  events: calendarEvents, // 🟢 Utilise une ref réactive ici
  eventContent: function (arg) {
    return { html: arg.event.title }
  },
  eventClick: function (info) {
    selectedSeance.value = info.event.extendedProps.seanceData
  },
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

const inscrireASeance = async () => {
  const token = localStorage.getItem('token')
  try {
    await axios.post(
      `http://localhost:8000/api/inscriptions`,
      { seance: `/api/seances/${selectedSeance.value.id}` },
      {
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/ld+json',
        },
      }
    )
    alert('Inscription réussie !')
    selectedSeance.value = null
  } catch (error) {
    console.error("Erreur lors de l'inscription :", error)
    alert("Une erreur est survenue lors de l'inscription.")
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

    console.log('Séances reçues :', response.data.member)

    // ✅ Filtrer uniquement les séances créées par l'utilisateur connecté
    const filteredSeances = response.data.member.filter(
      (seance) => seance.user.id === userId
    )

    // ✅ Mapper pour FullCalendar
    calendarEvents.value = filteredSeances.map((seance) => ({
      title: `<strong>• ${seance.programme.name}<br>• ${formatDateFr(seance.date)}</strong>`,
      start: seance.date,
      seanceData: seance,
    }))
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
</style>
