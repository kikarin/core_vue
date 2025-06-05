<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Link } from '@inertiajs/vue3'

const props = defineProps<{
  createUrl: string
  selected: number[]
  onDeleteSelected: () => void
}>()
</script>

<template>
  <div class="flex flex-wrap justify-between items-center gap-2">
    <Link :href="createUrl">
      <Button variant="default">+ Create</Button>
    </Link>

    <Button
      variant="destructive"
      size="sm"
      :disabled="selected.length === 0"
      @click="onDeleteSelected"
    >
      Delete Selected ({{ selected.length }})
    </Button>
  </div>
</template>
