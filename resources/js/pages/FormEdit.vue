<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';


const props = defineProps<{
    id: number;
    configuration: object;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Edit Form Configuration',
        href: '/view',
    },
];
// Store editable JSON string
const jsonText = ref(JSON.stringify(props.configuration, null, 4));

// Sync props if changed
watch(() => props.configuration, (newVal) => {
    jsonText.value = JSON.stringify(newVal, null, 4);
});

// Form submission handler
const submitForm = () => {
    try {
        const parsed = JSON.parse(jsonText.value);

        router.put(`/form-edit/${props.id}`, parsed, {
            preserveScroll: true,
            onSuccess: () => alert('Form updated successfully!'),
            onError: () => alert('There was a validation error.'),
        });
    } catch (e) {
        alert('Invalid JSON format. Please fix before submitting.');
    }
};
</script>

<template>

    <Head title="Edit Form Configuration" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">📋 Edit Form Configuration (ID: {{
                        props.id }})</h1>
                    <div class="overflow-x-auto">
                        <textarea v-model="jsonText" rows="20"
                            class="w-full border border-gray-300 p-3 font-mono text-sm rounded-lg shadow-sm focus:ring focus:ring-blue-100 focus:outline-none"></textarea>

                        <div class="mt-4">
                            <button @click="submitForm"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
