import { createApp } from 'vue'
import DemoInvitationApp from './DemoInvitationApp.vue'

const el = document.getElementById('demo-app')

if (el) {
  const initialTemplate = el.dataset.template || null

  createApp(DemoInvitationApp, {
    initialTemplate,
  }).mount(el)
}
