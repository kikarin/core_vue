<script setup lang="ts">
import PageShow from '@/pages/modules/base-page/PageShow.vue';
import { router } from '@inertiajs/vue3';

const breadcrumbs = [
    { title: 'Management', href: '#' },
    { title: 'Teams', href: '/management/teams' },
    { title: 'List Team', href: '/management/teams' },
    { title: 'Team Detail', href: '/management/teams/show' },
];

const fields = [
    { label: 'Team Name', value: 'Team A' },
    { label: 'Description', value: 'Main development team for core features' },
    { label: 'Members', value: '1 members' },
];

const actionFields = [
    { label: 'Created At', value: '2025-03-20' },
    { label: 'Created By', value: 'Admin' },
    { label: 'Updated At', value: '2025-04-10' },
    { label: 'Updated By', value: 'Super Admin' },
];

const handleEdit = () => {
    router.visit('/management/teams/1/edit');
};

const handleDelete = () => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete('/management/teams/1');
    }
};
</script>

<template>
    <PageShow
        title="Team"
        :breadcrumbs="breadcrumbs"
        :fields="fields"
        edit-url="/management/teams/edit"
        :actionFields="actionFields"
        :back-url="'/management/teams'"
        :on-edit="handleEdit"
        :on-delete="handleDelete"
    />
</template>
