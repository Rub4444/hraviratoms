<template>
  <section>
    <h1 class="mb-2 text-2xl font-semibold">
      {{ isEdit ? 'Edit invitation' : 'Create invitation' }}
    </h1>
    <p class="mb-5 text-sm text-slate-600">
      {{ templateTitle }}
    </p>

    <div class="mt-4 rounded-xl bg-slate-50 px-4 py-3 text-sm">
      <div class="flex justify-between">
        <span>Base price</span>
        <span>{{ selectedTemplate?.base_price }} ֏</span>
      </div>
      <template v-for="(enabled, key) in form.data.features" :key="key">
        <div
            v-if="enabled && pricing.features?.[key]"
            class="flex justify-between text-slate-600"
        >
            <span>{{ pricing.features[key].label }}</span>
            <span>+ {{ pricing.features[key].price }} ֏</span>
        </div>
      </template>
      <div class="mt-2 border-t pt-2 flex justify-between font-semibold">
        <span>Total</span>
        <span v-if="priceLoading" class="text-slate-400 animate-pulse">
          calculating…
        </span>

        <span v-else>
          {{ totalPrice }} ֏
        </span>
      </div>
    </div>

    <button
    v-if="isAdmin"
    type="button"
    class="btn-secondary text-xs"
    :disabled="loadingUser || !isEdit"
    @click="handleCreateUser"
    >
      {{ loadingUser ? 'Creating user...' : 'Add user' }}
    </button>

    <p v-if="errorUser" class="mt-1 text-[11px] text-red-500">
      {{ errorUser }}
    </p>

    <div class="grid gap-6 md:grid-cols-[minmax(0,1.1fr)_minmax(0,1fr)]">
      <form class="space-y-4" @submit.prevent="handleSubmit">
        <div class="grid gap-4 md:grid-cols-2">
            <div
            v-if="isAdmin & users.length"
            class="col-span-2"
            >
              <label class="block text-[11px] font-medium text-slate-600 mb-1">
                  Кто может видеть статистику (владелец)
              </label>
              <select
                  v-model="form.user_id"
                  class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-leaf focus:ring-leaf"
              >
                  <option :value="null">
                  Только супер-админ (не привязано к аккаунту)
                  </option>
                  <option
                  v-for="u in users"
                  :key="u.id"
                  :value="u.id"
                  >
                  {{ u.name }} ({{ u.email }}) <span v-if="u.is_superadmin">— Superadmin</span>
                  </option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-medium text-slate-700 mb-1">
                Template
              </label>

              <select
                v-model="selectedTemplate"
                class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm"
              >
                <option :value="null">Select template</option>

                <option
                  v-for="tpl in templates"
                  :key="tpl.id"
                  :value="tpl"
                >
                  {{ tpl.name }} — {{ tpl.base_price }} ֏
                </option>
              </select>

              <p v-if="templatesError" class="mt-1 text-xs text-red-500">
                {{ templatesError }}
              </p>
            </div>
            <div v-if="pricing.features">
                <h3 class="mt-4 mb-2 text-xs font-semibold text-slate-700">
                    Features
                </h3>

                <div
                    v-for="(feature, key) in pricing.features"
                    :key="key"
                    class="flex items-start gap-3 rounded-xl border px-3 py-2 text-sm"
                >
                    <input
                    type="checkbox"
                    v-model="form.data.features[key]"
                    class="mt-1"
                    />

                    <div class="flex-1">
                    <div class="font-medium">
                        {{ feature.label }}
                        <span class="text-xs text-slate-500">
                        (+{{ feature.price }} ֏)
                        </span>
                    </div>

                    <div class="text-[11px] text-slate-500">
                        {{ feature.description }}
                    </div>
                    </div>
                </div>
            </div>

            <div v-if="form.data.design" class="mt-4">
              <h3 class="mb-2 text-xs font-semibold text-slate-700">
                Design
              </h3>

              <div class="grid grid-cols-3 gap-3">
                <div v-if="form.data.design?.colors" class="mt-4">
                  <label class="block text-[11px] text-slate-600">Primary</label>
                  <input type="color" v-model="form.data.design.colors.primary" />
                </div>

                <div>
                  <label class="block text-[11px] text-slate-600">Accent</label>
                  <input type="color" v-model="form.data.design.colors.accent" />
                </div>

                <div>
                  <label class="block text-[11px] text-slate-600">Background</label>
                  <input type="color" v-model="form.data.design.colors.background" />
                </div>
              </div>
            </div>
            <!-- Gallery -->
            <div v-if="form.data.features?.gallery" class="mt-4">
                <h3 class="mb-2 text-xs font-semibold text-slate-700">
                    Gallery
                </h3>

                <div v-if="isAdmin" class="rounded-xl border border-dashed border-slate-300 p-4">
                    <input
                    type="file"
                    multiple
                    accept="image/*"
                    :disabled="!isEdit"
                    class="block w-full text-xs"
                    @change="handleGalleryUpload"
                    />
                    <p v-if="!isEdit" class="mt-2 text-[11px] text-amber-600">
                        Сначала сохраните приглашение
                    </p>

                    <p class="mt-2 text-[11px] text-slate-500">
                        Можно загрузить несколько изображений (JPG, PNG)
                    </p>
                </div>


                <div v-if="gallery.length" class="mt-3 grid grid-cols-3 gap-3">
                    <div
                    v-for="img in gallery"
                    :key="img.id"
                    class="relative overflow-hidden rounded-lg border"
                    >
                    <img :src="img.url" class="h-24 w-full object-cover" />
                    <button
                        type="button"
                        class="absolute top-1 right-1 rounded-full bg-black/60 px-1.5 text-[10px] text-white"
                        @click="removeGalleryItem(img.id)"
                    >
                        ✕
                    </button>
                    </div>
                </div>
            </div>

            <div>
              <label class="block text-xs font-medium text-slate-700">Bride name</label>
              <input
                v-model="form.bride_name"
                type="text"
                class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-rose-400 focus:ring-rose-400"
                required
              />
            </div>
            <div>
              <label class="block text-xs font-medium text-slate-700">Groom name</label>
              <input
                v-model="form.groom_name"
                type="text"
                class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-rose-400 focus:ring-rose-400"
                required
              />
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
          <div>
            <label class="block text-xs font-medium text-slate-700">Date</label>
            <input
              v-model="form.date"
              type="date"
              class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-rose-400 focus:ring-rose-400"
            />
          </div>
          <div>
            <label class="block text-xs font-medium text-slate-700">Time</label>
            <input
              v-model="form.time"
              type="text"
              placeholder="18:00"
              class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-rose-400 focus:ring-rose-400"
            />
          </div>
        </div>

        <div>
          <label class="block text-xs font-medium text-slate-700">Venue name</label>
          <input
            v-model="form.venue_name"
            type="text"
            class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-rose-400 focus:ring-rose-400"
          />
        </div>

        <div>
          <label class="block text-xs font-medium text-slate-700">Venue address</label>
          <input
            v-model="form.venue_address"
            type="text"
            class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-rose-400 focus:ring-rose-400"
          />
        </div>

        <div>
          <label class="block text-xs font-medium text-slate-700">Dress code</label>
          <input
            v-model="form.dress_code"
            type="text"
            placeholder="Elegant / Festive"
            class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-rose-400 focus:ring-rose-400"
          />
        </div>

        <!-- Программа -->
        <div v-if="form.data.features?.program">
          <label class="block text-xs font-medium text-slate-700 mb-1">Program</label>
          <div
            v-for="(item, index) in program"
            :key="index"
            class="mb-2 flex gap-2"
          >
            <input
              v-model="item.time"
              type="text"
              placeholder="18:00"
              class="block w-24 rounded-xl border-slate-200 text-xs shadow-sm focus:border-rose-400 focus:ring-rose-400"
            />
            <input
              v-model="item.label"
              type="text"
              placeholder="Welcome drink"
              class="block flex-1 rounded-xl border-slate-200 text-xs shadow-sm focus:border-rose-400 focus:ring-rose-400"
            />
          </div>
          <button
            type="button"
            class="btn-primary"
            @click="addProgramItem"
          >
            + Add program item
          </button>
        </div>
        <div v-if="isAdmin">
          <label class="block text-xs font-medium text-slate-700 mb-1">
            Invitation status
          </label>

          <select
            v-model="form.status"
            class="block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm
                  shadow-sm focus:border-leaf focus:ring-leaf"
          >
            <option value="pending">Pending</option>
            <option value="published">Published</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>

        <div class="flex items-center justify-between pt-2" v-if="isAdmin">
          <button
            type="submit"
            class="rounded-full bg-rose-500 px-4 py-1.5 text-xs font-medium text-white hover:bg-rose-600"
            :disabled="submitting"
          >
            {{ submitting ? 'Saving...' : (isEdit ? 'Save changes' : 'Create invitation') }}
          </button>
        </div>


        <p v-if="error" class="text-xs text-red-500">
          {{ error }}
        </p>
      </form>

    <!-- LIVE PREVIEW -->
    <div class="hidden md:block">
        <h2 class="mb-3 text-sm font-medium text-slate-700">
            Live preview
        </h2>

        <iframe
            ref="previewFrame"
            class="w-full h-[700px] rounded-2xl border bg-white"
        ></iframe>
    </div>

    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'
import { fetchTemplates } from '@/services/templatesApi'
import { debounce } from '@/utils/debounce'

import { getCurrentInstance } from 'vue'

let route = null
let router = null

const instance = getCurrentInstance()

if (instance?.appContext.config.globalProperties.$route) {
  route = instance.appContext.config.globalProperties.$route
  router = instance.appContext.config.globalProperties.$router
}

const priceLoading = ref(false)

const recalcPrice = async () => {
  if (!selectedTemplate.value) return

  priceLoading.value = true

  try {
    const url =
      props.mode === 'demo'
        ? '/api/demo/calculate-price'
        : '/api/invitations/calculate-price'

    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        ...(props.mode !== 'demo'
          ? { 'X-CSRF-TOKEN': csrfToken }
          : {}),
        Accept: 'application/json',
      },
      body: JSON.stringify({
        template_id: selectedTemplate.value.id,
        features: form.value.data.features,
      }),
    })

    if (!res.ok) return

    const data = await res.json()
    totalPrice.value = data.price
  } finally {
    priceLoading.value = false
  }
}


const recalcPriceDebounced = debounce(recalcPrice, 300)

const priceSignature = computed(() => {
  return JSON.stringify({
    template_id: selectedTemplate.value?.id,
    features: form.value.data.features,
  })
})

const previewSignature = computed(() => {
  return JSON.stringify({
    template: selectedTemplate.value?.key,
    bride: form.value.bride_name,
    groom: form.value.groom_name,
    date: form.value.date,
    time: form.value.time,
    venue: form.value.venue_name,
    address: form.value.venue_address,
    dress: form.value.dress_code,
    features: form.value.data.features,
    design: form.value.data.design,
    program: program.value,
    gallery: gallery.value.map(g => g.path ?? g.url),
  })
})

/* =======================
   ROUTER / PROPS
======================= */
const previewFrame = ref(null)

const props = defineProps({
  mode: {
    type: String,
    default: 'admin', // 'admin' | 'demo'
  },
  id: {
    type: String,
    default: null,
  },
  templateKey: {
    type: String,
    default: null,
  },
})

const isAdmin = computed(() => props.mode === 'admin')
const isDemo = computed(() => props.mode === 'demo')

const isEdit = computed(() => !!props.id)

/* =======================
   STATE
======================= */
const templates = ref([])
const templatesError = ref('')
const selectedTemplate = ref(null)

const pricing = ref({ features: {} })

const users = ref([])
const loadingUsers = ref(false)

const loadingUser = ref(false)
const errorUser = ref(null)

const submitting = ref(false)
const error = ref('')

/* =======================
   GALLERY
======================= */
const gallery = ref([])

const removeGalleryItem = async (imageId) => {
  if (!props.id) return

  try {
    const res = await fetch(
      `/api/invitations/${props.id}/gallery/${imageId}`,
      {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          Accept: 'application/json',
        },
      }
    )

    if (!res.ok) throw new Error('Delete failed')

    const data = await res.json()
    gallery.value = data.gallery.map(img => ({
    ...img,
    url: `/storage/${img.path}`,
    }))

  } catch (e) {
    alert(e.message || 'Ошибка удаления изображения')
  }
}

const handleGalleryUpload = async (event) => {
  if (!isEdit.value || !props.id) {
    alert('Сначала сохраните приглашение')
    return
  }

  const files = Array.from(event.target.files)
  if (!files.length) return

  const formData = new FormData()
  files.forEach(file => {
    formData.append('images[]', file)
  })

  try {
    const res = await fetch(
      `/api/invitations/${props.id}/gallery`,
      {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          Accept: 'application/json',
        },
        body: formData,
      }
    )

    if (!res.ok) throw new Error('Upload failed')

    const data = await res.json()

    gallery.value = data.gallery.map(img => ({
    ...img,
    url: `/storage/${img.path}`,
    }))

    // очистить input
    event.target.value = ''
  } catch (e) {
    alert(e.message || 'Ошибка загрузки изображений')
  }
}


/* =======================
   FORM
======================= */
function normalizeConfig(config = {}) {
  return {
    features: {
      program: false,
      gallery: false,
      rsvp: false,
      ...(config.features ?? {}),
    },
    design: {
      colors: {
        primary: config.design?.colors?.primary ?? '#16a34a',
        accent: config.design?.colors?.accent ?? '#22c55e',
        background: config.design?.colors?.background ?? '#ffffff',
      },
    },
  }
}

const buildPreviewPayload = () => ({
  template_key: selectedTemplate.value?.key,
  invitation: {
    bride_name: form.value.bride_name,
    groom_name: form.value.groom_name,
    date: form.value.date,
    time: form.value.time,
    venue_name: form.value.venue_name,
    venue_address: form.value.venue_address,
    dress_code: form.value.dress_code,
    data: {
      features: form.value.data.features,
      design: form.value.data.design,
      program: program.value,
      gallery: gallery.value.map(img => ({
        path: img.path ?? img.url?.replace('/storage/', '')
      })),
    }
  }
})

const updatePreview = async () => {
  if (!previewFrame.value || !selectedTemplate.value) return

  // ✅ DEMO / PUBLIC → GET preview
  if (isDemo.value) {
    const params = new URLSearchParams({
      invitation: JSON.stringify({
        bride_name: form.value.bride_name,
        groom_name: form.value.groom_name,
        date: form.value.date,
        time: form.value.time,
        venue_name: form.value.venue_name,
        venue_address: form.value.venue_address,
        dress_code: form.value.dress_code,
        data: {
          features: form.value.data.features,
          design: form.value.data.design,
          program: program.value,
        },
      }),
    })

    previewFrame.value.src =
      `/demo/preview/${selectedTemplate.value.key}?${params.toString()}`

    return
  }

  // 🔒 ADMIN → POST preview
  const payload = buildPreviewPayload()

  const html = `
    <form id="previewForm" method="POST" action="/preview/invitation">
      <input type="hidden" name="_token" value="${csrfToken}">
      <input type="hidden" name="template_key" value="${payload.template_key}">
      <input type="hidden" name="invitation"
        value='${JSON.stringify(payload.invitation)}'>
    </form>
    <script>document.getElementById('previewForm').submit()<\/script>
  `

  previewFrame.value.srcdoc = html
}



const updatePreviewDebounced = debounce(updatePreview, 500)

const form = ref({
  invitation_template_id: null,
  bride_name: '',
  groom_name: '',
  date: '',
  time: '',
  venue_name: '',
  venue_address: '',
  dress_code: '',
  status: 'pending',
  user_id: null,
  data: normalizeConfig(),
})

/* =======================
   PROGRAM
======================= */
const program = ref([
  { time: '18:00', label: 'Welcome drink' },
  { time: '19:00', label: 'Ceremony' },
  { time: '20:00', label: 'Dinner & party' },
])

const addProgramItem = () => {
  program.value.push({ time: '', label: '' })
}

/* =======================
   COMPUTED
======================= */
const templateTitle = computed(() => {
  if (selectedTemplate.value) {
    return `${selectedTemplate.value.name} template`
  }
  return isEdit.value ? 'Existing invitation' : ''
})

const totalPrice = ref(0)

/* =======================
   WATCHERS
======================= */
// sync selected template → form
watch(selectedTemplate, tpl => {
  if (!tpl) return

  form.value.invitation_template_id = tpl.id

  const merged = normalizeConfig()

  Object.assign(
    merged.design.colors,
    tpl.config?.design?.colors ?? {}
  )

  Object.assign(
    merged.features,
    tpl.config?.features ?? {}
  )

  form.value.data = merged

})


// when templates loaded → set selected template in edit mode
watch(templates, () => {
  if (!isEdit.value || !form.value.invitation_template_id) return

  selectedTemplate.value =
    templates.value.find(t => t.id === form.value.invitation_template_id) || null
})

watch(
  () => form.value.data.features.gallery,
  enabled => {
    if (!enabled) {
      gallery.value = []
    }
  }
)

let lastPreviewSignature = null

watch(previewSignature, () => {
  if (!selectedTemplate.value) return

  if (previewSignature.value === lastPreviewSignature) return

  lastPreviewSignature = previewSignature.value
  updatePreviewDebounced()
})


let lastSignature = null

watch(priceSignature, () => {
  if (!selectedTemplate.value) return

  if (priceSignature.value === lastSignature) return

  lastSignature = priceSignature.value
  recalcPriceDebounced()
})


/* =======================
   API HELPERS
======================= */
const csrfToken =
  document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''

const loadPricing = async () => {
  const res = await fetch('/api/invitation-pricing')
  pricing.value = await res.json()
}

const loadTemplates = async () => {
  const { templates: tpls, error } = await fetchTemplates()
  templates.value = tpls
  templatesError.value = error
}

const fetchUsers = async () => {
  loadingUsers.value = true
  try {
    const res = await fetch('/api/users')
    if (!res.ok) return
    users.value = await res.json()
  } finally {
    loadingUsers.value = false
  }
}

const loadInvitation = async () => {
  const res = await fetch(`/api/invitations/${props.id}`)
  if (!res.ok) throw new Error('Failed to load invitation')

  const data = await res.json()

  form.value.invitation_template_id = data.template?.id ?? null
  form.value.bride_name = data.bride_name
  form.value.groom_name = data.groom_name
  form.value.date = data.date || ''
  form.value.time = data.time || ''
  form.value.venue_name = data.venue_name || ''
  form.value.venue_address = data.venue_address || ''
  form.value.dress_code = data.dress_code || ''
  form.value.status = data.status
  form.value.user_id = data.user_id ?? null

  totalPrice.value = data.price

  // 🔥 ВАЖНО
  form.value.data = normalizeConfig(data.config)

  program.value = Array.isArray(data.config?.program)
    ? data.config.program
    : []

  gallery.value = Array.isArray(data.config?.gallery)
  ? data.config.gallery.map(img => ({
      ...img,
      url: `/storage/${img.path}`,
    }))
  : []
  
  lastSignature = null
  recalcPriceDebounced()

  // 🔥 ВОТ ТУТ, И ТОЛЬКО ТУТ
  selectedTemplate.value =
    templates.value.find(t => t.id === form.value.invitation_template_id) || null
}


const loadTemplateByKey = async () => {
  const res = await fetch(`/api/templates/${props.templateKey}`)
  if (!res.ok) throw new Error('Failed to load template')

  const tpl = await res.json()
  selectedTemplate.value = tpl
}

/* =======================
   ACTIONS
======================= */
const handleCreateUser = async () => {
  if (!isEdit.value || !props.id) {
    errorUser.value = 'User can be added only after invitation is created'
    return
  }

  try {
    loadingUser.value = true
    errorUser.value = null

    const { data } = await axios.post(
      `/api/invitations/${props.id}/create-user`
    )

    if (data.user) {
      form.value.user_id = data.user.id
      alert(`User created: ${data.user.email}`)
    }
  } catch (e) {
    errorUser.value =
      e.response?.data?.message || 'Failed to create user'
  } finally {
    loadingUser.value = false
  }
}

const handleSubmit = async () => {

  console.log('FORM STATE', form.value)

  if (!form.value.time) {
    alert('Time is empty in form state')
  }

  submitting.value = true
  error.value = ''

  try {
    form.value.data.program = program.value

    const payload = {
      ...form.value,
      data: {
        features: { ...form.value.data.features },
        design: { ...form.value.data.design },
        program: program.value,
      },
    }

    let url = '/api/invitations'
    let method = 'POST'

    if (isEdit.value) {
      url = `/api/invitations/${props.id}`
      method = 'PUT'
    }

    const res = await fetch(url, {
      method,
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify(payload),
    })

    if (!res.ok) throw new Error('Save failed')

    const saved = await res.json()
    totalPrice.value = saved.price

    alert(
      isEdit.value
        ? 'Invitation updated!'
        : `Invitation created! URL: ${location.origin}/i/${saved.slug}`
    )

    router.push({ name: 'invitations.index' })
  } catch (e) {
    error.value = e.message || 'Error saving invitation'
  } finally {
    submitting.value = false
  }
}

/* =======================
   MOUNT
======================= */
onMounted(async () => {
  try {
    await loadPricing()
    await fetchUsers()
    await loadTemplates()

    if (isEdit.value && props.id) {
      await loadInvitation()
    } else if (props.templateKey) {
      await loadTemplateByKey()
    }
    if (selectedTemplate.value) {
      updatePreview()
    }

  } catch (e) {
    error.value = e.message || 'Initialization failed'
  }
  if (window.__DEMO_INVITATION__) {
    const demo = window.__DEMO_INVITATION__

    if (demo.invitation) {
      Object.assign(form.value, {
        bride_name: demo.invitation.bride_name ?? '',
        groom_name: demo.invitation.groom_name ?? '',
        date: demo.invitation.date ?? '',
        time: demo.invitation.time ?? '',
        venue_name: demo.invitation.venue_name ?? '',
        venue_address: demo.invitation.venue_address ?? '',
        dress_code: demo.invitation.dress_code ?? '',
      })

      if (demo.invitation.data) {
        form.value.data = normalizeConfig(demo.invitation.data)
      }

      if (demo.invitation.data?.program) {
        program.value = demo.invitation.data.program
      }
    }
  }

})
</script>
