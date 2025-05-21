<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

interface Form {
    id: number;
    form_name: string;
    created_date: string;
}

const props = defineProps<{
    forms: Form[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Form Management',
        href: '/view',
    },
];
</script>

<template>

    <Head title="Form Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">📋 All Created Forms</h1>

                    <div v-if="props.forms.length > 0" class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left border-collapse">
                            <thead
                                class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3 border dark:border-gray-700">SL</th>
                                    <th class="px-4 py-3 border dark:border-gray-700">Form Name</th>
                                    <th class="px-4 py-3 border dark:border-gray-700">Created Date</th>
                                    <th class="px-4 py-3 border dark:border-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(form, index) in props.forms" :key="form.id"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3 border dark:border-gray-700">{{ index + 1 }}</td>
                                    <td
                                        class="px-4 py-3 border dark:border-gray-700 font-medium text-gray-700 dark:text-white">
                                        {{ form.form_name }}
                                    </td>
                                    <td class="px-4 py-3 border dark:border-gray-700">{{ form.created_date }}</td>
                                    <td class="px-4 py-3 border dark:border-gray-700">
                                        <div class="flex items-center gap-3">
                                            <a :href="`/forms/${form.id}`"
                                                class="text-blue-600 dark:text-blue-400 hover:underline text-sm flex items-center gap-1"
                                                title="View">
                                                👁️ <span>View</span>
                                            </a>
                                            <a :href="`/form-edit/${form.id}`"
                                                class="text-green-600 dark:text-green-400 hover:underline text-sm flex items-center gap-1"
                                                title="Edit">
                                                ✏️ <span>Edit</span>
                                            </a>

                                            <a :href="`/form-builder/${form.id}`"
                                                class="text-green-600 dark:text-green-400 hover:underline text-sm flex items-center gap-1"
                                                title="Edit">
                                                📋 <span>Form Builder</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-else class="text-center text-gray-500 dark:text-gray-400 py-12 italic">
                        No forms available.
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
