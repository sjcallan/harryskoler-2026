<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch, nextTick } from 'vue';

withDefaults(defineProps<{ theme?: string }>(), { theme: 'section-green' });

interface GalleryImage {
    src: string;
    full: string;
    caption: string;
    credit?: string;
}

const galleryImages: GalleryImage[] = [
    {
        src: '/assets/images/media/thumbnails/wayland/harry-skoler-wayland-ma-1.jpg',
        full: '/assets/images/media/full/wayland/harry-skoler-wayland-ma-1.jpg',
        caption: 'Wayland, MA 2023',
        credit: 'Photo by Jean-Pierre Ducondi',
    },
    {
        src: '/assets/images/about/side-image-3.jpg',
        full: '/assets/images/about/side-image-3.jpg',
        caption: 'Harry Skoler',
    },
    {
        src: '/assets/images/media/thumbnails/wayland/harry-skoler-wayland-ma-2.jpg',
        full: '/assets/images/media/full/wayland/harry-skoler-wayland-ma-2.jpg',
        caption: 'Wayland, MA 2023',
        credit: 'Photo by Jean-Pierre Ducondi',
    },
    {
        src: '/assets/images/cartoon.jpg',
        full: '/assets/images/cartoon.jpg',
        caption: "How Harry's life was influenced by Charles Mingus",
        credit: 'Artwork by Dave Chisholm',
    },
    {
        src: '/assets/images/media/thumbnails/wayland/harry-skoler-wayland-ma-3.jpg',
        full: '/assets/images/media/full/wayland/harry-skoler-wayland-ma-3.jpg',
        caption: 'Wayland, MA 2023',
        credit: 'Photo by Jean-Pierre Ducondi',
    },
    {
        src: '/assets/images/events/arts-wayland-4-6-25.png',
        full: '/assets/images/events/arts-wayland-4-6-25.png',
        caption: 'Arts Wayland 2025',
    },
    {
        src: '/assets/images/about/side-image-1.jpg',
        full: '/assets/images/about/side-image-1.jpg',
        caption: 'Harry Skoler',
    },
];

const lightboxOpen = ref(false);
const activeIndex = ref(0);
const imageLoaded = ref(false);
const thumbStrip = ref<HTMLElement | null>(null);

function openLightbox(index: number) {
    activeIndex.value = index;
    imageLoaded.value = false;
    lightboxOpen.value = true;
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    lightboxOpen.value = false;
    document.body.style.overflow = '';
}

function goTo(index: number) {
    if (index < 0 || index >= galleryImages.length) return;
    imageLoaded.value = false;
    activeIndex.value = index;
    scrollThumbIntoView(index);
}

function prev() {
    goTo(activeIndex.value <= 0 ? galleryImages.length - 1 : activeIndex.value - 1);
}

function next() {
    goTo(activeIndex.value >= galleryImages.length - 1 ? 0 : activeIndex.value + 1);
}

function onMainImageLoad() {
    imageLoaded.value = true;
}

function scrollThumbIntoView(index: number) {
    nextTick(() => {
        const strip = thumbStrip.value;
        if (!strip) return;
        const thumb = strip.children[index] as HTMLElement;
        if (!thumb) return;
        thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    });
}

function onKeydown(e: KeyboardEvent) {
    if (!lightboxOpen.value) return;
    if (e.key === 'Escape') closeLightbox();
    else if (e.key === 'ArrowLeft') prev();
    else if (e.key === 'ArrowRight') next();
}

onMounted(() => window.addEventListener('keydown', onKeydown));
onUnmounted(() => window.removeEventListener('keydown', onKeydown));

watch(lightboxOpen, (open) => {
    if (open) scrollThumbIntoView(activeIndex.value);
});
</script>

<template>
    <section id="gallery" :class="['section', theme]">
        <div class="section-header reveal">
            <span class="section-label">Visual</span>
            <h2 class="section-title">Gallery</h2>
            <div class="section-divider"></div>
        </div>
        <div class="gallery-masonry">
            <div
                class="gallery-item reveal"
                v-for="(img, i) in galleryImages"
                :key="i"
                @click="openLightbox(i)"
            >
                <img :src="img.src" :alt="img.caption" loading="lazy">
                <div class="gallery-caption">{{ img.caption }}</div>
                <div class="gallery-zoom-hint">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        <line x1="11" y1="8" x2="11" y2="14"/>
                        <line x1="8" y1="11" x2="14" y2="11"/>
                    </svg>
                </div>
            </div>
        </div>

        <Teleport to="body">
            <Transition name="lightbox-fade">
                <div v-if="lightboxOpen" class="lightbox-overlay" @click.self="closeLightbox">
                    <button class="lightbox-close" @click="closeLightbox" aria-label="Close lightbox">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>

                    <button class="lightbox-nav lightbox-prev" @click="prev" aria-label="Previous image">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"/>
                        </svg>
                    </button>

                    <div class="lightbox-main">
                        <div class="lightbox-image-wrap">
                            <Transition name="lightbox-slide" mode="out-in">
                                <img
                                    :key="activeIndex"
                                    :src="galleryImages[activeIndex].full"
                                    :alt="galleryImages[activeIndex].caption"
                                    class="lightbox-image"
                                    :class="{ 'is-loaded': imageLoaded }"
                                    @load="onMainImageLoad"
                                />
                            </Transition>
                            <div v-if="!imageLoaded" class="lightbox-spinner">
                                <div class="spinner-ring"></div>
                            </div>
                        </div>

                        <div class="lightbox-meta">
                            <div class="lightbox-meta-text">
                                <span class="lightbox-counter">{{ activeIndex + 1 }} / {{ galleryImages.length }}</span>
                                <h3 class="lightbox-caption">{{ galleryImages[activeIndex].caption }}</h3>
                                <p v-if="galleryImages[activeIndex].credit" class="lightbox-credit">{{ galleryImages[activeIndex].credit }}</p>
                            </div>
                            <a
                                :href="galleryImages[activeIndex].full"
                                target="_blank"
                                rel="noopener"
                                class="lightbox-view-original"
                            >
                                View Original
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                    <polyline points="15 3 21 3 21 9"/>
                                    <line x1="10" y1="14" x2="21" y2="3"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <button class="lightbox-nav lightbox-next" @click="next" aria-label="Next image">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </button>

                    <div class="lightbox-thumbs">
                        <div class="lightbox-thumb-strip" ref="thumbStrip">
                            <button
                                v-for="(img, i) in galleryImages"
                                :key="i"
                                class="lightbox-thumb"
                                :class="{ 'is-active': i === activeIndex }"
                                @click="goTo(i)"
                                :aria-label="'Go to image ' + (i + 1)"
                            >
                                <img :src="img.src" :alt="img.caption" />
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </section>
</template>

<style scoped>
/* ── Gallery Masonry Grid ── */
.gallery-masonry {
    max-width: 1200px;
    margin: 0 auto;
    columns: 3;
    column-gap: 1.5rem;
}

.gallery-item {
    break-inside: avoid;
    margin-bottom: 1.5rem;
    overflow: hidden;
    position: relative;
    cursor: pointer;
}

.gallery-item img {
    width: 100%;
    display: block;
    transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
    filter: grayscale(30%);
}

.gallery-item:hover img {
    transform: scale(1.05);
    filter: grayscale(0%);
}

.gallery-item .gallery-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 1.5rem;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    opacity: 0;
    transition: opacity 0.4s;
    font-size: 0.8rem;
    color: var(--cream);
}

.gallery-item:hover .gallery-caption { opacity: 1; }

.gallery-zoom-hint {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    color: var(--cream);
    opacity: 0;
    transform: scale(0.8);
    transition: opacity 0.3s, transform 0.3s;
    pointer-events: none;
}

.gallery-item:hover .gallery-zoom-hint {
    opacity: 0.8;
    transform: scale(1);
}

@media (max-width: 900px) {
    .gallery-masonry { columns: 2; }
}

@media (max-width: 600px) {
    .gallery-masonry { columns: 1; }
}

/* ── Lightbox Overlay ── */
.lightbox-overlay {
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(10, 10, 10, 0.96);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

/* ── Lightbox Transitions ── */
.lightbox-fade-enter-active,
.lightbox-fade-leave-active {
    transition: opacity 0.35s ease;
}
.lightbox-fade-enter-from,
.lightbox-fade-leave-to {
    opacity: 0;
}

.lightbox-slide-enter-active,
.lightbox-slide-leave-active {
    transition: opacity 0.25s ease, transform 0.25s ease;
}
.lightbox-slide-enter-from {
    opacity: 0;
    transform: translateX(20px);
}
.lightbox-slide-leave-to {
    opacity: 0;
    transform: translateX(-20px);
}

/* ── Close Button ── */
.lightbox-close {
    position: absolute;
    top: 1.25rem;
    right: 1.25rem;
    z-index: 10;
    background: none;
    border: none;
    color: var(--cream, #e8e0d6);
    cursor: pointer;
    opacity: 0.6;
    transition: opacity 0.2s, transform 0.2s;
    padding: 0.5rem;
}

.lightbox-close:hover {
    opacity: 1;
    transform: scale(1.1);
}

/* ── Navigation Arrows ── */
.lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    width: 52px;
    height: 52px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--cream, #e8e0d6);
    cursor: pointer;
    opacity: 0.6;
    transition: opacity 0.2s, background 0.2s;
}

.lightbox-nav:hover {
    opacity: 1;
    background: rgba(255, 255, 255, 0.12);
}

.lightbox-prev { left: 1.25rem; }
.lightbox-next { right: 1.25rem; }

/* ── Main Image Area ── */
.lightbox-main {
    display: flex;
    flex-direction: column;
    align-items: center;
    max-width: calc(100vw - 160px);
    max-height: calc(100vh - 140px);
    width: 100%;
}

.lightbox-image-wrap {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 1;
    min-height: 0;
    width: 100%;
}

.lightbox-image {
    max-width: 100%;
    max-height: calc(100vh - 220px);
    object-fit: contain;
    display: block;
    margin: 0 auto;
    opacity: 0;
    transition: opacity 0.4s ease;
    border-radius: 2px;
}

.lightbox-image.is-loaded {
    opacity: 1;
}

/* ── Loading Spinner ── */
.lightbox-spinner {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.spinner-ring {
    width: 36px;
    height: 36px;
    border: 2px solid rgba(232, 224, 214, 0.15);
    border-top-color: var(--gold, #c9a96e);
    border-radius: 50%;
    animation: spinner-rotate 0.8s linear infinite;
}

@keyframes spinner-rotate {
    to { transform: rotate(360deg); }
}

/* ── Metadata Bar ── */
.lightbox-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 1rem 0.5rem 0;
    gap: 1.5rem;
}

.lightbox-meta-text {
    display: flex;
    align-items: baseline;
    gap: 1rem;
    flex-wrap: wrap;
    min-width: 0;
}

.lightbox-counter {
    font-family: 'DM Sans', sans-serif;
    font-size: 0.7rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--gold, #c9a96e);
    white-space: nowrap;
}

.lightbox-caption {
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    font-weight: 400;
    color: var(--cream, #e8e0d6);
    margin: 0;
    line-height: 1.3;
}

.lightbox-credit {
    font-family: 'DM Sans', sans-serif;
    font-size: 0.75rem;
    color: rgba(232, 224, 214, 0.5);
    margin: 0;
    white-space: nowrap;
}

.lightbox-view-original {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.75rem;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--gold, #c9a96e);
    text-decoration: none;
    border: 1px solid rgba(201, 169, 110, 0.3);
    padding: 0.4rem 0.85rem;
    border-radius: 2px;
    white-space: nowrap;
    transition: background 0.2s, border-color 0.2s;
    flex-shrink: 0;
}

.lightbox-view-original:hover {
    background: rgba(201, 169, 110, 0.1);
    border-color: var(--gold, #c9a96e);
}

/* ── Thumbnail Strip ── */
.lightbox-thumbs {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(10, 10, 10, 0.95), rgba(10, 10, 10, 0.6), transparent);
    padding: 1.5rem 5rem 1.25rem;
    display: flex;
    justify-content: center;
}

.lightbox-thumb-strip {
    display: flex;
    gap: 0.5rem;
    overflow-x: auto;
    padding: 0.25rem 0;
    scrollbar-width: none;
}

.lightbox-thumb-strip::-webkit-scrollbar {
    display: none;
}

.lightbox-thumb {
    flex-shrink: 0;
    width: 64px;
    height: 48px;
    border: 2px solid transparent;
    border-radius: 2px;
    overflow: hidden;
    cursor: pointer;
    opacity: 0.45;
    transition: opacity 0.25s, border-color 0.25s, transform 0.25s;
    background: none;
    padding: 0;
}

.lightbox-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.lightbox-thumb:hover {
    opacity: 0.8;
    transform: translateY(-2px);
}

.lightbox-thumb.is-active {
    opacity: 1;
    border-color: var(--gold, #c9a96e);
    transform: translateY(-2px);
}

/* ── Responsive ── */
@media (max-width: 768px) {
    .lightbox-nav {
        width: 40px;
        height: 40px;
    }

    .lightbox-nav svg {
        width: 24px;
        height: 24px;
    }

    .lightbox-prev { left: 0.5rem; }
    .lightbox-next { right: 0.5rem; }

    .lightbox-main {
        max-width: calc(100vw - 100px);
    }

    .lightbox-image {
        max-height: calc(100vh - 250px);
    }

    .lightbox-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .lightbox-thumbs {
        padding: 1rem 1rem 0.75rem;
    }

    .lightbox-thumb {
        width: 52px;
        height: 40px;
    }

    .lightbox-caption {
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    .lightbox-meta-text {
        gap: 0.5rem;
    }
}
</style>
