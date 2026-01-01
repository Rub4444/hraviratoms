<template>
  <section class="grid gap-6 md:grid-cols-[420px_1fr]">

    <!-- LEFT: controls -->
    <div class="space-y-4">

      <h1 class="text-2xl font-semibold">
        Online invitation demo
      </h1>

      <!-- TEMPLATE PICKER -->
      <div class="rounded-xl border bg-white p-4">
        <label class="block text-xs font-medium mb-1">
          Choose template
        </label>

        <select
          v-model="selectedTemplate"
          class="w-full rounded-lg border px-3 py-2 text-sm"
        >
          <option :value="null">Select template</option>

          <option
            v-for="tpl in templates"
            :key="tpl.key"
            :value="tpl"
          >
            {{ tpl.name }}
          </option>
        </select>
      </div>

      <!-- FEATURES -->
      <div v-if="selectedTemplate" class="rounded-xl border bg-white p-4">
        <h3 class="text-xs font-semibold mb-2">
          Features
        </h3>

        <div
          v-for="(enabled, key) in invitation.data.features"
          :key="key"
          class="flex items-center gap-2 text-sm"
        >
          <input
            type="checkbox"
            v-model="invitation.data.features[key]"
          >
          <span>{{ key }}</span>
        </div>
      </div>

      <!-- ACTION -->
      <div class="pt-2">
        <button
          class="rounded-full bg-leaf px-5 py-2 text-sm text-white"
          :disabled="!selectedTemplate"
          @click="goToRequest"
        >
          Continue
        </button>
      </div>

    </div>

    <!-- RIGHT: PREVIEW -->
    <div>
      <h2 class="mb-2 text-sm font-medium">
        Live preview
      </h2>

      <iframe
        ref="previewFrame"
        class="h-[720px] w-full rounded-xl border bg-white"
      />
    </div>

  </section>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { fakeInvitation } from './fakeData'
import { debounce } from '@/utils/debounce'

/* -----------------------
   PROPS
----------------------- */
const props = defineProps({
  initialTemplate: {
    type: String,
    default: null,
  },
})

/* -----------------------
   STATE
----------------------- */
const templates = ref([])
const selectedTemplate = ref(null)
const invitation = ref(JSON.parse(JSON.stringify(fakeInvitation)))
const previewFrame = ref(null)

/* -----------------------
   LOAD TEMPLATES
----------------------- */
const loadTemplates = async () => {
  const res = await fetch('/api/templates')
  if (!res.ok) return
  templates.value = await res.json()

  if (props.initialTemplate) {
    selectedTemplate.value =
      templates.value.find(t => t.key === props.initialTemplate) || null
  }
}

/* -----------------------
   PREVIEW
----------------------- */
const updatePreview = async () => {
  if (!selectedTemplate.value || !previewFrame.value) return

  const payload = {
    template_key: selectedTemplate.value.key,
    invitation: invitation.value,
  }

  const html = `
    <form method="POST" action="/preview/invitation">
      <input type="hidden" name="_token" value="${csrf}">
      <input type="hidden" name="template_key" value="${payload.template_key}">
      <input type="hidden" name="invitation" value='${JSON.stringify(payload.invitation)}'>
    </form>
    <script>document.forms[0].submit()<\/script>
  `

  previewFrame.value.srcdoc = html
}

const updatePreviewDebounced = debounce(updatePreview, 400)

/* -----------------------
   WATCHERS
----------------------- */
watch(selectedTemplate, () => {
  updatePreviewDebounced()
})

watch(invitation, () => {
  updatePreviewDebounced()
}, { deep: true })

/* -----------------------
   NAVIGATION
----------------------- */
const goToRequest = () => {
  const params = new URLSearchParams({
    template: selectedTemplate.value.key,
  })

  window.location.href = `/request-invitation?${params.toString()}`
}

/* -----------------------
   INIT
----------------------- */
const csrf =
  document.querySelector('meta[name="csrf-token"]')?.content || ''

onMounted(async () => {
  await loadTemplates()
  updatePreview()
})
</script>
