<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Eye, X } from 'lucide-vue-next';

const page = usePage();

const isPreview = computed(() => Boolean(page.props.preview_mode));
const isAuthenticated = computed(() => Boolean((page.props.auth as { user?: unknown } | undefined)?.user));

const bannerLabel = computed(() => {
    if (isAuthenticated.value) {
        return 'Staff preview — draft content is visible to you.';
    }
    return 'Preview mode active — you can see unpublished content in this session.';
});

function exitPreview() {
    const url = new URL(window.location.href);
    url.searchParams.set('preview', 'off');
    window.location.href = url.toString();
}
</script>

<template>
    <div
        v-if="isPreview || isAuthenticated"
        class="sticky top-0 z-100 flex items-center justify-between gap-4 border-b border-amber-400/60 bg-amber-500/95 px-4 py-2 text-xs font-medium text-amber-950 backdrop-blur supports-backdrop-filter:bg-amber-500/85"
    >
        <div class="flex items-center gap-2">
            <Eye class="size-4" />
            <span>{{ bannerLabel }}</span>
        </div>
        <button
            v-if="isPreview && !isAuthenticated"
            type="button"
            class="inline-flex items-center gap-1 rounded-full bg-amber-950/10 px-3 py-0.5 text-amber-950 transition hover:bg-amber-950/20"
            @click="exitPreview"
        >
            Exit preview
            <X class="size-3" />
        </button>
    </div>
</template>
