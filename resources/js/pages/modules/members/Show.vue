<script setup lang="ts">
import PageShow from '@/pages/modules/base-page/PageShow.vue';
import { router } from '@inertiajs/vue3';

const breadcrumbs = [
    { title: 'Management', href: '#' },
    { title: 'Teams', href: '/management/members' },
    { title: 'Members', href: '/management/members' },
    { title: 'Detail Member', href: '/management/members/1' },
];

const fields = [
    { value: 'John Doe', label: 'Nama' },
    { value: '25', label: 'Umur' },
    { value: 'Team A', label: 'Team' },
];

const actionFields = [
    { label: 'Created At', value: '2025-03-20' },
    { label: 'Created By', value: 'Admin' },
    { label: 'Updated At', value: '2025-04-10' },
    { label: 'Updated By', value: 'Super Admin' },
];

const handleEdit = () => {
    router.visit('/management/members/1/edit');
};

const handleDelete = () => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete('/management/members/1');
    }
};
</script>

<template>
    <PageShow
        title="Member"
        :breadcrumbs="breadcrumbs"
        :fields="fields"
        edit-url="/management/members/1/edit"
        :actionFields="actionFields"
        :back-url="'/management/members'"
        :on-edit="handleEdit"
        :on-delete="handleDelete"
    />
</template>
