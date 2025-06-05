<script setup lang="ts">
import { Button } from '@/components/ui/button'

const emit = defineEmits(['save'])
</script>

<template>
  <div class="flex gap-2 justify-end mt-4">
    <Button type="submit" variant="default" @click.prevent="emit('save')">
      Save
    </Button>
  </div>
</template>
