<script setup lang="ts">
defineProps<{ title: string }>()
</script>

<template>
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <!-- Title -->
    <h1 class="text-2xl font-semibold tracking-tight">
      {{ title }}
    </h1>

    <!-- Slot untuk tombol -->
    <div class="flex flex-wrap gap-2 items-center">
      <slot />
    </div>
  </div>
</template>
