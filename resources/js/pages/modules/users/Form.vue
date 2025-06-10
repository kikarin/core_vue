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
        type: 'text'as const,
        placeholder: 'Enter name',
        required: true,
    },
    {
        name: 'email',
        label: 'Email',
        type: 'email'as const,
        placeholder: 'Enter email',
        required: true,
    },
    {
        name: 'password',
        label: 'Password',
        type: 'password'as const,
        placeholder: 'Enter password',
        required: props.mode === 'create',
    },
    {
        name: 'role',
        label: 'Role',
        type: 'select'as const,
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
        type: 'select'as const,
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
        router.post('/users', data);
    } else {
        router.put(`/users/${props.initialData?.id}`, data);
    }
};
</script>

<template>
    <FormInput :form-inputs="formInputs" @save="handleSave"/>
</template>
