<script setup>
import { ref, computed } from 'vue'
import InvitationEditorCore from '@/invitation/InvitationEditorCore.vue'
import { buildFakeInvitation } from './fakeInvitation'

const props = defineProps({
  templates: Array,
  defaultTemplateKey: String,
})

const selectedTemplateKey = ref(props.defaultTemplateKey)

const selectedTemplate = computed(() =>
  props.templates.find(t => t.key === selectedTemplateKey.value)
)

/**
 * Fake invitation — только для визуализации
 */
const invitation = computed(() => {
  if (!selectedTemplate.value) return null

  return buildFakeInvitation(selectedTemplate.value)
})
</script>

<template>
  <InvitationEditorCore
    v-if="invitation"
    :mode="'demo'"
    :template-key="selectedTemplateKey"
  />
</template>
