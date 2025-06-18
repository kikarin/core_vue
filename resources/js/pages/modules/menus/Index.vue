<script setup lang="ts">
import { useToast } from '@/components/ui/toast/useToast';
import PageIndex from '@/pages/modules/base-page/PageIndex.vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';

const breadcrumbs = [
    { title: 'Menu & Permissions', href: '#' },
    { title: 'Menus', href: '/menu-permissions/menus' },
];

const columns = [
    { key: 'name', label: 'Name', searchable: true, orderable: true, visible: true },
    { key: 'code', label: 'Code', searchable: true, orderable: true, visible: true },
    { key: 'icon', label: 'Icon', searchable: false, orderable: false, visible: true },
    {
        key: 'parent',
        label: 'Parent',
        searchable: false,
        orderable: true,
        visible: true,
        format: (row: any) => {
            return row.parent === '-' ? 'Menu Utama' : row.parent;
        },
    },
    { key: 'url', label: 'URL', searchable: true, orderable: true, visible: true },
];

const selected = ref<number[]>([]);

const pageIndex = ref();

const { toast } = useToast();

const actions = (row: any) => [
    {
        label: 'Detail',
        onClick: () => router.visit(`/menu-permissions/menus/${row.id}`),
    },
    {
        label: 'Edit',
        onClick: () => router.visit(`/menu-permissions/menus/${row.id}/edit`),
    },
    {
        label: 'Delete',
        onClick: () => {
            if (confirm(`Delete menu "${row.name}"?`)) {
                router.delete(`/menu-permissions/menus/${row.id}`);
            }
        },
    },
];

const deleteSelected = async () => {
    if (!selected.value.length) return toast({ title: 'Pilih data yang akan dihapus', variant: 'destructive' });
    try {
        await axios.post('/menu-permissions/menus/destroy-selected', {
            ids: selected.value,
        });
        selected.value = [];
        pageIndex.value.fetchData();
        toast({ title: 'Data berhasil dihapus', variant: 'success' });
    } catch (error) {
        console.error('Gagal menghapus data:', error);
        toast({ title: 'Gagal menghapus data yang dipilih.', variant: 'destructive' });
    }
};

const deleteMenu = async (row: any) => {
    await router.delete(`/menu-permissions/menus/${row.id}`, {
        onSuccess: () => {
            toast({ title: 'Data berhasil dihapus', variant: 'success' });
            pageIndex.value.fetchData();
        },
        onError: () => {
            toast({ title: 'Gagal menghapus data.', variant: 'destructive' });
        },
    });
};
</script>

<template>
    <PageIndex
        title="Menus"
        :breadcrumbs="breadcrumbs"
        :columns="columns"
        :create-url="'/menu-permissions/menus/create'"
        :actions="actions"
        :selected="selected"
        @update:selected="(val) => (selected = val)"
        :on-delete-selected="deleteSelected"
        api-endpoint="/api/menus"
        ref="pageIndex"
        :on-toast="toast"
        :on-delete-row-confirm="deleteMenu"
    />
</template>
