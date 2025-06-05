<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Link } from '@inertiajs/vue3'

defineProps<{
  title: string
  createUrl: string
  selected: number[]
  onDeleteSelected: () => void
}>()
</script>

<template>
  <div class="flex justify-between items-center flex-wrap gap-2">
    <h1 class="text-2xl font-semibold tracking-tight">
      {{ title }}
    </h1>

    <div class="flex items-center gap-2">
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
  </div>
</template>
