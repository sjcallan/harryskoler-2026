<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import SiteNav from '@/components/home/SiteNav.vue';
import SiteFooter from '@/components/home/SiteFooter.vue';
import ScrollToTop from '@/components/home/ScrollToTop.vue';
import '../../css/home.css';

const navLinks = [
    { id: 'home', label: 'Home' },
    { id: 'radio', label: 'Radio Airplay' },
    { id: 'news', label: 'News' },
    { id: 'reviews', label: 'Reviews' },
    { id: 'press', label: 'Press' },
    { id: 'music', label: 'Music' },
    { id: 'gallery', label: 'Gallery' },
    { id: 'about', label: 'About' },
    { id: 'events', label: 'Events' },
];

const scrollY = ref(0);

function onScroll() {
    scrollY.value = window.scrollY;
}

onMounted(() => {
    window.addEventListener('scroll', onScroll, { passive: true });
});

onUnmounted(() => {
    window.removeEventListener('scroll', onScroll);
});
</script>

<template>
    <div class="hs-page">
        <div class="grain-overlay"></div>

        <SiteNav
            :scroll-y="scrollY"
            :nav-links="navLinks"
            :subpage="true"
        />

        <slot />

        <SiteFooter
            :nav-links="navLinks"
            :subpage="true"
        />

        <ScrollToTop />
    </div>
</template>
