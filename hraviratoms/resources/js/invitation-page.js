import { createApp } from "vue"
import InvitationPublicPage from "./components/InvitationPublicPage.vue"
import './lightbox'

const el = document.getElementById("invitation-app")

if (el) {
    createApp(InvitationPublicPage).mount(el)
}
