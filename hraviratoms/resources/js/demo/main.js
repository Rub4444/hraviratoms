import { createApp } from 'vue'
import DemoInvitationApp from './DemoInvitationApp.vue'

const el = document.getElementById('demo-app')

createApp(DemoInvitationApp, {
  initialTemplate: el.dataset.template || null
}).mount(el)
