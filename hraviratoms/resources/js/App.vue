<template>
  <!-- Фулл-скрин лоадер при старте -->
  <FullScreenLoader v-if="loadingApp" />

  <div v-else class="min-h-screen bg-slate-50 text-slate-900">
    <!-- Шапка -->
    <header class="border-b bg-white/80 backdrop-blur">
      <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3">
        <div class="flex items-center gap-2">
          <div
            class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-600 text-white text-xs font-semibold"
          >
            LL
          </div>
          <div class="text-lg font-semibold tracking-wide">
            LoveLeaf Admin
          </div>
        </div>

        <nav class="flex items-center gap-4 text-sm text-slate-600">
          <!-- Ссылки в навигации -->
          <RouterLink
            v-if="isSuperAdmin"
            to="/"
            class="hover:text-emerald-700"
            active-class="text-emerald-700 font-medium"
          >
            Templates
          </RouterLink>

          <RouterLink
            to="/invitations"
            class="hover:text-emerald-700"
            active-class="text-emerald-700 font-medium"
          >
            Invitations
          </RouterLink>

          <RouterLink
            v-if="isSuperAdmin"
            to="/users"
            class="hover:text-emerald-700"
            active-class="text-emerald-700 font-medium"
          >
            Users
          </RouterLink>

          <button
            type="button"
            class="ml-4 rounded-full border border-slate-300 px-3 py-1 text-xs font-medium text-slate-700 hover:bg-slate-100"
            @click="logout"
          >
            Logout
          </button>
        </nav>
      </div>
    </header>

    <!-- Контент -->
    <main class="mx-auto max-w-5xl px-4 py-6">
      <RouterView />
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import FullScreenLoader from './components/FullScreenLoader.vue'

// Флаг прелоадера
const loadingApp = ref(true)

// Читаем флаг супер-админа из meta-тега, который ты ставишь в admin.blade.php
const isSuperAdmin = computed(() => {
  const meta = document.querySelector('meta[name="superadmin"]')
  return meta?.getAttribute('content') === '1'
})

// Настраиваем axios для CSRF и сессионных запросов
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  ?.getAttribute('content')

if (csrfToken) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken
}
axios.defaults.withCredentials = true

// Имитируем лёгкий прелоадер
onMounted(() => {
  setTimeout(() => {
    loadingApp.value = false
  }, 500)
})

// Logout через стандартный /logout
const logout = async () => {
  try {
    await axios.post('/logout')
  } catch (error) {
    console.error(error)
    alert('Error logging out')
  } finally {
    window.location.href = '/login'
  }
}
</script>
