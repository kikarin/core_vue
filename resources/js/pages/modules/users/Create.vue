<script setup lang="ts">
import PageCreate from '@/pages/modules/base-page/PageCreate.vue';
import Form from './Form.vue';

const breadcrumbs = [
    { title: 'Users', href: '/users' },
    { title: 'Create User', href: '/users/create' },
];

</script>

<template>
    <PageCreate title="Create User" :breadcrumbs="breadcrumbs" back-url="/users">
        <Form mode="create" />
    </PageCreate>
</template>
