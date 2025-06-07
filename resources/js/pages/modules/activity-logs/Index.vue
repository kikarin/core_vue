<script setup lang="ts">
import PageIndex from '@/pages/modules/base-page/PageIndex.vue'
import { router } from '@inertiajs/vue3'

const breadcrumbs = [
    { title: 'Menu & Permissions', href: '#' },
    { title: 'Activity Logs', href: '/menu-permissions/logs' },
]

const columns = [
    { key: 'module', label: 'Module', searchable: true, orderable: true, visible: true },
    { key: 'event', label: 'Event', searchable: true, orderable: true, visible: true },
    { key: 'subject_type', label: 'Subject Type', searchable: true, orderable: true, visible: true },
    { key: 'subject_id', label: 'Subject ID', searchable: true, orderable: true, visible: true },
    { key: 'data', label: 'Data', searchable: false, orderable: false, visible: true },
]

const rows = [
    {
        id: 1,
        module: 'Users',
        event: 'Updated',
        subject_type: 'App\\Models\\User',
        subject_id: 5,
        data: 'Changed name from "John" to "Johnny"',
    },
    {
        id: 2,
        module: 'Roles',
        event: 'Deleted',
        subject_type: 'App\\Models\\Role',
        subject_id: 2,
        data: 'Deleted role: Editor',
    },
]

const actions = (row: any) => [
    {
        label: 'Detail',
        onClick: () => router.visit(`/menu-permissions/logs/${row.id}`),
    },
    {
        label: 'Delete',
        onClick: () => {
            if (confirm(`Are you sure to delete this log entry?`)) {
                router.delete(`/menu-permissions/logs/${row.id}`)
            }
        },
    },
]
</script>

<template>
    <PageIndex title="Activity Logs" :breadcrumbs="breadcrumbs" :columns="columns" :rows="rows" :actions="actions" createUrl="" />
</template>
