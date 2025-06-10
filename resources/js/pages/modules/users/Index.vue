<script setup lang="ts">
import PageIndex from '@/pages/modules/base-page/PageIndex.vue'
import { router } from '@inertiajs/vue3'

const breadcrumbs = [
    { title: 'Users', href: '/users' },
]

const columns = [
  {
    key: 'name',
    label: 'Name',
    searchable: true,
    orderable: true,
    visible: true,
  },
  {
    key: 'email',
    label: 'Email',
    searchable: true,
    orderable: true,
    visible: true,
  },
  {
    key: 'role',
    label: 'Role',
    searchable: false,
    orderable: false,
    visible: true,
  },
  {
    key: 'status',
    label: 'Status',
    className: 'text-green-500',
    searchable: false,
    orderable: true,
    visible: true,
  },
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
            router.visit(`/users/${row.id}`)
        },
    },
    {
        label: 'Edit',
        onClick: () => {
            router.visit(`/users/${row.id}/edit`)
        },
    },
    {
        label: 'Delete',
        onClick: () => {
            if (confirm(`Are you sure you want to delete ${row.name}?`)) {
                router.delete(`/users/${row.id}`)
            }
        },
    },
]

</script>

<template>
    
    <PageIndex title="Users" :breadcrumbs="breadcrumbs" :columns="columns" :rows="rows" :actions="actions"
        create-url="/users/create" />

</template>
