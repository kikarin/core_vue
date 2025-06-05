<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import HeaderIndex from './HeaderIndex.vue'
import { Head } from '@inertiajs/vue3'
import { type BreadcrumbItem } from '@/types'

defineProps<{
  title: string
  breadcrumbs: BreadcrumbItem[]
}>()
</script>

<template>
  <Head :title="`Edit ${title}`" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-4">
      <HeaderIndex :title="`Edit ${title}`" />
      <slot />
    </div>
  </AppLayout>
</template>
