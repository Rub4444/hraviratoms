<template>
  <!-- PRICE BREAKDOWN -->
  <div
    v-if="selectedTemplate"
    class="mb-4 rounded-xl bg-slate-50 px-4 py-3 text-sm"
  >
    <!-- Base -->
    <div class="flex justify-between">
      <span>Base price</span>
      <span>{{ selectedTemplate.base_price }} ֏</span>
    </div>

    <!-- Features -->
    <template
      v-for="(enabled, key) in invitation.data.features"
      :key="key"
    >
      <div
        v-if="enabled && pricing.features?.[key]"
        class="flex justify-between text-slate-600"
      >
        <span>{{ pricing.features[key].label }}</span>
        <span>+ {{ pricing.features[key].price }} ֏</span>
      </div>
    </template>

    <!-- Total -->
    <div class="mt-2 border-t pt-2 flex justify-between font-semibold">
      <span>Total</span>

      <span
        v-if="priceLoading"
        class="text-slate-400 animate-pulse"
      >
        calculating…
      </span>

      <span v-else>
        {{ totalPrice }} ֏
      </span>
    </div>
  </div>


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
          <span>{{ featureLabels[key] }}</span>
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
import { debounce } from '@/utils/debounce'
import { demoInvitation } from './fakeInvitation'

const pricing = ref({ features: {} })
const priceLoading = ref(false)

const loadPricing = async () => {
  const res = await fetch('/api/invitation-pricing')
  if (!res.ok) return
  pricing.value = await res.json()
}

const featureLabels = {
  program: 'Program',
  gallery: 'Gallery',
  rsvp: 'RSVP',
}

const totalPrice = ref(0)

const recalcPrice = async () => {
  if (!selectedTemplate.value) return

  priceLoading.value = true

  try {
    const res = await fetch('/api/demo/calculate-price', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrf,
      },
      body: JSON.stringify({
        template_key: selectedTemplate.value.key,
        features: invitation.value.data.features,
      }),
    })

    if (!res.ok) return

    const data = await res.json()
    totalPrice.value = data.price
  } finally {
    priceLoading.value = false
  }
}


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
const invitation = ref(JSON.parse(JSON.stringify(demoInvitation)))
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
// const updatePreview = async () => {
//   if (!selectedTemplate.value || !previewFrame.value) return

//   const payload = {
//     template_key: selectedTemplate.value.key,
//     invitation: invitation.value,
//   }

//   const html = `
//     <form method="POST" action="/preview/invitation">
//       <input type="hidden" name="_token" value="${csrf}">
//       <input type="hidden" name="template_key" value="${payload.template_key}">
//       <input type="hidden" name="invitation" value='${JSON.stringify(payload.invitation)}'>
//     </form>
//     <script>document.forms[0].submit()<\/script>
//   `

//   previewFrame.value.srcdoc = html
// }
const updatePreview = () => {
  if (!selectedTemplate.value || !previewFrame.value) return

  const params = new URLSearchParams({
    invitation: JSON.stringify(invitation.value),
  })

  previewFrame.value.src =
    `/demo/preview/${selectedTemplate.value.key}?${params.toString()}`
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

watch(
  () => [selectedTemplate.value, invitation.value.data.features],
  recalcPrice,
  { deep: true }
),

watch(selectedTemplate, (tpl) => {
  if (!tpl || !tpl.config) return

  const config = tpl.config

  // 1️⃣ Дефолтные фичи из шаблона
  invitation.value.data.features = {
    ...config.features,
  }

  // 2️⃣ Дизайн (цвета + шрифты)
  invitation.value.data.design = {
    ...config.design,
    theme: config.theme,
  }
})

/* -----------------------
   NAVIGATION
----------------------- */
const goToRequest = () => {
  if (!selectedTemplate.value) return

  const payload = {
    template: selectedTemplate.value.key,
    invitation: invitation.value,
  }

  const form = document.createElement('form')
  form.method = 'POST'
  form.action = '/demo/to-request'

  const token =
    document.querySelector('meta[name="csrf-token"]')?.content || ''

  form.innerHTML = `
    <input type="hidden" name="_token" value="${token}">
    <input type="hidden" name="demo_payload"
           value='${JSON.stringify(payload)}'>
  `

  document.body.appendChild(form)
  form.submit()
}


/* -----------------------
   INIT
----------------------- */
const csrf =
  document.querySelector('meta[name="csrf-token"]')?.content || ''

onMounted(async () => {
  await loadPricing()
  await loadTemplates()
  updatePreview()
})
</script>
