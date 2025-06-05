<script setup lang="ts">
import PageShow from '@/pages/modules/base-page/PageShow.vue';

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
    { label: 'Created At', value: '2025-03-20' },
    { label: 'Updated At', value: '2025-03-20' },
];
</script>

<template>
    <PageShow title="Detail Member" :breadcrumbs="breadcrumbs" :fields="fields" edit-url="/management/members/1/edit" />
</template>
