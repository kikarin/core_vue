<script setup lang="ts">
import FormInput from '@/pages/modules/base-page/FormInput.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Eye, EyeOff } from 'lucide-vue-next';

const props = defineProps<{
    mode: 'create' | 'edit';
    initialData?: Record<string, any>;
    roles: Record<number, string>;
}>();

const roleOptions = Object.entries(props.roles).map(([id, name]) => ({
    value: Number(id),
    label: name,
}));

// Inisialisasi formData dengan data awal jika mode edit
const formData = ref<Record<string, any>>({
    name: props.initialData?.name || '',
    email: props.initialData?.email || '',
    password: '',
    password_confirmation: '',
    no_hp: props.initialData?.no_hp || '',
    role: props.initialData?.current_role_id || '',
    is_active: props.initialData?.is_active || 1,
    id: props.initialData?.id || undefined
});

// State untuk toggle password visibility
const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

const formInputs = [
    {
        name: 'name',
        label: 'Name',
        type: 'text' as const,
        placeholder: 'Enter name',
        required: true,
    },
    {
        name: 'email',
        label: 'Email',
        type: 'email' as const,
        placeholder: 'Enter email',
        required: true,
    },
    {
        name: 'password',
        label: 'Password',
        type: 'password' as const,
        placeholder: props.mode === 'create' ? 'Enter password' : 'Kosongkan jika tidak ingin mengubah password',
        required: props.mode === 'create',
        help: 'Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka',
        showPassword: showPassword,
    },
    {
        name: 'password_confirmation',
        label: 'Konfirmasi Password',
        type: 'password' as const,
        placeholder: 'Konfirmasi password',
        required: props.mode === 'create',
        help: 'Password harus sama dengan password di atas',
        showPassword: showPasswordConfirmation,
    },
    {
        name: 'no_hp',
        label: 'No. HP',
        type: 'text' as const,
        placeholder: 'Enter phone number',
        required: true,
    },
    {
        name: 'role',
        label: 'Role',
        type: 'select' as const,
        placeholder: 'Pilih Role',
        required: true,
        options: roleOptions,
    },
    {
        name: 'is_active',
        label: 'Status',
        type: 'select' as const,
        placeholder: 'Pilih status',
        required: true,
        options: [
            { value: 1, label: 'Active' },
            { value: 0, label: 'Inactive' },
        ],
    },
];

const handleSave = (data: Record<string, any>) => {
    console.log('Form data:', data) // Debug log
    
    // Validasi password
    if (props.mode === 'create') {
        if (data.password !== data.password_confirmation) {
            alert('Password dan konfirmasi password harus sama');
            return;
        }
    } else {
        // Jika mode edit dan password diisi
        if (data.password) {
            if (data.password !== data.password_confirmation) {
                alert('Password dan konfirmasi password harus sama');
                return;
            }
        } else {
            // Jika password kosong, hapus field password
            delete data.password;
            delete data.password_confirmation;
        }
    }
    
    // Pastikan role ada dan dalam format yang benar
    if (!data.role) {
        alert('Role harus dipilih');
        return;
    }
    
    // Konversi role menjadi role_id array untuk backend
    const roleId = Number(data.role);
    if (isNaN(roleId)) {
        alert('Role tidak valid');
        return;
    }
    
    const formData = {
        ...data,
        role_id: [roleId],
        role: roleId // Tetap kirim role untuk validasi
    };

    // Tambahkan id jika mode edit
    if (props.mode === 'edit' && props.initialData?.id) {
        formData.id = props.initialData.id;
    }
    
    if (props.mode === 'create') {
        router.post('/users', formData, {
            onSuccess: () => {
                router.visit('/users')
            },
            onError: (errors) => {
                console.error('Error:', errors)
                // Tampilkan error ke user
                if (errors.password) {
                    alert('Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka')
                }
                if (errors.role) {
                    alert('Role harus dipilih')
                }
            }
        });
    } else {
        router.put(`/users/${props.initialData?.id}`, formData, {
            onSuccess: () => {
                router.visit('/users')
            },
            onError: (errors) => {
                console.error('Error:', errors)
                // Tampilkan error ke user
                if (errors.password) {
                    alert('Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka')
                }
                if (errors.role) {
                    alert('Role harus dipilih')
                }
            }
        });
    }
};
</script>

<template>
    <FormInput :form-inputs="formInputs" :initial-data="formData" @save="handleSave"/>
</template>
