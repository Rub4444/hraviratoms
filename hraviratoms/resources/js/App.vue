<template>
    <FullScreenLoader v-if="loadingApp" />

    <div v-else class="min-h-screen bg-leaf-bg text-slate-900">
    <header class="border-b bg-white/80 backdrop-blur">
      <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3">
        <div class="flex items-center gap-2">
          <div class="flex h-8 w-8 items-center justify-center rounded-full bg-leaf text-white text-xs font-logo">
            LL
          </div>
          <div class="text-lg font-logo tracking-wide">
            LoveLeaf Admin
          </div>
        </div>
        <nav class="flex gap-4 text-sm text-slate-600">
          <RouterLink  v-if="isSuperAdmin" to="/" class="hover:text-leaf-deep">Templates</RouterLink>
          <RouterLink to="/invitations" class="hover:text-leaf-deep">My Invitations</RouterLink>
          <RouterLink  v-if="isSuperAdmin" to="/users" class="hover:text-leaf-deep">Users</RouterLink>
           <button
                @click="logout"
                class="rounded-full border border-slate-300 px-3 py-1 text-xs text-slate-700 hover:bg-slate-100"
            >
                Logout
            </button>
        </nav>
      </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-6">
      <RouterView />
    </main>
  </div>
</template>

<script setup>
    const csrfToken =
    document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const isSuperAdmin =
    document.querySelector('meta[name="superadmin"]')?.getAttribute('content') === '1';

    const logout = async () => {
        try {
            const res = await fetch('/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            });

            if (!res.ok) {
            throw new Error('Logout failed');
            }

            // Перезагрузить страницу → уйдём на /login
            window.location.reload();
        } catch (e) {
            console.error(e);
            alert('Error logging out');
        }
    };

    import { ref, onMounted } from 'vue'
    import FullScreenLoader from './components/FullScreenLoader.vue'

    const loadingApp = ref(true)

    onMounted(() => {
    setTimeout(() => {
        loadingApp.value = false
    }, 700) // чуточку стиля :)
    })
</script>
