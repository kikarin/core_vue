<script setup lang="ts">
import PageEdit from '@/pages/modules/base-page/PageEdit.vue';
import { ref } from 'vue';
import Form from './Form.vue';

const breadcrumbs = [
    { title: 'Management', href: '#' },
    { title: 'Teams', href: '/management/members' },
    { title: 'Members', href: '/management/members' },
    { title: 'Edit Member', href: '/management/members/1/edit' },
];

const initialData = ref({
    id: 1,
    name: 'John Doe',
    age: 25,
    team_id: 1,
    status: 'active',
});
</script>

<template>
    <PageEdit title="Edit Member" :breadcrumbs="breadcrumbs">
        <Form mode="edit" :initial-data="initialData" />
    </PageEdit>
</template>
