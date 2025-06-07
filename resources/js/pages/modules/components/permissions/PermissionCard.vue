<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import {
  DropdownMenu,
  DropdownMenuTrigger,
  DropdownMenuContent,
  DropdownMenuItem
} from '@/components/ui/dropdown-menu'
import { Button } from '@/components/ui/button'
import { MoreVertical, Lock } from 'lucide-vue-next'

const props = defineProps<{
  category: {
    id: number
    name: string
    permissions: { id: number; name: string }[]
  }
}>()

const goTo = (path: string) => router.visit(path)

const handleDeleteCategory = () => {
  if (window.confirm(`Are you sure you want to delete category "${props.category.name}"?`)) {
    router.delete(`/menu-permissions/permissions/${props.category.id}`)
  }
}

const handleDeletePermission = (permission: { id: number; name: string }) => {
  if (window.confirm(`Are you sure you want to delete permission "${permission.name}"?`)) {
    router.delete(`/menu-permissions/permissions/delete-permission/${permission.id}`)
  }
}
</script>

<template>
  <div class="bg-card border rounded-xl shadow-sm overflow-hidden">
    <!-- Category Header -->
    <div class="flex items-center justify-between px-4 py-3 border-b">
      <h3 class="text-sm font-semibold tracking-tight">
        {{ category.name }}
      </h3>

      <DropdownMenu>
        <DropdownMenuTrigger as-child>
          <Button variant="ghost" size="icon" class="h-6 w-6">
            <MoreVertical class="w-4 h-4" />
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="w-48">
          <DropdownMenuItem @click="goTo(`/menu-permissions/permissions/create-permission?category_id=${category.id}`)">
            Add Permission
          </DropdownMenuItem>
          <DropdownMenuItem @click="goTo(`/menu-permissions/permissions/category/${category.id}/edit`)">
            Edit Category
          </DropdownMenuItem>
          <DropdownMenuItem @click="goTo(`/menu-permissions/permissions/category/${category.id}`)">
            View Detail
          </DropdownMenuItem>
          <DropdownMenuItem class="text-red-500" @click="handleDeleteCategory">
            Delete Category
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
    </div>

    <!-- Permissions List -->
    <ul class="divide-y">
      <li
        v-for="perm in category.permissions"
        :key="perm.id"
        class="flex items-center justify-between px-4 py-2 hover:bg-muted/50"
      >
        <div class="flex items-center gap-2 text-sm text-foreground">
          <Lock class="w-4 h-4 text-muted-foreground" />
          <span>{{ perm.name }}</span>
        </div>

        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <Button variant="ghost" size="icon" class="h-6 w-6">
              <MoreVertical class="w-4 h-4" />
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end" class="w-40">
            <DropdownMenuItem @click="goTo(`/menu-permissions/permissions/${perm.id}/edit-permission`)">
              ✏️ Edit
            </DropdownMenuItem>
            <DropdownMenuItem @click="goTo(`/menu-permissions/permissions/${perm.id}/detail`)">
              👁️ View Detail
            </DropdownMenuItem>
            <DropdownMenuItem class="text-red-500" @click="handleDeletePermission(perm)">
              🗑️ Delete
            </DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </li>
    </ul>
  </div>
</template>
