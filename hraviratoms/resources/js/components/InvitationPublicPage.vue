<template>
  <div class="min-h-screen bg-[#f4f1ea] flex items-center justify-center p-6">
    <div class="bg-white/90 shadow-xl rounded-3xl p-8 text-center max-w-xl w-full">
      <p class="text-xs tracking-[0.3em] uppercase text-slate-500 mb-4">
        Wedding Invitation
      </p>

      <h1 class="text-4xl font-semibold mb-2">
        {{ bride }} &amp; {{ groom }}
      </h1>

      <p v-if="date" class="text-slate-600 mb-4">
        {{ date }} <span v-if="time">• {{ time }}</span>
      </p>

      <div v-if="venueName || venueAddress" class="text-sm mb-6">
        <p v-if="venueName" class="font-medium">{{ venueName }}</p>
        <p v-if="venueAddress" class="text-slate-600">{{ venueAddress }}</p>
      </div>

      <button
        @click="openRSVP"
        class="px-6 py-3 bg-amber-600 text-white rounded-xl shadow hover:bg-amber-700 transition"
      >
        RSVP
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

// Данные, переданные из Blade через data-атрибуты:
const el = document.getElementById('invitation-app')

const bride = ref(el.dataset.bride)
const groom = ref(el.dataset.groom)
const date = ref(el.dataset.date)
const time = ref(el.dataset.time)
const venueName = ref(el.dataset.venueName)
const venueAddress = ref(el.dataset.venueAddress)

function openRSVP() {
  alert('Здесь будет модалка RSVP 🙂')
}
</script>

<style scoped>
</style>
