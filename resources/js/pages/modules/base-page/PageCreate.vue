<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import HeaderIndex from './HeaderIndex.vue'
import { type BreadcrumbItem } from '@/types'

defineProps<{
  title: string
  breadcrumbs: BreadcrumbItem[]
  backUrl?: string
}>()

</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-4">
      <HeaderIndex :title="title" :back-url="backUrl" />
      <slot />
    </div>
  </AppLayout>
</template>
