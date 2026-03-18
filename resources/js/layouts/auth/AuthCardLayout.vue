<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="hs-auth relative flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10">
        <div class="hs-card-grain"></div>

        <div class="relative z-10 flex w-full max-w-md flex-col gap-6">
            <Link
                :href="home()"
                class="flex flex-col items-center gap-2 self-center font-medium transition-opacity hover:opacity-80"
            >
                <AppLogoIcon class="size-10 text-[#c9a96e]" />
                <span class="font-['Playfair_Display',Georgia,serif] text-lg tracking-wide text-foreground">
                    Harry Skoler
                </span>
            </Link>

            <div class="flex flex-col gap-6">
                <Card class="rounded-xl border-[#2a2520] bg-card">
                    <CardHeader class="px-10 pt-8 pb-0 text-center">
                        <CardTitle class="font-['Playfair_Display',Georgia,serif] text-xl text-card-foreground">
                            {{ title }}
                        </CardTitle>
                        <CardDescription>
                            {{ description }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="px-10 py-8">
                        <slot />
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>

<style scoped>
.hs-card-grain {
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
