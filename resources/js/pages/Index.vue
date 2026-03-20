<script setup lang="ts">
import { computed } from 'vue';
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
import PressSection from '@/components/home/PressSection.vue';
import GallerySection from '@/components/home/GallerySection.vue';
import EventsSection from '@/components/home/EventsSection.vue';
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

const themePalette = ['section-red-accent', 'section-deep', 'section-dark', 'section-green'];

const sectionThemes = computed(() => {
    const map: Record<string, string> = {};
    navLinks
        .filter(l => l.id !== 'home')
        .forEach((link, i) => {
            map[link.id] = themePalette[i % themePalette.length];
        });
    return map;
});

const { scrollY, activeSection, scrollToSection } = useScrollTracking(navLinks);
</script>

<template>
    <Head title="Harry Skoler — Jazz Clarinetist">
        <meta name="description" content="Harry Skoler — Grammy-nominated jazz clarinetist, Berklee College of Music professor. Explore albums including Echoes, Red Brick Hill, and Living In Sound." head-key="description" />
        <meta property="og:title" content="Harry Skoler — Jazz Clarinetist" head-key="og:title" />
        <meta property="og:description" content="Grammy-nominated jazz clarinetist, Berklee College of Music professor. Albums, reviews, press, radio airplay, and events." head-key="og:description" />
        <link rel="preload" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:wght@300;400;500;600&family=Instrument+Serif:ital@0;1&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'" />
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
        <RadioSection :theme="sectionThemes.radio" />
        <NewsSection :theme="sectionThemes.news" />
        <ReviewsSection :theme="sectionThemes.reviews" />
        <PressSection :theme="sectionThemes.press" />
        <MusicSection :theme="sectionThemes.music" />
        <GallerySection :theme="sectionThemes.gallery" />
        <AboutSection :theme="sectionThemes.about" />
        <EventsSection :theme="sectionThemes.events" />

        <SiteFooter
            :nav-links="navLinks"
            :scroll-to-section="scrollToSection"
        />

        <ScrollToTop />
    </div>
</template>
