<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import HeaderIndex from './HeaderIndex.vue'
import { type BreadcrumbItem } from '@/types'
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { router } from '@inertiajs/vue3'

defineProps<{
  title: string
  breadcrumbs: BreadcrumbItem[]
  backUrl?: string
}>()

</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-4">
      <!-- <HeaderIndex :title="title" :back-url="backUrl" /> -->
      <div class="grid grid-cols-12">
        <div class="col-span-7">
          <Card class="w-full">
            <CardHeader class="flex items-center justify-between">
              <CardTitle class="text-2xl">{{ title }}</CardTitle>
              <Button variant="outline" @click="backUrl ? router.visit(backUrl) : router.visit('/')">
                Cancel
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
