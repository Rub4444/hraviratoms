<template>
  <button
    :type="type"
    :class="classes"
    v-bind="$attrs"
  >
    <slot />
  </button>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  type: { type: String, default: 'button' },
  variant: { type: String, default: 'primary' },
  size: { type: String, default: 'md' },
})

const base =
  'inline-flex items-center justify-center rounded-full font-medium transition'

const variants = {
  primary:
    'bg-leaf text-white hover:bg-leaf-deep shadow-sm shadow-leaf/40',
  outline:
    'border border-slate-300 text-slate-800 hover:bg-slate-50',
  danger:
    'bg-red-500 text-white hover:bg-red-600'
}

const sizes = {
  sm: 'px-3 py-1 text-xs',
  md: 'px-4 py-1.5 text-sm',
  lg: 'px-5 py-2 text-sm'
}

const classes = computed(() => {
  return `${base} ${variants[props.variant]} ${sizes[props.size]}`
})
</script>
