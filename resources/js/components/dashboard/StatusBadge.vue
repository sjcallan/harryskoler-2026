<script setup lang="ts">
import { computed } from 'vue';
import { FileEdit, CheckCircle2, Archive } from 'lucide-vue-next';
import type { ContentStatus } from '@/composables/useContentStatus';

const props = defineProps<{
    status: ContentStatus | string | null | undefined;
    compact?: boolean;
}>();

const resolved = computed<ContentStatus>(() => {
    const raw = (props.status ?? 'draft') as string;
    if (raw === 'published' || raw === 'archived' || raw === 'draft') {
        return raw;
    }
    return 'draft';
});

const classes = computed(() => {
    switch (resolved.value) {
        case 'published':
            return 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/60 dark:text-emerald-300';
        case 'archived':
            return 'border-zinc-200 bg-zinc-100 text-zinc-600 dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-400';
        default:
            return 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-900 dark:bg-amber-950/60 dark:text-amber-300';
    }
});

const label = computed(() => {
    switch (resolved.value) {
        case 'published':
            return 'Published';
        case 'archived':
            return 'Archived';
        default:
            return 'Draft';
    }
});
</script>

<template>
    <span
        :class="[
            'inline-flex items-center gap-1 rounded-full border px-2 py-0.5 font-medium uppercase tracking-wide',
            compact ? 'text-[10px]' : 'text-xs',
            classes,
        ]"
    >
        <FileEdit v-if="resolved === 'draft'" class="size-3" />
        <CheckCircle2 v-else-if="resolved === 'published'" class="size-3" />
        <Archive v-else class="size-3" />
        {{ label }}
    </span>
</template>
