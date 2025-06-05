<script setup lang="ts">
import PageShow from '@/pages/modules/base-page/PageShow.vue';
import { router } from '@inertiajs/vue3';

const breadcrumbs = [
    { title: 'Data Master', href: '/data-master' },
    { title: 'Nama Team', href: '/data-master/team-names' },
    { title: 'Detail Team', href: '/data-master/team-names/1' },
];

const fields = [{ value: 'Team A', label: 'Nama Team' }];

const actionFields = [
    { label: 'Created At', value: '2025-03-20' },
    { label: 'Created By', value: 'Admin' },
    { label: 'Updated At', value: '2025-04-10' },
    { label: 'Updated By', value: 'Super Admin' },
];

const handleEdit = () => {
    router.visit('/data-master/team-names/1/edit');
};

const handleDelete = () => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete('/data-master/team-names/1');
    }
};
</script>

<template>
    <PageShow
        title="Team"
        :breadcrumbs="breadcrumbs"
        :fields="fields"
        edit-url="/data-master/team-names/1/edit"
        :actionFields="actionFields"
        :back-url="'/data-master/team-names'"
        :on-edit="handleEdit"
        :on-delete="handleDelete"
    />
</template>
