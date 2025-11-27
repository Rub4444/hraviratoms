<template>
  <section>
    <h1 class="mb-2 text-2xl font-semibold">
      {{ isEdit ? 'Edit invitation' : 'Create invitation' }}
    </h1>
    <p class="mb-5 text-sm text-slate-600">
      {{ templateTitle }}
    </p>

    <div class="grid gap-6 md:grid-cols-[minmax(0,1.1fr)_minmax(0,1fr)]">
      <!-- Форма -->
      <form class="space-y-4" @submit.prevent="handleSubmit">
        <div class="grid gap-4 md:grid-cols-2">
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
        <div>
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

        <div class="flex items-center justify-between pt-2">
          <label class="inline-flex items-center gap-2 text-xs text-slate-600">
            <input
              v-model="form.is_published"
              type="checkbox"
              class="rounded border-slate-300 text-rose-500 focus:ring-rose-400"
            />
            <span>Publish invitation</span>
          </label>

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

      <!-- Превью -->
      <div class="hidden md:block">
        <h2 class="mb-3 text-sm font-medium text-slate-700">Preview</h2>
        <div class="invite-page rounded-3xl border bg-slate-100 p-4">
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
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

const props = defineProps({
  id: {
    type: String,
    default: null,
  },
  templateKey: {
    type: String,
    default: null,
  },
});

const isEdit = computed(() => !!props.id);

const template = ref(null);
const templateTitle = computed(() => {
  if (!template.value && !isEdit.value) return '';
  if (template.value) return `${template.value.name} template`;
  return 'Existing invitation';
});

const form = ref({
  invitation_template_id: null,
  bride_name: '',
  groom_name: '',
  date: '',
  time: '',
  venue_name: '',
  venue_address: '',
  dress_code: '',
  is_published: false,
});

const program = ref([
  { time: '18:00', label: 'Welcome drink' },
  { time: '19:00', label: 'Ceremony' },
  { time: '20:00', label: 'Dinner & party' },
]);

const submitting = ref(false);
const error = ref('');

const addProgramItem = () => {
  program.value.push({ time: '', label: '' });
};

onMounted(async () => {
  try {
    if (isEdit.value && props.id) {
      // режим редактирования
      const res = await fetch(`/api/invitations/${props.id}`);
      if (!res.ok) throw new Error('Failed to load invitation');

      const data = await res.json();
      template.value = data.template;

      form.value.invitation_template_id = data.invitation_template_id;
      form.value.bride_name = data.bride_name;
      form.value.groom_name = data.groom_name;
      form.value.date = data.date || '';
      form.value.time = data.time || '';
      form.value.venue_name = data.venue_name || '';
      form.value.venue_address = data.venue_address || '';
      form.value.dress_code = data.dress_code || '';
      form.value.is_published = !!data.is_published;

      const programData = data.data?.program;
      if (Array.isArray(programData) && programData.length) {
        program.value = programData;
      }
    } else if (!isEdit.value && props.templateKey) {
      // режим создания → загружаем шаблон по key
      const res = await fetch(`/api/templates/${props.templateKey}`);
      if (!res.ok) throw new Error('Failed to load template');

      template.value = await res.json();
      form.value.invitation_template_id = template.value.id;
    }
  } catch (e) {
    console.error(e);
    error.value = e.message || 'Failed to load data';
  }
});

const handleSubmit = async () => {
  submitting.value = true;
  error.value = '';

  try {
    const payload = {
      ...form.value,
      data: {
        program: program.value,
      },
    };

    let url = '/api/invitations';
    let method = 'POST';

    if (isEdit.value && props.id) {
      url = `/api/invitations/${props.id}`;
      method = 'PUT';
    }

    const res = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(payload),
    });

    if (!res.ok) {
      throw new Error('Failed to save invitation');
    }

    const saved = await res.json();

    if (!isEdit.value) {
      alert(`Invitation created! Public URL: ${window.location.origin}/i/${saved.slug}`);
    } else {
      alert('Invitation updated!');
    }

    router.push({ name: 'invitations.index' });
  } catch (e) {
    console.error(e);
    error.value = e.message || 'Error saving invitation';
  } finally {
    submitting.value = false;
  }
};
</script>
