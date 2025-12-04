<!-- resources/js/layouts/AdminLayout.vue -->
<template>
  <div class="min-h-screen bg-leaf-bg text-slate-900 flex flex-col">
    <!-- HEADER -->
    <header class="border-b bg-white/80 backdrop-blur">
      <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3">
        <div class="flex items-center gap-2">
          <div
            class="flex h-8 w-8 items-center justify-center rounded-full bg-leaf text-white text-xs font-logo"
          >
            LL
          </div>
          <div class="text-lg font-logo tracking-wide">
            LoveLeaf Admin
          </div>
        </div>

        <nav class="flex flex-wrap items-center gap-3 text-sm text-slate-600">
          <RouterLink
            v-if="isSuperAdmin"
            to="/"
            class="hover:text-leaf-deep"
          >
            Templates
          </RouterLink>
          <RouterLink
            to="/invitations"
            class="hover:text-leaf-deep"
          >
            My Invitations
          </RouterLink>
          <RouterLink
            v-if="isSuperAdmin"
            to="/users"
            class="hover:text-leaf-deep"
          >
            Users
          </RouterLink>

          <button
            @click="logout"
            class="rounded-full border border-slate-300 px-3 py-1 text-xs text-slate-700 hover:bg-slate-100"
          >
            Logout
          </button>
        </nav>
      </div>
    </header>

    <!-- CONTENT -->
    <main class="mx-auto max-w-5xl px-4 py-6 w-full flex-1">
      <slot />
    </main>

    <!-- FOOTER (можно потом кастомизировать или убрать) -->
    <footer class="border-t border-slate-200 py-3">
      <div class="mx-auto flex max-w-5xl items-center justify-between px-4 text-[11px] text-slate-500">
        <span>LoveLeaf Admin</span>
        <span>Сделано с 🌿</span>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { isSuperAdmin as isSuperAdminHelper, getCsrfToken } from '../utils/meta'

const csrfToken = getCsrfToken()
const isSuperAdmin = isSuperAdminHelper()

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

    // после logout отправим юзера на /login
    window.location.href = '/login'
  } catch (e) {
    console.error(e)
    alert('Error logging out')
  }
}
</script>
