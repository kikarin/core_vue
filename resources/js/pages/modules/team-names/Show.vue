<script setup lang="ts">
import PageShow from '@/pages/modules/base-page/PageShow.vue';

const breadcrumbs = [
    { title: 'Data Master', href: '/data-master' },
    { title: 'Nama Team', href: '/data-master/team-names' },
    { title: 'Detail Team', href: '/data-master/team-names/1' },
];

const fields = [
    { value: 'Team A', label: 'Nama Team' },
    { label: 'Created At', value: '2025-03-20' },
    { label: 'Updated At', value: '2025-03-20' },
];
</script>

<template>
    <PageShow title="Detail Team" :breadcrumbs="breadcrumbs" :fields="fields" edit-url="/data-master/team-names/1/edit" />
</template>
