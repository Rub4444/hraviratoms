<template>
  <section>
    <div class="mb-4">
      <h1 class="text-xl sm:text-2xl font-semibold">Invitation templates</h1>
      <p class="mt-1 text-xs sm:text-sm text-slate-600">
        Выберите стиль приглашения, чтобы создать свой уникальный онлайн-инвайт.
      </p>
    </div>

    <!-- LOADING -->
    <Card
      v-if="loading"
      class="rounded-2xl border bg-white p-4 text-sm text-slate-500 shadow-sm"
    >
      Loading templates...
    </Card>

    <!-- ПУСТО -->
    <Card
      v-else-if="!templates.length"
      class="rounded-2xl border bg-white p-4 text-sm text-slate-500 shadow-sm"
    >
      Пока нет ни одного шаблона.
    </Card>

    <!-- СПИСОК ШАБЛОНОВ -->
    <div v-else class="grid gap-4 md:grid-cols-2">
      <Card
        v-for="template in templates"
        :key="template.id"
        class="rounded-2xl border bg-white p-4 shadow-sm"
      >
        <div class="flex items-start justify-between gap-2">
          <div>
            <h2 class="text-lg font-medium text-slate-900">
              {{ template.name }}
            </h2>
            <p class="mt-1 text-sm text-slate-600">
              {{ template.description || '—' }}
            </p>
          </div>

          <!-- Если в ответе есть is_active — красиво показываем -->
          <div v-if="template.hasOwnProperty('is_active')" class="text-right text-[11px]">
            <Badge :type="template.is_active ? 'success' : 'gray'">
              {{ template.is_active ? 'Active' : 'Inactive' }}
            </Badge>
          </div>
        </div>

        <div class="mt-3 flex flex-wrap gap-2">
          <a
            :href="getDemoUrl(template)"
            target="_blank"
            class="rounded-full border border-slate-200 px-3 py-1 text-xs font-medium text-slate-800 hover:bg-slate-50"
          >
            Preview
          </a>

          <Button
            variant="primary"
            size="sm"
            @click="$router.push({ name: 'invitations.new', params: { templateKey: template.key } })"
          >
            Create invitation
          </Button>
        </div>
      </Card>
    </div>

    <p v-if="error" class="mt-3 text-xs text-red-500">
      {{ error }}
    </p>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { fetchTemplates } from '../services/templatesApi'

import Button from '../components/ui/Button.vue'
import Card from '../components/ui/Card.vue'
import Badge from '../components/ui/Badge.vue'

const templates = ref([])
const loading = ref(true)
const error = ref('')

const getDemoUrl = (template) => {
  return `${window.location.origin}/demo/${template.key}`
}

const loadData = async () => {
  loading.value = true
  error.value = ''

  const { templates: list, error: err } = await fetchTemplates()

  if (err) {
    error.value = err
    templates.value = []
  } else {
    templates.value = list
  }

  loading.value = false
}

onMounted(loadData)
</script>
