<script setup lang="ts">
import { Button } from '@/components/ui/button'

const emit = defineEmits(['save', 'cancel'])

const handleSave = (e: Event) => {
  e.preventDefault()
  emit('save', e)
}
</script>

<template>
  <div class="flex gap-2 justify-end mt-4">
    <Button type="submit" variant="default" @click="handleSave">
      Save
    </Button>
  </div>
</template>
