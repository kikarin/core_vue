<script setup lang="ts">
import PageCreate from '@/pages/modules/base-page/PageCreate.vue';
import Form from './Form.vue';

const breadcrumbs = [
    { title: 'Management', href: '#' },
    { title: 'Users', href: '/management/users' },
    { title: 'Create User', href: '/management/users/create' },
];

</script>

<template>
    <PageCreate title="Create User" :breadcrumbs="breadcrumbs" back-url="/management/users">
        <Form mode="create" />
    </PageCreate>
</template>
