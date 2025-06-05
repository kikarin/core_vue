<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'

const props = defineProps<{
  title: string
  backUrl?: string
}>()

const goBack = () => {
  if (props.backUrl) {
    router.visit(props.backUrl)
  } else if (document.referrer) {
    router.visit(document.referrer)
  } else {
    router.visit('/')
  }
}
</script>

<template>
  <div class="grid grid-cols-12">
    <div class="col-span-6">
      <div class="bg-card rounded-lg border shadow-sm px-6 py-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold tracking-tight">
          {{ title }}
        </h1>
        <Button variant="secondary" @click="goBack">← Back</Button>
      </div>
    </div>
  </div>
</template>
