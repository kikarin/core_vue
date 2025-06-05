<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import HeaderShow from './HeaderShow.vue'
import { Head } from '@inertiajs/vue3'
import { type BreadcrumbItem } from '@/types'
import { Info } from 'lucide-vue-next' // pastikan sudah install lucide-vue-next

defineProps<{
  title: string
  breadcrumbs: BreadcrumbItem[]
  fields: { label: string; value: string }[]
}>()
</script>

<template>
  <Head :title="`Detail ${title}`" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-4">
      <HeaderShow :title="`Detail ${title}`" />

      <div class="grid grid-cols-12">
        <div class="col-span-12 md:col-span-8 lg:col-span-6">
          <div class="bg-card border shadow-sm rounded-2xl">
            <div class="border-b px-6 py-4 flex items-center gap-2">
              <Info class="w-4 h-4 text-muted-foreground" />
              <h2 class="text-sm font-semibold text-muted-foreground uppercase tracking-wide">
                Informasi
              </h2>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div
                v-for="field in fields"
                :key="field.label"
                class="space-y-1"
              >
                <div class="text-xs text-muted-foreground">{{ field.label }}</div>
                <div class="text-sm font-semibold text-foreground whitespace-pre-wrap">
                  {{ field.value }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
