<script setup lang="ts">
import PageShow from '@/pages/modules/base-page/PageShow.vue'

const breadcrumbs = [
    { title: 'Management', href: '#' },
    { title: 'Users', href: '/management/users' },
    { title: 'Detail User', href: '/management/users/show' },
]

const fields = [
    { label: 'Name', value: 'John Doe' },
    { label: 'Email', value: 'john@example.com' },
    { label: 'Role', value: 'Admin' },
    { label: 'Status', value: 'Active' },
    { label: 'Created At', value: '2025-03-20' },
    { label: 'Updated At', value: '2025-03-20' },

]
</script>

<template>
    <PageShow title="Users" :breadcrumbs="breadcrumbs" :fields="fields" />
</template>
