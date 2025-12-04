import { createApp } from "vue"
// import '../css/app.css';
import InvitationPublicPage from "./components/InvitationPublicPage.vue"

const el = document.getElementById("invitation-app")

if (el) {
    createApp(InvitationPublicPage).mount(el)
}
