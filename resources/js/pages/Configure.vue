<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Configuration from '../components/application/Configuration.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Configure JSON',
        href: '/configure',
    },
];
</script>

<template>
    <Head title="Configure JSON" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                 <Configuration />
            </div>
        </div>
    </AppLayout>
</template>
