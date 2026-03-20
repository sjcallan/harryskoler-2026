<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import {
    Newspaper,
    Star,
    Radio,
    FileText,
    MessageSquareQuote,
    ImageIcon,
    Plus,
    Clock,
    HardDrive,
    BarChart3,
} from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard() },
];

interface Stats {
    news: number;
    reviews: number;
    radio_airplays: number;
    press_events: number;
    quotes: number;
    gallery_images: number;
}

interface RecentItem {
    type: string;
    title: string;
    updated_at: string;
}

interface StorageInfo {
    total_files: number;
    total_size_bytes: number;
    total_size_formatted: string;
}

const stats = ref<Stats | null>(null);
const recentItems = ref<RecentItem[]>([]);
const storageInfo = ref<StorageInfo | null>(null);
const loadingStats = ref(true);
const loadingRecent = ref(true);
const loadingStorage = ref(true);

const statCards = [
    { key: 'news' as keyof Stats, label: 'News', icon: Newspaper, href: '/news' },
    { key: 'reviews' as keyof Stats, label: 'Reviews', icon: Star, href: '/reviews' },
    { key: 'radio_airplays' as keyof Stats, label: 'Radio Airplay', icon: Radio, href: '/radio-airplay' },
    { key: 'press_events' as keyof Stats, label: 'Press Events', icon: FileText, href: '/press' },
    { key: 'quotes' as keyof Stats, label: 'Quotes', icon: MessageSquareQuote, href: '/quotes' },
    { key: 'gallery_images' as keyof Stats, label: 'Gallery', icon: ImageIcon, href: '/gallery' },
];

const quickActions = [
    { label: 'Add News', href: '/news', icon: Newspaper },
    { label: 'Add Review', href: '/reviews', icon: Star },
    { label: 'Upload Photos', href: '/gallery', icon: ImageIcon },
    { label: 'Add Press', href: '/press', icon: FileText },
];

function typeIcon(type: string) {
    const map: Record<string, any> = {
        'News': Newspaper,
        'Review': Star,
        'Radio Airplay': Radio,
        'Press': FileText,
        'Gallery': ImageIcon,
        'Quote': MessageSquareQuote,
    };
    return map[type] ?? Clock;
}

function timeAgo(isoDate: string): string {
    const now = Date.now();
    const then = new Date(isoDate).getTime();
    const diffMs = now - then;
    const mins = Math.floor(diffMs / 60000);
    if (mins < 1) return 'Just now';
    if (mins < 60) return `${mins}m ago`;
    const hrs = Math.floor(mins / 60);
    if (hrs < 24) return `${hrs}h ago`;
    const days = Math.floor(hrs / 24);
    if (days < 30) return `${days}d ago`;
    return new Date(isoDate).toLocaleDateString();
}

async function fetchStats() {
    try {
        const res = await fetch('/api/dashboard/stats');
        stats.value = await res.json();
    } catch { /* silent */ } finally {
        loadingStats.value = false;
    }
}

async function fetchRecent() {
    try {
        const res = await fetch('/api/dashboard/recent');
        recentItems.value = await res.json();
    } catch { /* silent */ } finally {
        loadingRecent.value = false;
    }
}

async function fetchStorage() {
    try {
        const res = await fetch('/api/dashboard/storage');
        storageInfo.value = await res.json();
    } catch { /* silent */ } finally {
        loadingStorage.value = false;
    }
}

onMounted(() => {
    fetchStats();
    fetchRecent();
    fetchStorage();
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4 md:p-6">

            <!-- Content Overview -->
            <Card>
                <CardHeader class="pb-3">
                    <CardTitle class="flex items-center gap-2 text-lg">
                        <BarChart3 class="size-5" />
                        Content Overview
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div v-if="loadingStats" class="flex justify-center py-8">
                        <Spinner class="text-muted-foreground size-5" />
                    </div>
                    <div v-else class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-6">
                        <Link
                            v-for="card in statCards"
                            :key="card.key"
                            :href="card.href"
                            class="flex flex-col items-center gap-2 rounded-lg border p-4 transition-colors hover:bg-muted/50"
                        >
                            <component :is="card.icon" class="text-primary size-5" />
                            <span class="text-2xl font-bold tabular-nums">{{ stats?.[card.key] ?? 0 }}</span>
                            <span class="text-muted-foreground text-xs">{{ card.label }}</span>
                        </Link>
                    </div>
                </CardContent>
            </Card>

            <div class="grid gap-6 md:grid-cols-2">
                <!-- Recent Activity -->
                <Card>
                    <CardHeader class="pb-3">
                        <CardTitle class="flex items-center gap-2 text-lg">
                            <Clock class="size-5" />
                            Recent Activity
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="loadingRecent" class="flex justify-center py-8">
                            <Spinner class="text-muted-foreground size-5" />
                        </div>
                        <div v-else-if="recentItems.length === 0" class="text-muted-foreground py-8 text-center text-sm">
                            No recent activity.
                        </div>
                        <div v-else class="divide-y">
                            <div v-for="(item, i) in recentItems" :key="i" class="flex items-center gap-3 py-3">
                                <div class="bg-muted flex size-8 shrink-0 items-center justify-center rounded-full">
                                    <component :is="typeIcon(item.type)" class="text-muted-foreground size-3.5" />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium">{{ item.title }}</p>
                                    <p class="text-muted-foreground text-xs">{{ item.type }}</p>
                                </div>
                                <span class="text-muted-foreground shrink-0 text-xs">{{ timeAgo(item.updated_at) }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <div class="flex flex-col gap-6">
                    <!-- Quick Actions -->
                    <Card>
                        <CardHeader class="pb-3">
                            <CardTitle class="flex items-center gap-2 text-lg">
                                <Plus class="size-5" />
                                Quick Actions
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="grid grid-cols-2 gap-3">
                                <Button
                                    v-for="action in quickActions"
                                    :key="action.label"
                                    variant="outline"
                                    as-child
                                    class="h-auto flex-col gap-2 py-4"
                                >
                                    <Link :href="action.href">
                                        <component :is="action.icon" class="size-5" />
                                        <span class="text-xs">{{ action.label }}</span>
                                    </Link>
                                </Button>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Storage Info -->
                    <Card>
                        <CardHeader class="pb-3">
                            <CardTitle class="flex items-center gap-2 text-lg">
                                <HardDrive class="size-5" />
                                Media Storage
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div v-if="loadingStorage" class="flex justify-center py-8">
                                <Spinner class="text-muted-foreground size-5" />
                            </div>
                            <div v-else-if="storageInfo" class="flex items-center gap-6">
                                <div class="text-center">
                                    <p class="text-2xl font-bold">{{ storageInfo.total_size_formatted }}</p>
                                    <p class="text-muted-foreground text-xs">Total Size</p>
                                </div>
                                <div class="bg-border h-10 w-px"></div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold tabular-nums">{{ storageInfo.total_files }}</p>
                                    <p class="text-muted-foreground text-xs">Files</p>
                                </div>
                            </div>
                            <div v-else class="text-muted-foreground py-4 text-center text-sm">
                                No storage data available.
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
