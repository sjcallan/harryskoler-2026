<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useScrollTracking } from '@/composables/useScrollTracking';
import SiteNav from '@/components/home/SiteNav.vue';
import HeroSlider from '@/components/home/HeroSlider.vue';
import FloatingCanvas from '@/components/home/FloatingCanvas.vue';
import ListenStrip from '@/components/home/ListenStrip.vue';
import AboutSection from '@/components/home/AboutSection.vue';
import MusicSection from '@/components/home/MusicSection.vue';
import ReviewsSection from '@/components/home/ReviewsSection.vue';
import NewsSection from '@/components/home/NewsSection.vue';
import RadioSection from '@/components/home/RadioSection.vue';
import GallerySection from '@/components/home/GallerySection.vue';
import EventsSection from '@/components/home/EventsSection.vue';
import SiteFooter from '@/components/home/SiteFooter.vue';
import '../../css/home.css';

const navLinks = [
    { id: 'home', label: 'Home' },
    { id: 'about', label: 'About' },
    { id: 'music', label: 'Music' },
    { id: 'reviews', label: 'Reviews' },
    { id: 'news', label: 'News' },
    { id: 'radio', label: 'Radio' },
    { id: 'gallery', label: 'Gallery' },
    { id: 'events', label: 'Events' },
];

const { scrollY, activeSection, scrollToSection } = useScrollTracking(navLinks);
</script>

<template>
    <Head title="Harry Skoler — Echoes">
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous" />
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:wght@300;400;500;600&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet" />
    </Head>

    <div class="hs-page">
        <div class="grain-overlay"></div>

        <FloatingCanvas :scroll-y="scrollY" />

        <SiteNav
            :scroll-y="scrollY"
            :active-section="activeSection"
            :nav-links="navLinks"
            :scroll-to-section="scrollToSection"
        />

        <HeroSlider :scroll-y="scrollY" />
        <ListenStrip />
        <AboutSection />
        <MusicSection />
        <ReviewsSection />
        <NewsSection />
        <RadioSection />
        <GallerySection />
        <EventsSection />

        <SiteFooter
            :nav-links="navLinks"
            :scroll-to-section="scrollToSection"
        />
    </div>
</template>
