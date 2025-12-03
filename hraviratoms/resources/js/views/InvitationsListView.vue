<template>
  <section>
    <div class="mb-4 flex items-center justify-between gap-2">
      <div>
        <h1 class="text-2xl font-semibold">My invitations</h1>
        <p class="mt-1 text-sm text-slate-600">
          Здесь отображаются все созданные приглашения с публичными ссылками.
        </p>
      </div>

      <div class="flex items-center gap-2">
        <!-- ФИЛЬТР ПО СТАТУСУ -->
        <select
          v-model="statusFilter"
          class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs text-slate-700"
        >
          <option value="all">Все</option>
          <option value="published">Только published</option>
          <option value="draft">Только draft</option>
        </select>

        <!-- КНОПКА МАССОВОГО УДАЛЕНИЯ -->
        <button
          v-if="isSuperadmin && selectedIds.length"
          type="button"
          class="rounded-full bg-red-500 px-3 py-1 text-xs font-medium text-white hover:bg-red-600"
          @click="deleteSelected"
        >
          Delete selected ({{ selectedIds.length }})
        </button>

        <!-- СОЗДАНИЕ НОВОГО -->
        <button
          v-if="isSuperadmin"
          class="btn-primary"
          @click="$router.push({ name: 'templates.index' })"
        >
          + New invitation
        </button>
      </div>
    </div>

    <div
      v-if="loading"
      class="rounded-2xl border bg-white p-4 text-sm text-slate-500 shadow-sm"
    >
      Loading invitations...
    </div>

    <div
      v-else-if="!filteredInvitations.length"
      class="rounded-2xl border bg-white p-4 text-sm text-slate-500 shadow-sm"
    >
      Пока нет ни одного приглашения.
    </div>

    <div v-else class="overflow-hidden rounded-2xl border bg-white shadow-sm">
      <table class="min-w-full text-left text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
          <tr>
            <!-- ЧЕКБОКС В ХЕДЕРЕ: СЕЛЕКТ ВСЕ -->
            <th class="px-4 py-3 w-8">
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
            <th class="px-4 py-3">Published</th>
            <th class="px-4 py-3">Link</th>
            <th class="px-4 py-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="invitation in filteredInvitations"
            :key="invitation.id"
            class="border-t last:border-b hover:bg-slate-50/60"
          >
            <!-- ЧЕКБОКС В СТРОКЕ -->
            <td class="px-4 py-3 align-top">
              <input
                type="checkbox"
                :value="invitation.id"
                :checked="selectedIds.includes(invitation.id)"
                @change="toggleSelect(invitation.id, $event)"
              />
            </td>

            <td class="px-4 py-3 align-top">
              <div class="font-medium text-slate-900">
                {{ invitation.bride_name }} &amp; {{ invitation.groom_name }}
              </div>
              <div class="text-xs text-slate-500">
                {{ invitation.title || '—' }}
              </div>
            </td>

            <td class="px-4 py-3 align-top text-xs text-slate-600">
              {{ invitation.template?.name || '—' }}
            </td>

            <td class="px-4 py-3 align-top text-xs text-slate-600">
              {{ invitation.user?.name || 'Superadmin only' }}
            </td>

            <td class="px-4 py-3 align-top text-xs text-slate-600">
              <span v-if="invitation.date">
                {{ formatDate(invitation.date) }}
              </span>
              <span v-else>—</span>
            </td>

            <td class="px-4 py-3 align-top text-xs">
              <span
                v-if="invitation.is_published"
                class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-700"
              >
                Published
              </span>
              <span
                v-else
                class="inline-flex items-center rounded-full bg-slate-50 px-2 py-0.5 text-[11px] font-medium text-slate-500"
              >
                Draft
              </span>
            </td>

            <td class="px-4 py-3 align-top text-xs">
              <div v-if="invitation.is_published">
                <div class="max-w-[200px] truncate text-slate-600">
                  {{ invitation.slug }}
                </div>
                <button
                  class="mt-1 text-[11px] text-rose-500 hover:text-rose-600"
                  type="button"
                  @click="copyLink(invitation)"
                >
                  Copy link
                </button>
              </div>
              <div v-else class="text-red-500 text-sm">
                -
              </div>
            </td>

            <td class="px-4 py-3 align-top text-right text-xs">
              <div class="flex flex-wrap justify-end gap-2">
                <a
                  v-if="isSuperAdmin"
                  :href="getPublicUrl(invitation)"
                  target="_blank"
                  class="rounded-full border border-slate-200 px-3 py-1 font-medium hover:bg-slate-50"
                >
                  Open
                </a>

                <button
                  v-if="isSuperAdmin"
                  class="rounded-full bg-slate-900 px-3 py-1 font-medium text-white hover:bg-slate-800"
                  type="button"
                  @click="$router.push({ name: 'invitations.edit', params: { id: invitation.id } })"
                >
                  Edit
                </button>

                <button
                  class="rounded-full border border-leaf-soft px-3 py-1 font-medium text-leaf-deep hover:bg-leaf-soft/20"
                  type="button"
                  @click="$router.push({ name: 'invitations.rsvps', params: { id: invitation.id } })"
                >
                  RSVP
                </button>

                <button
                  v-if="isSuperAdmin"
                  class="rounded-full bg-red-500 px-3 py-1 font-medium text-white hover:bg-red-600"
                  type="button"
                  @click="deleteInvitation(invitation)"
                >
                  Delete
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <p v-if="error" class="mt-3 text-xs text-red-500">
      {{ error }}
    </p>
  </section>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';

const isSuperAdmin =
  document.querySelector('meta[name="superadmin"]')?.getAttribute('content') === '1';

const invitations = ref([]);
const loading = ref(true);
const error = ref('');

// фильтр по статусу
const statusFilter = ref('all'); // all | published | draft

// выбранные id для массового удаления
const selectedIds = ref([]);

// тот же флаг, что и выше, но реактивный для шаблона
const isSuperadmin = ref(
  document
    .querySelector('meta[name="superadmin"]')
    ?.getAttribute('content') === '1'
);

// CSRF-токен (для DELETE)
const csrfToken =
  document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// отфильтрованный список по is_published
const filteredInvitations = computed(() => {
  return invitations.value.filter((inv) => {
    if (statusFilter.value === 'published') {
      return !!inv.is_published;
    }
    if (statusFilter.value === 'draft') {
      return !inv.is_published;
    }
    return true; // all
  });
});

// все ли видимые строки выбраны
const allVisibleSelected = computed(() => {
  if (!filteredInvitations.value.length) return false;
  return filteredInvitations.value.every((inv) =>
    selectedIds.value.includes(inv.id)
  );
});

const getPublicUrl = (invitation) => {
  return `${window.location.origin}/i/${invitation.slug}`;
};

const copyLink = async (invitation) => {
  const url = getPublicUrl(invitation);
  try {
    await navigator.clipboard.writeText(url);
    alert('Link copied!');
  } catch {
    prompt('Copy this link:', url);
  }
};

const formatDate = (value) => {
  if (!value) return '';
  try {
    const date = new Date(value);
    return date.toLocaleDateString('ru-RU');
  } catch {
    return value;
  }
};

const fetchInvitations = async () => {
  loading.value = true;
  error.value = '';

  try {
    const res = await fetch('/api/invitations', {
      headers: {
        Accept: 'application/json',
      },
    });

    if (res.status === 403) {
      invitations.value = [];
      return;
    }

    if (!res.ok) {
      error.value = 'Не удалось загрузить приглашения';
      invitations.value = [];
      return;
    }

    invitations.value = await res.json();
  } catch (e) {
    if (import.meta.env.DEV) {
      console.error('fetchInvitations error', e);
    }
    error.value = 'Ошибка при загрузке приглашений';
    invitations.value = [];
  } finally {
    loading.value = false;
  }
};

const deleteInvitation = async (invitation) => {
  const confirmed = confirm(
    `Удалить приглашение для пары "${invitation.bride_name} & ${invitation.groom_name}"?`
  );

  if (!confirmed) return;

  try {
    const res = await fetch(`/api/invitations/${invitation.id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        Accept: 'application/json',
      },
    });

    if (!res.ok) {
      if (import.meta.env.DEV) {
        console.warn('Delete error status:', res.status);
      }
      alert('Failed to delete invitation');
      return;
    }

    invitations.value = invitations.value.filter((i) => i.id !== invitation.id);
    selectedIds.value = selectedIds.value.filter((id) => id !== invitation.id);
    alert('Invitation deleted');
  } catch (e) {
    if (import.meta.env.DEV) {
      console.error(e);
    }
    alert('Error deleting invitation');
  }
};

// выбор/снятие выбора одной строки
const toggleSelect = (id, event) => {
  if (event.target.checked) {
    if (!selectedIds.value.includes(id)) {
      selectedIds.value.push(id);
    }
  } else {
    selectedIds.value = selectedIds.value.filter((x) => x !== id);
  }
};

// выбор/снятие выбора всех видимых
const toggleSelectAll = (event) => {
  if (event.target.checked) {
    const visibleIds = filteredInvitations.value.map((inv) => inv.id);
    selectedIds.value = Array.from(new Set([...selectedIds.value, ...visibleIds]));
  } else {
    const visibleIds = filteredInvitations.value.map((inv) => inv.id);
    selectedIds.value = selectedIds.value.filter((id) => !visibleIds.includes(id));
  }
};

// массовое удаление
const deleteSelected = async () => {
  if (!selectedIds.value.length) return;

  const confirmed = confirm(
    `Удалить выбранные приглашения (${selectedIds.value.length} шт.)?`
  );
  if (!confirmed) return;

  for (const id of selectedIds.value) {
    try {
      const res = await fetch(`/api/invitations/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          Accept: 'application/json',
        },
      });

      if (!res.ok && import.meta.env.DEV) {
        console.warn('Delete error status:', id, res.status);
      }
    } catch (e) {
      if (import.meta.env.DEV) {
        console.error('Bulk delete error for', id, e);
      }
    }
  }

  invitations.value = invitations.value.filter(
    (inv) => !selectedIds.value.includes(inv.id)
  );
  selectedIds.value = [];
  alert('Selected invitations deleted');
};

onMounted(fetchInvitations);
</script>
