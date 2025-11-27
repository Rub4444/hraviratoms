<template>
  <section>
    <button
      type="button"
      class="mb-4 text-xs text-slate-500 hover:text-leaf-deep"
      @click="$router.push({ name: 'invitations.index' })"
    >
      ← Back to invitations
    </button>

    <div class="mb-4 flex flex-wrap items-baseline justify-between gap-3">
      <div>
        <h1 class="text-2xl font-display font-semibold text-slate-900">
          Guests &amp; RSVP
        </h1>
        <p v-if="invitation" class="mt-1 text-sm text-slate-600">
          {{ invitation.bride_name }} &amp; {{ invitation.groom_name }}
          <span v-if="invitation.date"> · {{ formatDate(invitation.date) }}</span>
        </p>
      </div>

      <div v-if="invitation" class="text-xs">
        <p class="text-slate-500">Public link:</p>
        <a
          :href="publicUrl"
          target="_blank"
          class="text-leaf hover:text-leaf-deep underline underline-offset-2"
        >
          {{ publicUrl }}
        </a>
      </div>
    </div>

    <!-- Статистика -->
    <div v-if="stats" class="mb-5 grid gap-4 md:grid-cols-4">
      <div class="card-soft p-4">
        <p class="text-[11px] uppercase tracking-[0.2em] text-slate-500">
          Ընդհանուր պատասխաններ
        </p>
        <p class="mt-2 text-2xl font-semibold text-slate-900">
          {{ stats.total_responses }}
        </p>
      </div>
      <div class="card-soft p-4">
        <p class="text-[11px] uppercase tracking-[0.2em] text-leaf-deep">
          Կգան
        </p>
        <p class="mt-2 text-2xl font-semibold text-slate-900">
          {{ stats.yes }}
        </p>
        <p class="mt-1 text-[11px] text-slate-500">
          Մոտ {{ stats.guests_yes_count }} մարդ
        </p>
      </div>
      <div class="card-soft p-4">
        <p class="text-[11px] uppercase tracking-[0.2em] text-slate-500">
          Հնարավոր է
        </p>
        <p class="mt-2 text-2xl font-semibold text-slate-900">
          {{ stats.maybe }}
        </p>
      </div>
      <div class="card-soft p-4">
        <p class="text-[11px] uppercase tracking-[0.2em] text-slate-500">
          Չեն մասնակցի
        </p>
        <p class="mt-2 text-2xl font-semibold text-slate-900">
          {{ stats.no }}
        </p>
        <p class="mt-1 text-[11px] text-slate-500">
          Ընդամենը {{ stats.guests_total }} հայտավորված հյուր
        </p>
      </div>
    </div>

    <!-- Таблица гостей -->
    <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
      <table class="min-w-full text-left text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
          <tr>
            <th class="px-4 py-3">Guest</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">People</th>
            <th class="px-4 py-3">Phone</th>
            <th class="px-4 py-3">Message</th>
            <th class="px-4 py-3">Submitted at</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="!loading && items.length === 0">
            <td class="px-4 py-4 text-sm text-slate-500" colspan="6">
              Пока нет ни одного RSVP. Как только гости начнут отвечать, они появятся здесь.
            </td>
          </tr>
          <tr
            v-for="item in items"
            :key="item.id"
            class="border-t last:border-b hover:bg-slate-50/60"
          >
            <td class="px-4 py-3 align-top">
              <div class="font-medium text-slate-900">
                {{ item.guest_name }}
              </div>
            </td>
            <td class="px-4 py-3 align-top">
              <span
                v-if="item.status === 'yes'"
                class="inline-flex rounded-full bg-leaf-soft/40 px-2 py-0.5 text-[11px] font-medium text-leaf-deep"
              >
                Կգամ
              </span>
              <span
                v-else-if="item.status === 'maybe'"
                class="inline-flex rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-medium text-amber-700"
              >
                Հնարավոր է
              </span>
              <span
                v-else
                class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600"
              >
                Չեմ կարող
              </span>
            </td>
            <td class="px-4 py-3 align-top text-xs text-slate-700">
              {{ item.guests_count }}
            </td>
            <td class="px-4 py-3 align-top text-xs text-slate-700">
              {{ item.guest_phone || '—' }}
            </td>
            <td class="px-4 py-3 align-top text-xs text-slate-700">
              <div class="max-w-xs whitespace-pre-wrap">
                {{ item.message || '—' }}
              </div>
            </td>
            <td class="px-4 py-3 align-top text-xs text-slate-500">
              {{ formatDateTime(item.created_at) }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <p v-if="error" class="mt-3 text-xs text-red-500">
      {{ error }}
    </p>

    <div v-if="loading" class="mt-3 text-xs text-slate-500">
      Loading RSVPs...
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const invitation = ref(null);
const stats = ref(null);
const items = ref([]);
const loading = ref(true);
const error = ref('');

const publicUrl = computed(() => {
  if (!invitation.value) return '';
  return `${window.location.origin}/i/${invitation.value.slug}`;
});

const fetchData = async () => {
  loading.value = true;
  error.value = '';

  try {
    const id = route.params.id;
    const res = await fetch(`/api/invitations/${id}/rsvps`);
    if (!res.ok) throw new Error('Failed to load RSVPs');

    const data = await res.json();
    invitation.value = data.invitation;
    stats.value = data.stats;
    items.value = data.items || [];
  } catch (e) {
    console.error(e);
    error.value = e.message || 'Error loading RSVPs';
  } finally {
    loading.value = false;
  }
};

const formatDate = (value) => {
  if (!value) return '';
  try {
    const d = new Date(value);
    return d.toLocaleDateString('ru-RU');
  } catch {
    return value;
  }
};

const formatDateTime = (value) => {
  if (!value) return '';
  try {
    const d = new Date(value);
    return d.toLocaleString('ru-RU', {
      dateStyle: 'short',
      timeStyle: 'short',
    });
  } catch {
    return value;
  }
};

onMounted(fetchData);
</script>
