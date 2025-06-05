<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Link } from '@inertiajs/vue3'
import { ref, watchEffect } from 'vue'

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
      v-if="selected.length"
      variant="destructive"
      size="sm"
      @click="onDeleteSelected"
    >
      Delete Selected ({{ selected.length }})
    </Button>
  </div>
</template>
