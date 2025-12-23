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
        <div v-if="enabled" class="flex justify-between text-slate-600">
          <span>{{ key }}</span>
          <span>+ {{ pricing.features?.[key] ?? 0 }} ֏</span>
        </div>
      </template>
      <div class="mt-2 border-t pt-2 flex justify-between font-semibold">
        <span>Total</span>
        <span>{{ totalPrice }} ֏</span>
      </div>
    </div>

    <button
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
            v-if="users.length"
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
            <div v-if="form.data.features">
              <h3 class="mt-4 mb-2 text-xs font-semibold text-slate-700">
                Features
              </h3>

              <label
                v-for="(enabled, key) in form.data.features"
                :key="key"
                class="flex items-center gap-2 text-sm"
              >
                <input
                  type="checkbox"
                  v-model="form.data.features[key]"
                />
                {{ key }}
              </label>
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
        <div>
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

        <div class="flex items-center justify-between pt-2">
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

      <div class="hidden md:block">
          <h2 class="mb-3 text-sm font-medium text-slate-700">Preview</h2>
          <div
            class="invite-page rounded-3xl border p-4"
            :style="{
              '--color-primary': form.data.design?.colors?.primary,
              '--color-accent': form.data.design?.colors?.accent,
              '--color-bg': form.data.design?.colors?.background,
            }"
          >
            <div class="card-soft">
              <div class="px-6 pt-8 pb-6 text-center">
                <p class="text-[10px] tracking-[0.35em] uppercase text-slate-400">
                  Օնլայն հրավիրատոմս
                </p>
                <h1 class="invite-names mt-3">
                  {{ form.bride_name || 'Bride' }} &amp; {{ form.groom_name || 'Groom' }}
                </h1>
                <p
                  v-if="form.date || form.time"
                  class="mt-3 text-xs tracking-[0.25em] uppercase invite-accent"
                >
                  {{ form.date || '2025-11-16' }}
                  <span v-if="form.time"> • {{ form.time }}</span>
                </p>
                <p v-if="form.venue_name" class="mt-3 text-sm text-slate-600">
                  {{ form.venue_name }}
                </p>
              </div>
            </div>
          </div>
          <p class="mt-2 text-[11px] text-slate-500">
            Стиль превью будет зависеть от темы (Elegant, Nature, Luxury, Pastel).
          </p>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { fetchTemplates } from '@/services/templatesApi'

/* =======================
   ROUTER / PROPS
======================= */
const route = useRoute()
const router = useRouter()

const props = defineProps({
  id: {
    type: String,
    default: null,
  },
  templateKey: {
    type: String,
    default: null,
  },
})

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


const totalPrice = computed(() => {
  if (!selectedTemplate.value) return 0

  let price = selectedTemplate.value.base_price || 0

  for (const key in form.value.data.features) {
    if (form.value.data.features[key]) {
      price += pricing.value.features?.[key] || 0
    }
  }

  return price
})

/* =======================
   WATCHERS
======================= */
// sync selected template → form
watch(selectedTemplate, tpl => {
  form.value.invitation_template_id = tpl?.id ?? null
})

// when templates loaded → set selected template in edit mode
watch(templates, () => {
  if (!isEdit.value || !form.value.invitation_template_id) return

  selectedTemplate.value =
    templates.value.find(t => t.id === form.value.invitation_template_id) || null
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

  // 🔥 ВАЖНО
  form.value.data = normalizeConfig(data.config)

  program.value = Array.isArray(data.config?.program)
    ? data.config.program
    : []

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
  } catch (e) {
    error.value = e.message || 'Initialization failed'
  }
})
</script>
