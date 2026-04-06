<script setup lang="ts">
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { useIsMacDesktop } from '@/composables/useIsMacDesktop';

defineProps<{
    scrollY?: number;
}>();

const { isMacDesktop } = useIsMacDesktop();

const currentSlide = ref(0);
const totalSlides = 3;
const slideNames = ['Echoes', 'Red Brick Hill', 'Living In Sound'];
let autoSlideTimer: ReturnType<typeof setInterval> | null = null;
const echoesVideo = ref<HTMLVideoElement | null>(null);

function stopAutoSlide() {
    if (autoSlideTimer) {
        clearInterval(autoSlideTimer);
        autoSlideTimer = null;
    }
}

function nextSlide() {
    currentSlide.value = (currentSlide.value + 1) % totalSlides;
    stopAutoSlide();
}

function prevSlide() {
    currentSlide.value = (currentSlide.value - 1 + totalSlides) % totalSlides;
    stopAutoSlide();
}

function goToSlide(i: number) {
    currentSlide.value = i;
    stopAutoSlide();
}

function resetAutoSlide() {
    if (autoSlideTimer) clearInterval(autoSlideTimer);
    autoSlideTimer = setInterval(() => {
        currentSlide.value = (currentSlide.value + 1) % totalSlides;
    }, 8000);
}

function resumeVideos() {
    const video = echoesVideo.value;
    if (video && video.paused) {
        video.play().catch(() => {});
    }
}

watch(currentSlide, (slide) => {
    if (slide === 0) {
        resumeVideos();
    }
});

function handleVisibilityChange() {
    if (document.visibilityState === 'visible') {
        resumeVideos();
        resetAutoSlide();
    } else {
        stopAutoSlide();
    }
}

onMounted(() => {
    resetAutoSlide();
    document.addEventListener('visibilitychange', handleVisibilityChange);
    window.addEventListener('pageshow', resumeVideos);
    window.addEventListener('focus', resumeVideos);
});

onUnmounted(() => {
    if (autoSlideTimer) clearInterval(autoSlideTimer);
    document.removeEventListener('visibilitychange', handleVisibilityChange);
    window.removeEventListener('pageshow', resumeVideos);
    window.removeEventListener('focus', resumeVideos);
});
</script>

<template>
    <section id="home" class="hero-slider">
        <div class="hero-slide-area">
            <div class="hero-track" :style="{ transform: 'translateX(-' + (currentSlide * 100) + '%)' }">

                <!-- SLIDE 1: Echoes — Coming May 1st Promo -->
                <div class="hero-slide hero-slide--echoes" :class="{ 'slide-active': currentSlide === 0 }">
                    <video
                        ref="echoesVideo"
                        class="echoes-bg-video"
                        src="/assets/videos/echoes-album-cover-animation-low.mp4"
                        autoplay
                        loop
                        muted
                        playsinline
                        webkit-playsinline
                    ></video>
                    <div class="echoes-top-grad"></div>
                    <div class="slide-decor">
                        <div class="deco-sq" style="width:380px;height:380px;top:8%;right:5%;--rot:6deg;border-color:rgba(0,0,0,0.06);"></div>
                        <div class="deco-sq" style="width:220px;height:220px;bottom:12%;left:8%;--rot:-4deg;border-color:rgba(0,0,0,0.04);"></div>
                    </div>
                    <div class="hero-content hero-content--album">
                        <div class="echoes-promo-label">New Album</div>
                        <div class="album-cover-wrap">
                            <a href="/album/echoes">
                                <img src="/images/albums/echoes.png" alt="Harry Skoler — Echoes" class="album-cover" loading="eager" fetchpriority="high" />
                            </a>
                        </div>
                        <div class="echoes-release-date">
                            <span class="echoes-release-prefix">Available</span>
                            <span class="echoes-release-day">May 1, 2026</span>
                        </div>
                        <div class="echoes-cta-row">
                            <a href="https://harryskoler1.bandcamp.com/album/echoes" target="_blank" class="cta-btn">Pre-Order on Bandcamp</a>
                            <a href="https://music.apple.com/us/album/echoes/1890977854" target="_blank" class="cta-btn cta-btn-outline">Apple Music</a>
                            <a href="/album/echoes" class="cta-btn cta-btn-outline">Explore the Album</a>
                        </div>
                        <ul class="echoes-icons">
                            <li>
                                <a href="https://harryskoler1.bandcamp.com/album/echoes" target="_blank" aria-label="Bandcamp">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M0 18.75l7.437-13.5H24l-7.438 13.5z"/></svg>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.facebook.com/HarrySkolerJazzClarinet" target="_blank" aria-label="Facebook">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/harryskoler/" target="_blank" aria-label="Instagram">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                </a>
                            </li>
                            <li>
                                <a href="https://music.apple.com/us/album/echoes/1890977854" target="_blank" aria-label="Apple Music">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                                </a>
                            </li>
                        </ul>
                        <span v-if="isMacDesktop" class="apple-music-note">Mac users: opens in Music app. To purchase, select iTunes Store inside Music.</span>
                    </div>
                </div>

                <!-- SLIDE 2: Red Brick Hill -->
                <div class="hero-slide hero-slide--rbh" :class="{ 'slide-active': currentSlide === 1 }">
                    <div class="rbh-bg"></div>
                    <div class="rbh-layout">
                        <div class="rbh-cover-side">
                            <div class="rbh-cover-wrap">
                                <a href="/album/red-brick-hill">
                                    <img src="/images/albums/red-brick-hill-md.png" alt="Red Brick Hill" class="rbh-cover" />
                                </a>
                            </div>
                        </div>
                        <div class="rbh-text-side">
                            <div class="rbh-story">
                                This is a story of loss and redemption, of two ones searching for a way to
                                connect. Both on a path of dark fate. One chose to give up their life. One
                                had their life saved, through capture of a soul living in sound, ultimately
                                embracing self-compassion and self-love. From ascent, to abyss, to a
                                visionary winter's second chance&hellip;
                            </div>
                            <div class="rbh-story-coda">
                                and a red brick hill.
                            </div>
                            <a href="/album/red-brick-hill#album-story" class="rbh-story-link">Read the full story here</a>
                            <div class="rbh-promo">
                                The new recording from Harry Skoler<br />
                                "Red Brick Hill" on <a href="https://www.sunnysiderecords.com/" target="_blank" class="rbh-accent-link">Sunnyside Records</a><br />
                                is now available for order on <a href="https://harryskoler.bandcamp.com/album/red-brick-hill" target="_blank" class="rbh-accent-link">Bandcamp</a><br />
                                and <a href="https://music.apple.com/us/album/red-brick-hill/1755928110" target="_blank" class="rbh-accent-link">Apple Music</a>!
                                <span v-if="isMacDesktop" class="apple-music-note">Mac users: opens in Music app. To purchase, select iTunes Store inside Music.</span>
                            </div>
                            <div class="rbh-catalog">SSC 1748</div>
                            <ul class="rbh-icons">
                                <li>
                                    <a href="https://www.sunnysiderecords.com/" target="_blank" aria-label="Sunnyside Records">
                                        <img src="/images/logos/sunnyside-logo-white.png" alt="Sunnyside Records" class="rbh-sunnyside-logo" />
                                    </a>
                                </li>
                                <li>
                                    <a href="https://harryskoler.bandcamp.com/album/red-brick-hill" target="_blank" aria-label="Bandcamp">
                                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M0 18.75l7.437-13.5H24l-7.438 13.5z"/></svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.facebook.com/HarrySkolerJazzClarinet" target="_blank" aria-label="Facebook">
                                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com/harryskoler/" target="_blank" aria-label="Instagram">
                                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://music.apple.com/us/album/red-brick-hill/1755928110" target="_blank" aria-label="Apple Music">
                                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- SLIDE 3: Living In Sound -->
                <div class="hero-slide hero-slide--lis" :class="{ 'slide-active': currentSlide === 2 }">
                    <div class="lis-bg"></div>
                    <div class="lis-overlay"></div>
                    <div class="lis-content">
                        <div class="lis-promo">
                            The album "Living In Sound: The Music of Charles Mingus" on
                            <a href="https://www.sunnysiderecords.com/" target="_blank" class="lis-accent-link">Sunnyside Records</a>
                            is available for purchase on
                            <a href="https://harryskoler.bandcamp.com/album/living-in-sound-the-music-of-charles-mingus" target="_blank" class="lis-accent-link">Bandcamp</a>
                            and <a href="https://music.apple.com/us/album/living-in-sound-the-music-of-charles-mingus/1614535356" target="_blank" class="lis-accent-link">Apple Music</a>!
                            <span v-if="isMacDesktop" class="apple-music-note">Mac users: opens in Music app. To purchase, select iTunes Store inside Music.</span>
                        </div>
                        <div class="lis-album-wrap">
                            <a href="/album/living-in-sound">
                                <img src="/images/albums/living-in-sound-md.jpg" alt="Living In Sound" class="lis-album-cover" />
                            </a>
                        </div>
                        <ul class="lis-icons">
                            <li>
                                <a href="https://harryskoler.bandcamp.com/album/living-in-sound-the-music-of-charles-mingus" target="_blank" aria-label="Bandcamp">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M0 18.75l7.437-13.5H24l-7.438 13.5z"/></svg>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.facebook.com/HarrySkolerJazzClarinet" target="_blank" aria-label="Facebook">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/harryskoler/" target="_blank" aria-label="Instagram">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                </a>
                            </li>
                            <li>
                                <a href="https://music.apple.com/us/artist/harry-skoler/287182016" target="_blank" aria-label="Apple Music">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                                </a>
                            </li>
                            <li>
                                <a href="https://open.spotify.com/artist/0cVzLgjFM1aMprSetcI2y4" target="_blank" aria-label="Spotify">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/></svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            <button class="slider-arrow slider-arrow--prev" @click="prevSlide" aria-label="Previous slide">
                <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6" /></svg>
            </button>
            <button class="slider-arrow slider-arrow--next" @click="nextSlide" aria-label="Next slide">
                <svg viewBox="0 0 24 24"><polyline points="9 6 15 12 9 18" /></svg>
            </button>

            <div class="slider-dots">
                <button v-for="(slide, i) in slideNames" :key="i"
                        class="slider-dot" :class="{ active: currentSlide === i }"
                        @click="goToSlide(i)" :aria-label="'Go to ' + slide">
                </button>
            </div>
        </div>
    </section>
</template>

<style scoped>
.hero-slider {
    position: relative;
    height: 100vh;
    min-height: 700px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    touch-action: pan-y;
    -ms-touch-action: pan-y;
}

.hero-slide-area {
    position: relative;
    flex: 1;
    min-height: 0;
    overflow: hidden;
}

.hero-track {
    display: flex;
    height: 100%;
    transition: transform 0.8s cubic-bezier(0.22, 1, 0.36, 1);
}

.hero-slide {
    min-width: 100%;
    height: 100%;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.hero-slide--echoes { background: var(--green); }

.echoes-bg-video {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    z-index: 0;
    pointer-events: none;
}

.echoes-top-grad {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 160px;
    background: linear-gradient(180deg, rgba(10,10,10,0.55) 0%, rgba(10,10,10,0.2) 50%, transparent 100%);
    z-index: 15;
    pointer-events: none;
}

.echoes-promo-label {
    font-family: 'DM Sans', sans-serif;
    font-weight: 500;
    font-size: clamp(0.75rem, 1.2vw, 0.9rem);
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--white);
    opacity: 0;
    transform: translateY(15px);
    transition: all 0.7s 0.05s cubic-bezier(0.22, 1, 0.36, 1);
    margin-bottom: 1.4rem;
}

.slide-active .echoes-promo-label {
    opacity: 0.85;
    transform: translateY(0);
}

.echoes-release-date {
    margin-top: 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.3rem;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.7s 0.6s cubic-bezier(0.22, 1, 0.36, 1);
}

.slide-active .echoes-release-date {
    opacity: 1;
    transform: translateY(0);
}

.echoes-release-prefix {
    font-family: 'DM Sans', sans-serif;
    font-weight: 400;
    font-size: clamp(0.85rem, 1.3vw, 1.05rem);
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(245, 240, 235, 0.7);
}

.echoes-release-day {
    font-family: 'DM Sans', sans-serif;
    font-weight: 600;
    font-size: clamp(1.4rem, 3vw, 2rem);
    letter-spacing: 0.04em;
    color: var(--white);
}

/* ==============================
   RED BRICK HILL SLIDE
   ============================== */

.hero-slide--rbh {
    background: #5a7d7c;
}

.rbh-bg {
    position: absolute;
    inset: 0;
    background: url('/images/homepage-slider/rbh/background.png') center/cover no-repeat;
    z-index: 0;
}

.rbh-layout {
    position: relative;
    z-index: 5;
    display: flex;
    align-items: center;
    gap: clamp(3rem, 6vw, 6rem);
    max-width: 1100px;
    width: 90%;
    pointer-events: auto;
}

.rbh-cover-side {
    flex-shrink: 0;
    opacity: 0;
    transform: translateX(-40px) scale(0.95);
    transition: all 0.9s 0.1s cubic-bezier(0.22, 1, 0.36, 1);
}

.slide-active .rbh-cover-side {
    opacity: 1;
    transform: translateX(0) scale(1);
}

.rbh-cover {
    width: clamp(240px, 30vw, 400px);
    height: auto;
    display: block;
    box-shadow: 0 24px 80px rgba(0,0,0,0.45), 0 8px 24px rgba(0,0,0,0.25);
}

.rbh-text-side {
    flex: 1;
    min-width: 0;
    opacity: 0;
    transform: translateX(30px);
    transition: all 0.8s 0.25s cubic-bezier(0.22, 1, 0.36, 1);
}

.slide-active .rbh-text-side {
    opacity: 1;
    transform: translateX(0);
}

.rbh-story {
    font-family: 'DM Sans', sans-serif;
    font-weight: 300;
    font-size: clamp(0.85rem, 1.3vw, 1rem);
    line-height: 1.7;
    color: #e8e2da;
    max-width: 520px;
}

.rbh-story-coda {
    margin-top: 1.4rem;
    font-family: 'DM Sans', sans-serif;
    font-weight: 300;
    font-size: clamp(0.85rem, 1.3vw, 1rem);
    line-height: 1.7;
    color: #e8e2da;
}

.rbh-story-link {
    display: inline-block;
    margin-top: 0.8rem;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.82rem;
    color: #e8a849;
    text-decoration: none;
    transition: color 0.3s;
}
.rbh-story-link:hover { color: #f0c06a; }

.rbh-promo {
    margin-top: 2rem;
    font-family: 'DM Sans', sans-serif;
    font-weight: 600;
    font-size: clamp(0.95rem, 1.4vw, 1.15rem);
    line-height: 1.65;
    color: #fff;
}

.rbh-accent-link {
    color: #e8a849;
    text-decoration: none;
    transition: color 0.3s;
}
.rbh-accent-link:hover { color: #f0c06a; }

.apple-music-note {
    display: block;
    margin-top: 0.6rem;
    font-family: 'DM Sans', sans-serif;
    font-weight: 400;
    font-size: 0.72rem;
    color: rgba(232, 226, 218, 0.55);
    font-style: italic;
    letter-spacing: 0.02em;
}

.rbh-catalog {
    margin-top: 1rem;
    font-family: 'DM Sans', sans-serif;
    font-weight: 400;
    font-size: 0.75rem;
    letter-spacing: 0.06em;
    color: rgba(232, 226, 218, 0.6);
}

.rbh-icons {
    list-style: none;
    display: flex;
    gap: 1.2rem;
    align-items: center;
    margin-top: 1.5rem;
    padding: 0;
}

.rbh-icons a {
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.75);
    transition: color 0.3s, transform 0.3s;
}
.rbh-icons a:hover {
    color: #fff;
    transform: scale(1.12);
}

.rbh-icons svg {
    width: 24px;
    height: 24px;
}

.rbh-sunnyside-logo {
    height: 32px;
    width: auto;
    opacity: 0.8;
    transition: opacity 0.3s;
}
.rbh-icons a:hover .rbh-sunnyside-logo { opacity: 1; }

/* ==============================
   LIVING IN SOUND SLIDE
   ============================== */

.hero-slide--lis {
    background: #1a3a5c;
}

.lis-bg {
    position: absolute;
    inset: 0;
    background: url('/images/homepage-slider/living-in-sound.jpg') center/cover no-repeat;
    z-index: 0;
}

.lis-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        180deg,
        rgba(0,0,0,0.15) 0%,
        rgba(0,0,0,0.05) 30%,
        rgba(0,0,0,0.25) 70%,
        rgba(0,0,0,0.5) 100%
    );
    z-index: 1;
}

.lis-content {
    position: relative;
    z-index: 5;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    pointer-events: auto;
    max-width: 900px;
    width: 90%;
    padding-top: 4rem;
}

.lis-promo {
    font-family: 'DM Sans', sans-serif;
    font-weight: 500;
    font-size: clamp(1.1rem, 2vw, 1.45rem);
    line-height: 1.65;
    color: #fff;
    text-shadow: 0 2px 16px rgba(0,0,0,0.5), 0 1px 4px rgba(0,0,0,0.3);
    opacity: 0;
    transform: translateY(25px);
    transition: all 0.8s 0.15s cubic-bezier(0.22, 1, 0.36, 1);
}

.slide-active .lis-promo {
    opacity: 1;
    transform: translateY(0);
}

.lis-accent-link {
    color: #e8a849;
    text-decoration: none;
    font-weight: 700;
    transition: color 0.3s;
    text-shadow: 0 1px 8px rgba(0,0,0,0.4);
}
.lis-accent-link:hover { color: #f5d080; }

.lis-album-wrap {
    margin-top: 1.8rem;
    opacity: 0;
    transform: translateY(20px) scale(0.95);
    transition: all 0.8s 0.35s cubic-bezier(0.22, 1, 0.36, 1);
}

.slide-active .lis-album-wrap {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.lis-album-cover {
    width: clamp(140px, 16vw, 200px);
    height: auto;
    display: block;
    box-shadow: 0 12px 48px rgba(0,0,0,0.5), 0 4px 16px rgba(0,0,0,0.3);
    transition: transform 0.4s;
}
.lis-album-cover:hover { transform: scale(1.04); }

.lis-icons {
    list-style: none;
    display: flex;
    gap: 1.4rem;
    align-items: center;
    justify-content: center;
    margin-top: 1.6rem;
    padding: 0;
    opacity: 0;
    transform: translateY(15px);
    transition: all 0.7s 0.55s cubic-bezier(0.22, 1, 0.36, 1);
}

.slide-active .lis-icons {
    opacity: 1;
    transform: translateY(0);
}

.lis-icons a {
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.85);
    transition: color 0.3s, transform 0.3s;
    filter: drop-shadow(0 2px 6px rgba(0,0,0,0.4));
}
.lis-icons a:hover {
    color: #fff;
    transform: scale(1.15);
}

.lis-icons svg {
    width: 26px;
    height: 26px;
}

/* ==============================
   ECHOES SLIDE
   ============================== */

.hero-content--album {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0;
}

.album-cover-wrap {
    opacity: 0;
    transform: translateY(30px) scale(0.96);
    transition: all 0.8s 0.1s cubic-bezier(0.22, 1, 0.36, 1);
}

.slide-active .album-cover-wrap {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.album-cover {
    width: min(55vw, 440px);
    height: auto;
    display: block;
    box-shadow: 0 20px 60px rgba(0,0,0,0.4), 0 4px 16px rgba(0,0,0,0.2);
}

.hero-content {
    position: relative;
    z-index: 10;
    text-align: center;
    pointer-events: auto;
}

.echoes-cta-row {
    margin-top: 1.8rem;
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.7s 0.7s cubic-bezier(0.22, 1, 0.36, 1);
}

.slide-active .echoes-cta-row {
    opacity: 1;
    transform: translateY(0);
}

.echoes-cta-row .cta-btn {
    background: var(--white);
    color: var(--black, #0a0a0a);
    border-color: var(--white);
}

.echoes-cta-row .cta-btn:hover {
    background: rgba(255, 255, 255, 0.15);
    color: var(--white);
    border-color: var(--white);
}

.echoes-cta-row .cta-btn-outline {
    background: transparent;
    color: var(--white);
    border-color: rgba(255, 255, 255, 0.5);
}

.echoes-cta-row .cta-btn-outline:hover {
    background: rgba(255, 255, 255, 0.12);
    color: var(--white);
    border-color: var(--white);
}

.echoes-icons {
    list-style: none;
    display: flex;
    gap: 1.2rem;
    align-items: center;
    justify-content: center;
    margin-top: 1.5rem;
    padding: 0;
    opacity: 0;
    transform: translateY(15px);
    transition: all 0.7s 0.85s cubic-bezier(0.22, 1, 0.36, 1);
}

.slide-active .echoes-icons {
    opacity: 1;
    transform: translateY(0);
}

.echoes-icons a {
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.75);
    transition: color 0.3s, transform 0.3s;
}
.echoes-icons a:hover {
    color: #fff;
    transform: scale(1.12);
}

.echoes-icons svg {
    width: 24px;
    height: 24px;
}

/* ==============================
   SHARED SLIDER CONTROLS
   ============================== */

.slider-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 20;
    width: 52px;
    height: 52px;
    border: 1px solid rgba(245,240,235,0.3);
    background: rgba(10,10,10,0.25);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    color: var(--white);
    font-size: 1.3rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.35s;
    border-radius: 0;
    padding: 0;
}

.slider-arrow:hover {
    background: var(--red);
    border-color: var(--red);
    transform: translateY(-50%) scale(1.08);
}

.slider-arrow--prev { left: 2rem; }
.slider-arrow--next { right: 2rem; }

.slider-arrow svg {
    width: 20px;
    height: 20px;
    stroke: currentColor;
    stroke-width: 2;
    fill: none;
}

.slider-dots {
    position: absolute;
    bottom: 1.5rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 20;
    display: flex;
    gap: 1.2rem;
    align-items: center;
}

.slider-dot {
    width: 10px;
    height: 10px;
    border: 1px solid rgba(245,240,235,0.5);
    background: transparent;
    cursor: pointer;
    transition: all 0.4s;
    padding: 0;
    position: relative;
}

.slider-dot::after {
    content: '';
    position: absolute;
    inset: 1px;
    background: var(--white);
    transform: scale(0);
    transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
}

.slider-dot.active { border-color: var(--red-bright); }
.slider-dot.active::after { background: var(--red-bright); transform: scale(1); }
.slider-dot:hover { border-color: var(--white); }

.slide-decor {
    position: absolute;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
}

.slide-decor .deco-sq {
    position: absolute;
    border: 1px solid rgba(245,240,235,0.06);
    transition: all 1.4s cubic-bezier(0.22, 1, 0.36, 1);
    transform: scale(0.8) rotate(var(--rot, 0deg));
}

.slide-active .deco-sq {
    transform: scale(1) rotate(var(--rot, 0deg));
    border-color: rgba(245,240,235,0.1);
}

/* ==============================
   RESPONSIVE
   ============================== */

@media (max-width: 900px) {
    .hero-content { padding: 0 4rem; }
    .rbh-layout {
        flex-direction: column;
        gap: 2rem;
        text-align: center;
        width: 100%;
        padding: 0 4rem;
        box-sizing: border-box;
    }
    .rbh-cover { width: clamp(200px, 50vw, 300px); }
    .rbh-story { max-width: 100%; }
    .rbh-icons { justify-content: center; }
    .lis-content {
        width: 100%;
        padding-left: 4rem;
        padding-right: 4rem;
        box-sizing: border-box;
    }
    .slider-arrow { width: 42px; height: 42px; }
    .slider-arrow--prev { left: 1rem; }
    .slider-arrow--next { right: 1rem; }
}

@media (max-width: 600px) {
    .hero-content { padding: 0 3.5rem; }
    .rbh-layout { gap: 1.5rem; padding: 0 3.5rem; }
    .rbh-cover { width: 220px; }
    .rbh-promo { display: none; }
    .lis-content { padding-left: 3.5rem; padding-right: 3.5rem; }
    .lis-promo { font-size: 1rem; }
    .lis-album-cover { width: 130px; }
    .echoes-cta-row { flex-direction: column; align-items: center; gap: 0.7rem; }
}
</style>
