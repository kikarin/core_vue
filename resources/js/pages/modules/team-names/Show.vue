<script setup lang="ts">
import PageShow from '@/pages/modules/base-page/PageShow.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
  item: { id: number, nama: string, created_at?: string, created_by_user?: { name: string }, updated_at?: string, updated_by_user?: { name: string } }
}>()

const breadcrumbs = [
    { title: 'Data Master', href: '/data-master' },
    { title: 'Nama Team', href: '/data-master/team-names' },
    { title: 'Detail Team', href: `/data-master/team-names/${props.item.id}` },
];

const fields = [{ value: props.item.nama, label: 'Nama Team' }];

const actionFields = [
    { label: 'Created At', value: props.item.created_at || '-' },
    { label: 'Created By', value: props.item.created_by_user?.name || '-' },
    { label: 'Updated At', value: props.item.updated_at || '-' },
    { label: 'Updated By', value: props.item.updated_by_user?.name || '-' },
];

const handleEdit = () => {
    router.visit(`/data-master/team-names/${props.item.id}/edit`);
};

const handleDelete = () => {
    if (confirm('Are you sure you want to delete this team?')) {
        router.delete(`/data-master/team-names/${props.item.id}`);
    }
};
</script>

<template>
    <PageShow
        title="Team"
        :breadcrumbs="breadcrumbs"
        :fields="fields"
        edit-url="/data-master/team-names/${props.item.id}/edit"
        :actionFields="actionFields"
        :back-url="'/data-master/team-names'"
        :on-edit="handleEdit"
        :on-delete="handleDelete"
    />
</template>
