<template>
  <section>
    <h1 class="mb-4 text-2xl font-semibold">Invitation templates</h1>

    <p class="mb-4 text-sm text-slate-600">
      Выберите стиль приглашения, чтобы создать свой уникальный онлайн-инвайт.
    </p>

    <div v-if="loading" class="text-sm text-slate-500">
      Loading templates...
    </div>

    <div v-else class="grid gap-4 md:grid-cols-2">
      <div
        v-for="template in templates"
        :key="template.id"
        class="rounded-2xl border bg-white p-4 shadow-sm"
      >
        <h2 class="text-lg font-medium">{{ template.name }}</h2>
        <p class="mt-1 text-sm text-slate-600">{{ template.description }}</p>

        <div class="mt-3 flex gap-2">
             <a
                :href="getDemoUrl(template)"
                target="_blank"
                class="rounded-full bg-slate-900 px-3 py-1 text-xs font-medium text-white hover:bg-slate-800"
            >
                Preview
            </a>
            <button
                class="btn-primary"
                @click="$router.push({ name: 'invitations.new', params: { templateKey: template.key } })"
            >
                Create invitation
            </button>
        </div>

      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const templates = ref([]);
const loading = ref(true);

const getDemoUrl = (template) => {
  return `${window.location.origin}/demo/${template.key}`;
};

onMounted(async () => {
  try {
    const res = await fetch('/api/templates');
    templates.value = await res.json();
  } catch (e) {
    console.error('Failed to load templates', e);
  } finally {
    loading.value = false;
  }
});
</script>
