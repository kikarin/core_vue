<script setup lang="ts">
import FormInput from '@/pages/modules/base-page/FormInput.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    mode: 'create' | 'edit';
    initialData?: Record<string, any>;
}>();

const formInputs = [
    {
        name: 'name',
        label: 'Name',
        type: 'text',
        placeholder: 'Enter name',
        required: true,
    },
    {
        name: 'email',
        label: 'Email',
        type: 'email',
        placeholder: 'Enter email',
        required: true,
    },
    {
        name: 'password',
        label: 'Password',
        type: 'password',
        placeholder: 'Enter password',
        required: props.mode === 'create',
    },
    {
        name: 'role',
        label: 'Role',
        type: 'select',
        placeholder: 'Pilih Role',
        required: true,
        options: [
            { value: 1, label: 'Admin' },
            { value: 2, label: 'Editor' },
            { value: 3, label: 'User' },
        ],
    },
    {
        name: 'status',
        label: 'Status',
        type: 'select',
        placeholder: 'Pilih status',
        required: true,
        options: [
            { value: 'active', label: 'Active' },
            { value: 'inactive', label: 'Inactive' },
        ],
    },
];

const handleSave = (data: Record<string, any>) => {
    if (props.mode === 'create') {
        router.post('/management/users', data);
    } else {
        router.put(`/management/users/${props.initialData?.id}`, data);
    }
};

const handleCancel = () => {
    router.visit('/management/users');
};
</script>

<template>
    <FormInput :form-inputs="formInputs" @save="handleSave" @cancel="handleCancel" />
</template>
