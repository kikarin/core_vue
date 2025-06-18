<script setup lang="ts">
import { useToast } from '@/components/ui/toast/useToast';
import PageShow from '@/pages/modules/base-page/PageShow.vue';
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';

const { toast } = useToast();

const props = defineProps<{
    fields: Array<{ label: string; value: string }>;
    actionFields: Array<{ label: string; value: string }>;
    backUrl?: string;
}>();

const breadcrumbs = [
    { title: 'Menu & Permissions', href: '#' },
    { title: 'Activity Logs', href: '/menu-permissions/logs' },
    { title: 'Detail Log', href: '#' },
];

// Format fields untuk tampilan yang lebih baik
const formattedFields = computed(() => {
    return props.fields.map((field) => {
        let formattedValue = field.value;
        let className = '';

        if (field.label === 'Data') {
            try {
                const parsed = JSON.parse(field.value);
                if (typeof parsed === 'object') {
                    formattedValue = JSON.stringify(parsed, null, 2);
                    className = 'font-mono text-xs bg-muted p-3 rounded border overflow-x-auto whitespace-pre-wrap';
                }
            } catch {
                // Jika bukan JSON, tampilkan as is
                formattedValue = field.value;
                className = 'font-mono text-xs bg-muted p-3 rounded border';
            }
        }

        if (field.label === 'Subject Type') {
            formattedValue = field.value.replace('App\\Models\\', '');
        }

        if (field.label === 'Subject ID') {
            className = 'font-mono';
        }

        return {
            ...field,
            value: formattedValue,
            className: className,
        };
    });
});

const handleDelete = () => {
    // Extract log ID from the URL or props if available
    const logId = window.location.pathname.split('/').pop();
    if (logId) {
        router.delete(`/menu-permissions/logs/${logId}`, {
            onSuccess: () => {
                toast({ title: 'Log berhasil dihapus', variant: 'success' });
                router.visit('/menu-permissions/logs');
            },
            onError: () => {
                toast({ title: 'Gagal menghapus log', variant: 'destructive' });
            },
        });
    }
};
</script>

<template>
    <PageShow
        title="Activity Log"
        :breadcrumbs="breadcrumbs"
        :fields="formattedFields"
        :action-fields="props.actionFields"
        :back-url="props.backUrl || '/menu-permissions/logs'"
        :on-delete="handleDelete"
    />
</template>
