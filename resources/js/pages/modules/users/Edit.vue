<script setup lang="ts">
import PageEdit from '@/pages/modules/base-page/PageEdit.vue';
import Form from './Form.vue';

const breadcrumbs = [
    { title: 'Management', href: '#' },
    { title: 'Users', href: '/management/users' },
    { title: 'Edit User', href: '/management/users/edit' },
];

// Ini nanti akan diganti dengan data dari API
const initialData = {
    id: 1,
    name: 'John Doe',
    email: 'john@example.com',
};
</script>

<template>
    <PageEdit title="Edit User" :breadcrumbs="breadcrumbs">
        <Form mode="edit" :initial-data="initialData" />
    </PageEdit>
</template>
