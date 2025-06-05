<script setup lang="ts">
defineProps<{ title: string }>()
</script>

<template>
  <h1 class="text-xl font-medium text-muted-foreground">{{ title }}</h1>
</template>
