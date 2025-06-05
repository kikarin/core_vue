`
<script setup lang="ts">
import PageIndex from '@/pages/modules/base-page/PageIndex.vue'
import { router } from '@inertiajs/vue3'

const breadcrumbs = [
    { title: 'Management', href: '#' },
    { title: 'Users', href: '/management/users' },
]

const columns = [
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'role', label: 'Role' },
    { key: 'status', label: 'Status' },
]

const rows = [
    { id: 1, name: 'John Doe', email: 'john@example.com', role: 'Admin', status: 'Active' },
    { id: 2, name: 'Jane Smith', email: 'jane@example.com', role: 'Editor', status: 'Active' },
    { id: 3, name: 'Alex Lee', email: 'alex@example.com', role: 'User', status: 'Active' },
]

const actions = (row: any) => [
    {
        label: 'Detail',
        onClick: () => {
            router.visit(`/management/users/${row.id}`)
        },
    },
    {
        label: 'Edit',
        onClick: () => {
            router.visit(`/management/users/${row.id}/edit`)
        },
    },
    {
        label: 'Delete',
        onClick: () => {
            if (confirm(`Are you sure you want to delete ${row.name}?`)) {
                router.delete(`/management/users/${row.id}`)
            }
        },
    },
]
</script>

<template>
    <PageIndex title="Users" :breadcrumbs="breadcrumbs" :columns="columns" :rows="rows" :actions="actions"
        create-url="/management/users/create" />

</template>
`