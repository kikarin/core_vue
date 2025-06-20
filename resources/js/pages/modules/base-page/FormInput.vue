<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useToast } from '@/components/ui/toast/useToast';
import { useForm } from '@inertiajs/vue3';
import * as LucideIcons from 'lucide-vue-next';
import { Eye, EyeOff, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { Alert, AlertDescription, AlertTitle } from '../../../components/ui/alert';
import ButtonsForm from './ButtonsForm.vue';

const props = defineProps<{
    formInputs: {
        name: string;
        label: string;
        type: 'text' | 'email' | 'password' | 'textarea' | 'select' | 'multi-select' | 'number' | 'radio' | 'icon' | 'checkbox';
        placeholder?: string;
        required?: boolean;
        help?: string;
        options?: { value: string | number; label: string }[];
        showPassword?: { value: boolean };
    }[];
    initialData?: Record<string, any>;
}>();

const emit = defineEmits(['save', 'cancel']);

// Inisialisasi form menggunakan useForm dengan data awal
const form = useForm(props.initialData || {});

// State untuk multi-select dropdown
const multiSelectOpen = ref<Record<string, boolean>>({});

// Memisahkan icon options ke computed property
const iconOptions = computed(() => {
    return Object.keys(LucideIcons)
        .filter((key) => key !== 'default')
        .map((key) => ({
            value: key,
            label: key,
            icon: key,
        }));
});

const formErrors = ref<Record<string, string>>({});

const { toast } = useToast();

const handleSubmit = (e: Event) => {
    e.preventDefault();
    formErrors.value = {}; // reset error sebelum submit

    // Cek required field
    let isValid = true;
    const localErrors: Record<string, string> = {};
    props.formInputs.forEach((input) => {
        if (input.required && !form[input.name]) {
            isValid = false;
            localErrors[input.name] = `${input.label} wajib diisi`;
        }
    });

    if (!isValid) {
        toast({ title: 'Data is not valid', variant: 'destructive' });
        formErrors.value = localErrors; // <-- tampilkan alert error juga
        return;
    }

    emit('save', form, setFormErrors);
};

function setFormErrors(errors: Record<string, string>) {
    formErrors.value = errors;
}

const togglePassword = (field: { value: boolean }) => {
    field.value = !field.value;
};

// Multi-select functions
const toggleMultiSelect = (fieldName: string) => {
    multiSelectOpen.value[fieldName] = !multiSelectOpen.value[fieldName];
};

const selectMultiOption = (fieldName: string, value: string | number) => {
    const currentValues = form[fieldName] || [];
    if (currentValues.includes(value)) {
        form[fieldName] = currentValues.filter((v: any) => v !== value);
    } else {
        form[fieldName] = [...currentValues, value];
    }
};

const removeMultiOption = (fieldName: string, value: string | number) => {
    const currentValues = form[fieldName] || [];
    form[fieldName] = currentValues.filter((v: any) => v !== value);
};

const getSelectedLabels = (fieldName: string, options: { value: string | number; label: string }[]) => {
    const selectedValues = form[fieldName] || [];
    return options.filter((option) => selectedValues.includes(option.value));
};
</script>

<template>
    <div class="w-full">
        <!-- ALERT ERROR -->
        <Alert v-if="Object.keys(formErrors).length" variant="destructive" class="mb-4 shadow-none hover:shadow-none">
            <AlertCircle class="h-4 w-4" />
            <AlertTitle>Error</AlertTitle>
            <AlertDescription>
                <ul class="list-disc pl-5">
                    <li v-for="(msg, field) in formErrors" :key="field">{{ msg }}</li>
                </ul>
            </AlertDescription>
        </Alert>
        <form @submit="handleSubmit" class="space-y-6">
            <div v-for="input in formInputs" :key="input.name" class="grid grid-cols-1 items-start gap-2 md:grid-cols-12 md:gap-4">
                <label class="col-span-full text-sm font-medium md:col-span-4 md:pt-2">{{ input.label }}</label>
                <div class="col-span-full md:col-span-8">
                    <!-- MULTI-SELECT -->
                    <div v-if="input.type === 'multi-select'" class="relative">
                        <div
                            @click="toggleMultiSelect(input.name)"
                            class="border-input bg-background flex min-h-[40px] w-full cursor-pointer flex-wrap items-center gap-1 rounded-md border px-3 py-2 text-sm"
                            :class="{ 'border-ring ring-ring ring-2 ring-offset-2': multiSelectOpen[input.name] }"
                        >
                            <!-- Selected badges -->
                            <div v-if="form[input.name] && form[input.name].length > 0" class="flex flex-wrap gap-1">
                                <Badge
                                    v-for="selected in getSelectedLabels(input.name, input.options || [])"
                                    :key="selected.value"
                                    variant="secondary"
                                    class="flex items-center gap-1 text-xs"
                                >
                                    {{ selected.label }}
                                    <X
                                        class="hover:text-destructive h-3 w-3 cursor-pointer"
                                        @click.stop="removeMultiOption(input.name, selected.value)"
                                    />
                                </Badge>
                            </div>
                            <!-- Placeholder -->
                            <div v-else class="text-muted-foreground">
                                {{ input.placeholder }}
                            </div>
                        </div>

                        <!-- Dropdown options -->
                        <div
                            v-if="multiSelectOpen[input.name]"
                            class="bg-popover absolute top-full right-0 left-0 z-50 mt-1 max-h-60 overflow-auto rounded-md border p-1 shadow-lg"
                        >
                            <div
                                v-for="option in input.options"
                                :key="option.value"
                                @click="selectMultiOption(input.name, option.value)"
                                class="hover:bg-accent hover:text-accent-foreground flex cursor-pointer items-center space-x-2 rounded-sm px-2 py-1.5 text-sm"
                            >
                                <Checkbox
                                    :model-value="(form[input.name] || []).includes(option.value)"
                                    @update:modelValue="
                                        (checked) => {
                                            const selected = form[input.name] || [];
                                            if (checked) {
                                                form[input.name] = [...selected, option.value];
                                            } else {
                                                form[input.name] = selected.filter((v: any) => v !== option.value);
                                            }
                                        }
                                    "
                                />
                                <span>{{ option.label }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- ICON SELECT -->
                    <Select
                        v-else-if="input.type === 'icon'"
                        :required="input.required"
                        :model-value="form[input.name]"
                        @update:modelValue="(val) => (form[input.name] = val)"
                    >
                        <SelectTrigger class="w-full">
                            <SelectValue :placeholder="input.placeholder">
                                <template v-if="form[input.name]">
                                    <component :is="LucideIcons[form[input.name] as keyof typeof LucideIcons]" class="mr-2 inline-block h-4 w-4" />
                                    {{ form[input.name] }}
                                </template>
                            </SelectValue>
                        </SelectTrigger>
                        <SelectContent class="max-h-[300px]">
                            <div class="grid grid-cols-4 gap-2 p-2">
                                <SelectItem
                                    v-for="option in iconOptions"
                                    :key="option.value"
                                    :value="option.value"
                                    class="hover:bg-accent flex cursor-pointer items-center gap-2 rounded-md p-2"
                                >
                                    <component :is="LucideIcons[option.icon as keyof typeof LucideIcons]" class="h-4 w-4" />
                                    <span class="text-sm">{{ option.label }}</span>
                                </SelectItem>
                            </div>
                        </SelectContent>
                    </Select>

                    <!-- TEXTAREA -->
                    <textarea
                        v-else-if="input.type === 'textarea'"
                        v-model="form[input.name]"
                        :placeholder="input.placeholder"
                        :required="input.required"
                        class="border-input bg-background text-foreground placeholder:text-muted-foreground focus-visible:ring-ring min-h-[100px] w-full rounded-md border px-3 py-2 text-sm shadow-sm focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                    />

                    <!-- SELECT -->
                    <Select
                        v-else-if="input.type === 'select'"
                        :required="input.required"
                        :model-value="form[input.name]"
                        @update:modelValue="(val) => (form[input.name] = val)"
                    >
                        <SelectTrigger class="w-full">
                            <SelectValue :placeholder="input.placeholder" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="option in input.options" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>

                    <!-- RADIO -->
                    <div v-else-if="input.type === 'radio'" class="flex gap-4">
                        <label v-for="option in input.options" :key="option.value" class="inline-flex cursor-pointer items-center space-x-2">
                            <input
                                type="radio"
                                :name="input.name"
                                :value="option.value"
                                v-model="form[input.name]"
                                :required="input.required"
                                class="form-radio text-primary border-input focus:ring-ring"
                            />
                            <span class="text-sm">{{ option.label }}</span>
                        </label>
                    </div>

                    <!-- CHECKBOX GROUP -->
                    <div v-else-if="input.type === 'checkbox' && Array.isArray(input.options)" class="flex flex-col gap-2">
                        <div v-for="option in input.options" :key="option.value" class="flex items-center space-x-2">
                            <Checkbox
                                :id="`${input.name}-${option.value}`"
                                :value="option.value"
                                :checked="Array.isArray(form[input.name]) && form[input.name].includes(option.value)"
                                @update:checked="
                                    (checked: boolean) => {
                                        const selected = form[input.name] || [];
                                        if (checked) {
                                            form[input.name] = [...selected, option.value];
                                        } else {
                                            form[input.name] = selected.filter((v: any) => v !== option.value);
                                        }
                                    }
                                "
                            />
                            <label :for="`${input.name}-${option.value}`" class="text-sm">
                                {{ option.label }}
                            </label>
                        </div>
                    </div>

                    <!-- PASSWORD WITH TOGGLE -->
                    <div v-else-if="input.type === 'password'" class="relative">
                        <Input
                            v-model="form[input.name]"
                            :type="input.showPassword?.value ? 'text' : 'password'"
                            :placeholder="input.placeholder"
                            :required="input.required"
                        />
                        <Button
                            type="button"
                            variant="ghost"
                            size="sm"
                            class="absolute top-1/2 right-2 -translate-y-1/2"
                            @click="togglePassword(input.showPassword!)"
                        >
                            <Eye v-if="!input.showPassword?.value" class="h-4 w-4" />
                            <EyeOff v-else class="h-4 w-4" />
                        </Button>
                    </div>

                    <!-- DEFAULT INPUT (text, email, number) -->
                    <Input v-else v-model="form[input.name]" :type="input.type" :placeholder="input.placeholder" :required="input.required" />

                    <!-- Help text -->
                    <p v-if="input.help" class="text-muted-foreground mt-1 text-sm">
                        {{ input.help }}
                    </p>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="grid grid-cols-1 items-center md:grid-cols-12">
                <div class="hidden md:col-span-3 md:block"></div>
                <div class="col-span-full md:col-span-9">
                    <ButtonsForm @save="handleSubmit" @cancel="emit('cancel')" />
                </div>
            </div>
        </form>
    </div>

    <!-- Overlay untuk menutup multi-select dropdown -->
    <div v-if="Object.values(multiSelectOpen).some(Boolean)" @click="multiSelectOpen = {}" class="fixed inset-0 z-40"></div>
</template>
