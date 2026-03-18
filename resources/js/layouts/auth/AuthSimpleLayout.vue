<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="hs-auth relative flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10">
        <div class="hs-auth-grain"></div>

        <div class="relative z-10 w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-5">
                    <Link
                        :href="home()"
                        class="flex flex-col items-center gap-3 font-medium transition-opacity hover:opacity-80"
                    >
                        <AppLogoIcon class="size-10 text-[#c9a96e]" />
                        <span class="font-['Playfair_Display',Georgia,serif] text-lg tracking-wide text-foreground">
                            Harry Skoler
                        </span>
                    </Link>
                    <div class="mx-auto h-px w-12 bg-[#B8282E]"></div>
                    <div class="space-y-2 text-center">
                        <h1 class="font-['Playfair_Display',Georgia,serif] text-xl font-medium text-foreground">
                            {{ title }}
                        </h1>
                        <p class="text-center text-sm text-muted-foreground">
                            {{ description }}
                        </p>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>
.hs-auth-grain {
    position: fixed;
    inset: 0;
    z-index: 1;
    pointer-events: none;
    opacity: 0.03;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
    background-repeat: repeat;
    background-size: 200px;
}
</style>
