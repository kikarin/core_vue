<script setup lang="ts">
import { Button } from '@/components/ui/button'

const emit = defineEmits(['save', 'cancel'])
</script>

<template>
  <div class="flex gap-2 justify-end mt-4">
    <Button type="submit" variant="default" @click.prevent="emit('save')">
      Save
    </Button>
    <Button type="button" variant="outline" @click="emit('cancel')">
      Cancel
    </Button>
  </div>
</template>
