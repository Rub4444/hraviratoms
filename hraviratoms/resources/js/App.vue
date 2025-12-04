<template>
  <FullScreenLoader v-if="loadingApp" />

  <div v-else class="min-h-screen bg-leaf-bg text-slate-900">
    <AdminHeader
      :is-super-admin="isSuperAdmin"
      @logout="logout"
    />

    <main class="mx-auto max-w-5xl px-4 py-6">
      <RouterView />
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterView } from 'vue-router'
import FullScreenLoader from './components/FullScreenLoader.vue'
import AdminHeader from './components/AdminHeader.vue'

// meta-теги из Blade
const csrfToken =
  document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''

const isSuperAdmin =
  document.querySelector('meta[name="superadmin"]')?.getAttribute('content') === '1'

// состояние загрузки приложения
const loadingApp = ref(true)

onMounted(() => {
  setTimeout(() => {
    loadingApp.value = false
  }, 700)
})

// logout
const logout = async () => {
  try {
    const res = await fetch('/logout', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        Accept: 'application/json',
      },
    })

    if (!res.ok) {
      throw new Error('Logout failed')
    }

    window.location.reload()
  } catch (e) {
    console.error(e)
    alert('Error logging out')
  }
}
</script>
