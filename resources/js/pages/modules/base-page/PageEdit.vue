<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { router } from '@inertiajs/vue3'
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import { Button } from '@/components/ui/button'

const props = defineProps<{
  title: string
  breadcrumbs: BreadcrumbItem[]
  backUrl?: string
}>()

const emit = defineEmits(['cancel'])

const handleCancel = () => {
  emit('cancel')
  if (props.backUrl) {
    router.visit(props.backUrl)
  } else {
    router.visit('/')
  }
}

</script>

<template>
  <!-- <Head :title="`Edit ${title}`" /> -->
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-4">
      <!-- <HeaderIndex :title="`Edit ${title}`" :back-url="backUrl" /> -->
      <div class="grid grid-cols-1 lg:grid-cols-12">
        <div class="col-span-1 lg:col-span-7 lg:col-start-1">
          <Card class="w-full">
            <CardHeader class="flex items-center justify-between">
              <CardTitle class="text-2xl">Edit {{ title }}</CardTitle>
              <Button variant="secondary" @click="handleCancel">
                ← Back
              </Button>
            </CardHeader>
            <CardContent>
              <slot />
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
