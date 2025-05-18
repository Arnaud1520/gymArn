<template>
    <div>
      <FullCalendar :options="calendarOptions" />
    </div>
  </template>
  
  <script setup>
  import axios from 'axios'
import { onMounted, ref } from 'vue'
  
  import dayGridPlugin from '@fullcalendar/daygrid'
import FullCalendar from '@fullcalendar/vue3'
  
  const seances = ref([])
  
  const calendarOptions = ref({
    plugins: [dayGridPlugin],
    initialView: 'dayGridMonth',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth',
    },
    events: [],
    eventContent: function(arg) {
      return { html: arg.event.title }
    }
  })
  
  const formatDateFr = (isoDate) => {
    const options = { hour: '2-digit', minute: '2-digit', hour12: false }
    return new Date(isoDate).toLocaleTimeString('fr-FR', options)
  }
  
  onMounted(async () => {
    const token = localStorage.getItem('token')
  
    try {
      const response = await axios.get('http://localhost:8000/api/seances', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
  
      seances.value = response.data.member.map(seance => ({
        title: `<strong>• ${seance.user.name}<br>• ${seance.programme.name}<br>• ${formatDateFr(seance.date)}</strong>`,
        start: seance.date,
      }))
  
      calendarOptions.value.events = seances.value
    } catch (error) {
      console.error('Erreur lors du chargement des séances :', error)
    }
  })
  </script>
  