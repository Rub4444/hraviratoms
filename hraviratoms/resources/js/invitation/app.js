import { createApp } from 'vue'
import InvitationEditorCore from './InvitationEditorCore.vue'

const el = document.getElementById('invitation-app')

if (el) {
  createApp(InvitationEditorCore, {
    mode: 'demo',
    templateKey: el.dataset.templateKey || null,
  }).mount(el)
}
