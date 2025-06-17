<script setup lang="ts">
import FormInput from '@/pages/modules/base-page/FormInput.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps<{
  mode: 'create' | 'edit'
  initialData?: Record<string, any>
  listBg?: Record<string, string>
  listInitPage?: Record<string, string>
}>()

const bgOptions = props.listBg
  ? Object.entries(props.listBg).map(([value, label]) => ({ value, label }))
  : []
const initPageOptions = props.listInitPage
  ? Object.entries(props.listInitPage).map(([value, label]) => ({ value, label }))
  : []

const formInputs = [
  {
    name: 'name',
    label: 'Nama Team',
    type: 'text' as const,
    placeholder: 'Masukkan nama team',
    required: true,
  },
]
const handleSave = async (data: Record<string, any>) => {
  const formattedData = {
    ...data,
    name: data.name.trim(),
  }
  if (props.mode === 'create') {
    await router.post('/data-master/team-names', data)
  } else {
    await router.put(`/data-master/team-names/${props.initialData?.id}`, data)
  }
}

</script>

<template>
  <FormInput :form-inputs="formInputs" @save="handleSave" />
</template>