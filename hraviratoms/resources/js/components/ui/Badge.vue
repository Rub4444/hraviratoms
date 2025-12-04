<template>
  <span :class="classes">
    <slot />
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  type: { type: String, default: 'default' }, // success, gray
})

const styles = {
  success:
    'bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full text-[11px] font-medium',
  gray:
    'bg-slate-50 text-slate-500 px-2 py-0.5 rounded-full text-[11px] font-medium',
}

const classes = computed(() => styles[props.type])
</script>
