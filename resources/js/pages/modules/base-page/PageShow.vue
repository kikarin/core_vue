<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import HeaderShow from './HeaderShow.vue'
import { Head, router } from '@inertiajs/vue3'
import { type BreadcrumbItem } from '@/types'
import { Info, Clock, Pencil, Trash2, ArrowLeft } from 'lucide-vue-next'
import { ref } from 'vue'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'
import { useToast } from '@/components/ui/toast/useToast'

const { toast } = useToast()

const props = defineProps<{
  title: string
  breadcrumbs: BreadcrumbItem[]
  fields: { label: string; value: string; className?: string }[]
  actionFields?: { label: string; value: string }[]
  backUrl?: string
  onDelete?: () => void
  onEdit?: () => void
}>()

const showDeleteDialog = ref(false)

const handleDelete = () => {
  showDeleteDialog.value = true
}

const confirmDelete = () => {
  if (props.onDelete) {
    props.onDelete()
  }
  showDeleteDialog.value = false
}
</script>

<template>
  <Head :title="`Detail ${title}`" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-4">
      <!-- Header & Action Buttons -->
      <HeaderShow :title="`Detail ${title}`">
        <!-- Only show if onEdit is provided -->
        <button
          v-if="onEdit"
          class="inline-flex items-center gap-1 px-3 py-2 text-sm border rounded-md hover:bg-muted"
          @click="onEdit"
        >
          <Pencil class="w-4 h-4" />
          Edit
        </button>

        <!-- Always show Delete -->
        <button
          v-if="onDelete"
          class="inline-flex items-center gap-1 px-3 py-2 text-sm border rounded-md hover:bg-muted"
          @click="handleDelete"
        >
          <Trash2 class="w-4 h-4 text-red-500" />
          Delete
        </button>

        <!-- Back -->
        <button
          class="inline-flex items-center gap-1 px-3 py-2 text-sm border rounded-md hover:bg-muted"
          @click="() => router.visit(backUrl || '#')"
        >
          <ArrowLeft class="w-4 h-4" />
          Back
        </button>
      </HeaderShow>


      <div class="grid grid-cols-12 gap-6">
        <!-- Information Panel -->
        <div class="col-span-12 md:col-span-8">
          <div class="bg-card border shadow-sm rounded-2xl">
            <div class="border-b px-6 py-4 flex items-center gap-2">
              <Info class="w-4 h-4 text-muted-foreground" />
              <h2 class="text-sm font-semibold text-muted-foreground uppercase tracking-wide">
                Information
              </h2>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div
                v-for="field in fields"
                :key="field.label"
                class="space-y-1"
              >
                <div class="text-xs text-muted-foreground">{{ field.label }}</div>
                <div :class="['text-sm font-semibold text-foreground whitespace-pre-wrap', field.className]">
                  {{ field.value }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Time Panel -->
        <div class="col-span-12 md:col-span-4">
          <div class="bg-card border shadow-sm rounded-2xl">
            <div class="border-b px-6 py-4 flex items-center gap-2">
              <Clock class="w-4 h-4 text-muted-foreground" />
              <h2 class="text-sm font-semibold text-muted-foreground uppercase tracking-wide">
                Action Time
              </h2>
            </div>

            <div class="p-6 grid grid-cols-1 gap-3">
              <div
                v-for="field in actionFields"
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

    <!-- Delete Confirmation Dialog -->
    <Dialog v-model:open="showDeleteDialog">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Hapus data ini!</DialogTitle>
          <DialogDescription>
            Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
          </DialogDescription>
        </DialogHeader>
        <DialogFooter>
          <Button variant="outline" @click="showDeleteDialog = false">Batal</Button>
          <Button variant="destructive" @click="confirmDelete">Hapus</Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>
