<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import RadioAirplayManager from '@/components/dashboard/RadioAirplayManager.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
    },
    {
        title: 'Radio Airplay',
        href: '/admin/radio-airplay',
    },
];
</script>

<template>
    <Head title="Radio Airplay" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4 md:p-6">
            <RadioAirplayManager />
        </div>
    </AppLayout>
</template>
