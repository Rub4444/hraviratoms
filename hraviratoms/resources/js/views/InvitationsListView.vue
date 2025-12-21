<template>
  <section>
    <div
      class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
    >
      <div>
        <h1 class="text-xl sm:text-2xl font-semibold">My invitations</h1>
        <p class="mt-1 text-xs sm:text-sm text-slate-600">
          Здесь отображаются все созданные приглашения с публичными ссылками.
        </p>
      </div>

      <div class="flex flex-wrap items-center gap-2">
        <!-- ФИЛЬТР ПО СТАТУСУ -->
        <select
            v-if="isSuperadmin"
          v-model="statusFilter"
          class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs text-slate-700"
        >
          <option value="all">Все</option>
          <option value="pending">Pending</option>
          <option value="published">Published</option>
          <option value="rejected">Rejected</option>
        </select>

        <!-- КНОПКА МАССОВОГО УДАЛЕНИЯ -->
        <Button
          v-if="isSuperadmin && selectedIds.length"
          type="button"
          variant="danger"
          size="xs"
          class="rounded-full px-3 py-1 text-xs"
          @click="deleteSelected"
        >
          Delete selected ({{ selectedIds.length }})
        </Button>

        <!-- СОЗДАНИЕ НОВОГО -->
        <Button
          v-if="isSuperadmin"
          variant="primary"
          size="xs"
          class="btn-primary text-xs"
          @click="$router.push({ name: 'templates.index' })"
        >
          + New invitation
        </Button>
      </div>
    </div>

    <!-- LOADING -->
    <Card
      v-if="loading"
      class="rounded-2xl border bg-white p-4 text-sm text-slate-500 shadow-sm"
    >
      Loading invitations...
    </Card>

    <!-- ПУСТО -->
    <Card
      v-else-if="!filteredInvitations.length"
      class="rounded-2xl border bg-white p-4 text-sm text-slate-500 shadow-sm"
    >
      Пока нет ни одного приглашения.
    </Card>

    <!-- ===== ОСНОВНОЙ КОНТЕНТ (ЕСЛИ ЕСТЬ ДАННЫЕ) ===== -->
    <div v-else>
      <!-- ===== DESKTOP TABLE (md+) ===== -->
      <Card
        class="hidden overflow-hidden rounded-2xl border bg-white p-0 shadow-sm md:block"
      >
        <table class="min-w-full text-left text-sm">
          <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>
              <th class="w-8 px-4 py-3">
                <input
                  type="checkbox"
                  :checked="allVisibleSelected"
                  @change="toggleSelectAll($event)"
                />
              </th>
              <th class="px-4 py-3">Couple</th>
              <th class="px-4 py-3">Template</th>
              <th class="px-4 py-3">Owner</th>
              <th class="px-4 py-3">Date</th>
              <th class="px-4 py-3">Status</th>
              <th class="px-4 py-3">Link</th>
              <th class="px-4 py-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <InvitationItem
              v-for="invitation in filteredInvitations"
              :key="invitation.id"
              :item="invitation"
              mode="desktop"
              :selected="selectedIds.includes(invitation.id)"
              :is-super-admin="isSuperAdmin"
              :public-url="getPublicUrl(invitation)"
              @select="toggleSelect"
              @copy="copyLink"
              @edit="(inv) => $router.push({ name: 'invitations.edit', params: { id: inv.id } })"
              @rsvp="(inv) => $router.push({ name: 'invitations.rsvps', params: { id: inv.id } })"
              @delete="deleteInvitation"
              @change-status="changeStatus"
            />
          </tbody>
        </table>
      </Card>

      <!-- ===== MOBILE CARDS (< md) ===== -->
      <div class="mt-3 space-y-3 md:hidden">
        <!-- чекбокс "выделить все" + инфо -->
        <div class="flex items-center justify-between text-xs text-slate-600">
          <label class="flex items-center gap-2">
            <input
              type="checkbox"
              :checked="allVisibleSelected"
              @change="toggleSelectAll($event)"
            />
            <span>Выбрать все</span>
          </label>
          <span v-if="selectedIds.length">
            Выбрано: {{ selectedIds.length }}
          </span>
        </div>
        <InvitationItem
          v-for="invitation in filteredInvitations"
          :key="invitation.id"
          :item="invitation"
          mode="mobile"
          :selected="selectedIds.includes(invitation.id)"
          :is-super-admin="isSuperAdmin"
          :public-url="getPublicUrl(invitation)"
          @select="toggleSelect"
          @copy="copyLink"
          @edit="(inv) => $router.push({ name: 'invitations.edit', params: { id: inv.id } })"
          @rsvp="(inv) => $router.push({ name: 'invitations.rsvps', params: { id: inv.id } })"
          @delete="deleteInvitation"
          @change-status="changeStatus"
        />
      </div>
    </div>

    <p v-if="error" class="mt-3 text-xs text-red-500">
      {{ error }}
    </p>
  </section>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { isSuperAdmin as isSuperAdminHelper } from '../utils/meta'
import {
  fetchInvitations,
  deleteInvitationApi,
  bulkDeleteInvitationsApi,
} from '../services/invitationsApi'

import Button from '../components/ui/Button.vue'
import Card from '../components/ui/Card.vue'
import InvitationItem from '../components/invitations/InvitationItem.vue'

const invitations = ref([])
const loading = ref(true)
const error = ref('')

const statusFilter = ref('all') // all | published | draft
const selectedIds = ref([])

// superadmin флаг
const isSuperadmin = ref(isSuperAdminHelper())
const isSuperAdmin = isSuperadmin // для удобства в template

// отфильтрованный список по статусу
const filteredInvitations = computed(() => {
  if (statusFilter.value === 'all') {
    return invitations.value
  }

  return invitations.value.filter(
    (inv) => inv.status === statusFilter.value
  )
})


// все ли видимые строки выбраны
const allVisibleSelected = computed(() => {
  if (!filteredInvitations.value.length) return false
  return filteredInvitations.value.every((inv) =>
    selectedIds.value.includes(inv.id),
  )
})

const getPublicUrl = (invitation) => {
  return `${window.location.origin}/i/${invitation.slug}`
}

const copyLink = async (invitation) => {
  const url = getPublicUrl(invitation)
  try {
    await navigator.clipboard.writeText(url)
    alert('Link copied!')
  } catch {
    prompt('Copy this link:', url)
  }
}

const formatDate = (value) => {
  if (!value) return ''
  try {
    const date = new Date(value)
    return date.toLocaleDateString('ru-RU')
  } catch {
    return value
  }
}

const changeStatus = async ({ id, status }) => {
  const inv = invitations.value.find(i => i.id === id)
  if (!inv) return

  const prevStatus = inv.status
  inv.status = status

  try {
    const res = await fetch(`/api/invitations/${id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({ status }),
    })

    if (!res.ok) throw new Error()

  } catch (e) {
    inv.status = prevStatus // 🔙 откат
    alert('Failed to update invitation status')
  }
}



const loadData = async () => {
  loading.value = true
  error.value = ''

  const { data, meta, error: err } = await fetchInvitations()

  // console.log(data.map(i => ({
  //   id: i.id,
  //   is_published: i.is_published,
  //   type: typeof i.is_published
  // })))

  if (err) {
    invitations.value = []
  } else {
    invitations.value = data      // ВАЖНО!!!
  }


  loading.value = false
}


const deleteInvitation = async (invitation) => {
  const confirmed = confirm(
    `Удалить приглашение для пары "${invitation.bride_name} & ${invitation.groom_name}"?`,
  )

  if (!confirmed) return

  try {
    await deleteInvitationApi(invitation.id)

    invitations.value = invitations.value.filter(
      (i) => i.id !== invitation.id,
    )
    selectedIds.value = selectedIds.value.filter(
      (id) => id !== invitation.id,
    )

    alert('Invitation deleted')
  } catch (e) {
    console.error(e)
    alert('Failed to delete invitation')
  }
}

// выбор/снятие выбора одной строки
const toggleSelect = (id, event) => {
  if (event.target.checked) {
    if (!selectedIds.value.includes(id)) {
      selectedIds.value.push(id)
    }
  } else {
    selectedIds.value = selectedIds.value.filter((x) => x !== id)
  }
}

// выбор/снятие выбора всех видимых
const toggleSelectAll = (event) => {
  if (event.target.checked) {
    const visibleIds = filteredInvitations.value.map((inv) => inv.id)
    selectedIds.value = Array.from(
      new Set([...selectedIds.value, ...visibleIds]),
    )
  } else {
    const visibleIds = filteredInvitations.value.map((inv) => inv.id)
    selectedIds.value = selectedIds.value.filter(
      (id) => !visibleIds.includes(id),
    )
  }
}

// массовое удаление
const deleteSelected = async () => {
  if (!selectedIds.value.length) return

  const confirmed = confirm(
    `Удалить выбранные приглашения (${selectedIds.value.length} шт.)?`,
  )
  if (!confirmed) return

  await bulkDeleteInvitationsApi(selectedIds.value)

  invitations.value = invitations.value.filter(
    (inv) => !selectedIds.value.includes(inv.id),
  )
  selectedIds.value = []
  alert('Selected invitations deleted')
}

onMounted(loadData)
</script>
