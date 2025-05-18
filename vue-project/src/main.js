import '@fullcalendar/daygrid/index.js'
import { createApp } from 'vue'
import App from './App.vue'
import './assets/global.css'
import router from './router'

const app = createApp(App)

app.use(router) // Ajoutez le routeur à l'application Vue
app.mount('#app')
