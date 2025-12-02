<template>
  <section>
    <div class="mb-4 flex items-center justify-between gap-2">
      <div>
        <h1 class="text-2xl font-semibold">My invitations</h1>
        <p class="mt-1 text-sm text-slate-600">
          Здесь отображаются все созданные приглашения с публичными ссылками.
        </p>
      </div>
      <button
        v-if="isSuperadmin"
        class="btn-primary"
        @click="$router.push({ name: 'templates.index' })"
        >
        + New invitation
        </button>

    </div>

    <div
      v-if="loading"
      class="rounded-2xl border bg-white p-4 text-sm text-slate-500 shadow-sm"
    >
      Loading invitations...
    </div>

    <div
      v-else-if="!invitations.length"
      class="rounded-2xl border bg-white p-4 text-sm text-slate-500 shadow-sm"
    >
      Пока нет ни одного приглашения.
    </div>

    <div v-else class="overflow-hidden rounded-2xl border bg-white shadow-sm">
      <table class="min-w-full text-left text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
          <tr>
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
            v-for="invitation in invitations"
            :key="invitation.id"
            class="border-t last:border-b hover:bg-slate-50/60"
          >
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
import { ref, onMounted } from 'vue';

const isSuperAdmin =
  document.querySelector('meta[name="superadmin"]')?.getAttribute('content') === '1';

const invitations = ref([]);
const loading = ref(true);
const error = ref('');


const isSuperadmin = ref(
  document
    .querySelector('meta[name="superadmin"]')
    ?.getAttribute('content') === '1'
);

// CSRF-токен (для DELETE)
const csrfToken =
  document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

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
      // нет прав — просто показываем пустой список без ошибок в консоли
      invitations.value = [];
      return;
    }

    if (!res.ok) {
      // неожиданная ошибка → можно тихо показать текст пользователю
      error.value = 'Не удалось загрузить приглашения';
      invitations.value = [];
      // НИЧЕГО не кидаем, чтобы не было Uncaught в консоли
      return;
    }

    invitations.value = await res.json();
  } catch (e) {
    // на проде можно вообще убрать console.error
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
    alert('Invitation deleted');
  } catch (e) {
    if (import.meta.env.DEV) {
      console.error(e);
    }
    alert('Error deleting invitation');
  }
};

onMounted(fetchInvitations);
</script>


