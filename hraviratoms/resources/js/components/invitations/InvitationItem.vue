<template>
  <!-- DESKTOP ROW -->
  <tr v-if="mode === 'desktop'" class="border-t last:border-b hover:bg-slate-50/60">

    <td class="px-4 py-3 align-top w-8">
      <input
        type="checkbox"
        :value="item.id"
        :checked="selected"
        @change="$emit('select', item.id, $event)"
      />
    </td>

    <td class="px-4 py-3 align-top">
      <div class="font-medium text-slate-900">
        {{ item.bride_name }} &amp; {{ item.groom_name }}
      </div>
      <div class="text-xs text-slate-500">
        {{ item.title || '—' }}
      </div>
    </td>

    <td class="px-4 py-3 align-top text-xs text-slate-600">
      {{ item.template?.name || '—' }}
    </td>

    <td class="px-4 py-3 align-top text-xs text-slate-600">
      {{ item.user?.name || 'Superadmin only' }}
    </td>

    <td class="px-4 py-3 align-top text-xs text-slate-600">
      <span v-if="item.date">{{ formatDate(item.date) }}</span>
      <span v-else>—</span>
    </td>

    <td class="px-4 py-3 align-top text-xs">
      <Badge :type="item.is_published ? 'success' : 'gray'">
        {{ item.is_published ? 'Published' : 'Draft' }}
      </Badge>
    </td>

    <td class="px-4 py-3 align-top text-xs">
      <div v-if="item.is_published">
        <div class="max-w-[200px] truncate text-slate-600">
          {{ item.slug }}
        </div>
        <button
          class="mt-1 text-[11px] text-rose-500 hover:text-rose-600"
          type="button"
          @click="$emit('copy', item)"
        >
          Copy link
        </button>
      </div>
      <div v-else class="text-red-500 text-sm">-</div>
    </td>

    <td class="px-4 py-3 align-top text-right text-xs">
      <div class="flex flex-wrap justify-end gap-2">

        <a
          v-if="isSuperAdmin"
          :href="publicUrl"
          target="_blank"
          class="rounded-full border border-slate-200 px-3 py-1 font-medium hover:bg-slate-50"
        >
          Open
        </a>

        <button
          v-if="isSuperAdmin"
          class="rounded-full bg-slate-900 px-3 py-1 font-medium text-white hover:bg-slate-800"
          @click="$emit('edit', item)"
        >
          Edit
        </button>

        <button
          class="rounded-full border border-leaf-soft px-3 py-1 font-medium text-leaf-deep hover:bg-leaf-soft/20"
          @click="$emit('rsvp', item)"
        >
          RSVP
        </button>

        <button
          v-if="isSuperAdmin"
          class="rounded-full bg-red-500 px-3 py-1 font-medium text-white hover:bg-red-600"
          @click="$emit('delete', item)"
        >
          Delete
        </button>

      </div>
    </td>
  </tr>

  <!-- MOBILE CARD -->
  <div
    v-else
    class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm"
  >
    <!-- Заголовок -->
    <div class="flex items-start justify-between gap-2">
      <div>
        <div class="flex items-center gap-2">
          <input
            type="checkbox"
            :value="item.id"
            :checked="selected"
            @change="$emit('select', item.id, $event)"
          />
          <div class="font-medium text-slate-900 text-sm">
            {{ item.bride_name }} & {{ item.groom_name }}
          </div>
        </div>
        <div class="mt-0.5 text-[11px] text-slate-500">
          {{ item.title || '—' }}
        </div>
      </div>

      <Badge :type="item.is_published ? 'success' : 'gray'">
        {{ item.is_published ? 'Published' : 'Draft' }}
      </Badge>
    </div>

    <!-- Данные -->
    <div class="mt-2 grid grid-cols-2 gap-1 text-[11px] text-slate-600">
      <div><span class="font-semibold">Template:</span> {{ item.template?.name || '—' }}</div>
      <div><span class="font-semibold">Owner:</span> {{ item.user?.name || '—' }}</div>
      <div><span class="font-semibold">Date:</span> {{ item.date ? formatDate(item.date) : '—' }}</div>
      <div><span class="font-semibold">Link:</span> {{ item.is_published ? item.slug : '-' }}</div>
    </div>

    <!-- Кнопки -->
    <div class="mt-3 flex flex-wrap gap-2 text-[11px]">

      <button
        v-if="item.is_published"
        class="rounded-full border border-rose-200 px-3 py-1 text-rose-500 hover:bg-rose-50"
        @click="$emit('copy', item)"
      >
        Copy link
      </button>

      <a
        v-if="isSuperAdmin"
        :href="publicUrl"
        target="_blank"
        class="rounded-full border border-slate-200 px-3 py-1 font-medium text-slate-800 hover:bg-slate-50"
      >
        Open
      </a>

      <button
        v-if="isSuperAdmin"
        class="rounded-full bg-slate-900 px-3 py-1 font-medium text-white hover:bg-slate-800"
        @click="$emit('edit', item)"
      >
        Edit
      </button>

      <button
        class="rounded-full border border-leaf-soft px-3 py-1 font-medium text-leaf-deep hover:bg-leaf-soft/20"
        @click="$emit('rsvp', item)"
      >
        RSVP
      </button>

      <button
        v-if="isSuperAdmin"
        class="ml-auto rounded-full bg-red-500 px-3 py-1 font-medium text-white hover:bg-red-600"
        @click="$emit('delete', item)"
      >
        Delete
      </button>
    </div>
  </div>
</template>

<script setup>
import Badge from '../ui/Badge.vue'
import { isSuperAdmin as isAdmin } from '../../utils/meta'

const props = defineProps({
  item: Object,
  mode: String, // desktop | mobile
  selected: Boolean,
})

const isSuperAdmin = isAdmin()

const publicUrl = `${window.location.origin}/i/${props.item.slug}`

const formatDate = (v) => new Date(v).toLocaleDateString('ru-RU')
</script>
