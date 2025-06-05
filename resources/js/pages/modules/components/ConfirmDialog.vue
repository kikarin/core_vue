<script setup lang="ts">
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'
import { ref } from 'vue'

defineProps<{
  title?: string
  description?: string
  modelValue: boolean
}>()


const emit = defineEmits(['update:modelValue', 'confirm'])

const close = () => emit('update:modelValue', false)
const confirm = () => {
  emit('confirm')
  close()
}
</script>

<template>
  <Dialog :open="modelValue" @update:open="val => emit('update:modelValue', val)">
    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <DialogTitle>{{ title || 'Are you sure?' }}</DialogTitle>
        <p class="text-sm text-muted-foreground">
          {{ description || 'This action cannot be undone.' }}
        </p>
      </DialogHeader>
      <DialogFooter class="mt-4">
        <Button variant="outline" @click="close">Cancel</Button>
        <Button variant="destructive" @click="confirm">Confirm</Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
