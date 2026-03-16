<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import ReviewsManager from '@/components/dashboard/ReviewsManager.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
    },
    {
        title: 'Reviews',
        href: '/reviews',
    },
];
</script>

<template>
    <Head title="Reviews" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4 md:p-6">
            <ReviewsManager />
        </div>
    </AppLayout>
</template>
