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
    <div class="hs-auth relative grid h-dvh flex-col items-center justify-center bg-background px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <div class="relative hidden h-full flex-col p-10 text-[#f5f0eb] lg:flex">
            <div class="absolute inset-0 bg-[#133d33]"></div>
            <div class="hs-split-grain"></div>
            <Link
                :href="home()"
                class="relative z-20 flex items-center gap-3 text-lg font-medium transition-opacity hover:opacity-80"
            >
                <AppLogoIcon class="size-9 text-[#c9a96e]" />
                <span class="font-['Playfair_Display',Georgia,serif] tracking-wide">
                    Harry Skoler
                </span>
            </Link>
            <div class="relative z-20 mt-auto">
                <p class="text-sm leading-relaxed text-[#e8e0d6]/70">
                    Award-winning clarinetist &amp; recording artist
                </p>
            </div>
        </div>
        <div class="lg:p-8">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                <div class="flex flex-col space-y-2 text-center">
                    <h1 class="font-['Playfair_Display',Georgia,serif] text-xl font-medium tracking-tight text-foreground" v-if="title">
                        {{ title }}
                    </h1>
                    <p class="text-sm text-muted-foreground" v-if="description">
                        {{ description }}
                    </p>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>
.hs-split-grain {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
    opacity: 0.04;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
    background-repeat: repeat;
    background-size: 200px;
}
</style>
