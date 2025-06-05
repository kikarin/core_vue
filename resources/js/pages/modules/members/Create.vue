<script setup lang="ts">
import PageCreate from '@/pages/modules/base-page/PageCreate.vue';
import Form from './Form.vue';

const breadcrumbs = [
    { title: 'Management', href: '#' },
    { title: 'Teams', href: '/management/members' },
    { title: 'Members', href: '/management/members' },
    { title: 'Tambah Member', href: '/management/members/create' },
];
</script>

<template>
    <PageCreate title="Create Member" :breadcrumbs="breadcrumbs" back-url="/management/members">
        <Form mode="create" />
    </PageCreate>
</template>
