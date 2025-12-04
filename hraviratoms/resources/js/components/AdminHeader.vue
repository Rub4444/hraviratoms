<template>
  <header class="border-b bg-white/80 backdrop-blur">
    <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3">
      <!-- Лого -->
      <div class="flex items-center gap-2">
        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-leaf text-white text-xs font-logo">
          LL
        </div>
        <div class="text-lg font-logo tracking-wide">
          LoveLeaf Admin
        </div>
      </div>

      <!-- Навигация -->
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
          @click="$emit('logout')"
          class="rounded-full border border-slate-300 px-3 py-1 text-xs text-slate-700 hover:bg-slate-100"
        >
          Logout
        </button>
      </nav>
    </div>
  </header>
</template>

<script setup>
import { RouterLink } from 'vue-router'

defineProps({
  isSuperAdmin: {
    type: Boolean,
    default: false,
  },
})

defineEmits(['logout'])
</script>
