<template>
  <div :class="classes">
    <slot />
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  padding: { type: String, default: 'md' },
})

const paddings = {
  sm: 'p-3',
  md: 'p-4',
  lg: 'p-5',
}

const classes = computed(() =>
  `rounded-2xl border bg-white shadow-sm ${paddings[props.padding]}`
)
</script>
