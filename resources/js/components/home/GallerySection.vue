<script setup lang="ts">
import { ref, onMounted } from 'vue';
import ImageLightbox from './ImageLightbox.vue';
import type { LightboxImage } from './ImageLightbox.vue';

withDefaults(defineProps<{ theme?: string }>(), { theme: 'section-green' });

const galleryImages = ref<LightboxImage[]>([
    {
        src: '/images/media/thumbnails/wayland/harry-skoler-wayland-ma-1.jpg',
        full: '/images/media/full/wayland/harry-skoler-wayland-ma-1.jpg',
        caption: 'Wayland, MA 2023',
        credit: 'Photo by Jean-Pierre Ducondi',
    },
    {
        src: '/images/about/side-image-3.jpg',
        full: '/images/about/side-image-3.jpg',
        caption: 'Harry Skoler',
    },
    {
        src: '/images/media/thumbnails/wayland/harry-skoler-wayland-ma-2.jpg',
        full: '/images/media/full/wayland/harry-skoler-wayland-ma-2.jpg',
        caption: 'Wayland, MA 2023',
        credit: 'Photo by Jean-Pierre Ducondi',
    },
    {
        src: '/images/cartoon.jpg',
        full: '/images/cartoon.jpg',
        caption: "How Harry's life was influenced by Charles Mingus",
        credit: 'Artwork by Dave Chisholm',
    },
    {
        src: '/images/media/thumbnails/wayland/harry-skoler-wayland-ma-3.jpg',
        full: '/images/media/full/wayland/harry-skoler-wayland-ma-3.jpg',
        caption: 'Wayland, MA 2023',
        credit: 'Photo by Jean-Pierre Ducondi',
    },
    {
        src: '/images/events/arts-wayland-4-6-25.png',
        full: '/images/events/arts-wayland-4-6-25.png',
        caption: 'Arts Wayland 2025',
    },
    {
        src: '/images/about/side-image-1.jpg',
        full: '/images/about/side-image-1.jpg',
        caption: 'Harry Skoler',
    },
]);

const lightboxOpen = ref(false);
const lightboxIndex = ref(0);

function openLightbox(index: number) {
    lightboxIndex.value = index;
    lightboxOpen.value = true;
}

async function fetchGallery() {
    try {
        const res = await fetch('/api/gallery-images');
        if (res.ok) {
            const data = await res.json();
            if (data.length > 0) {
                galleryImages.value = data.map((img: any) => ({
                    src: img.thumbnail_url ?? img.image_url,
                    full: img.image_url,
                    caption: img.caption ?? '',
                    credit: img.credit ?? undefined,
                }));
            }
        }
    } catch {
        // Fall back to hardcoded images
    }
}

onMounted(fetchGallery);
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

        <ImageLightbox
            :images="galleryImages"
            :open="lightboxOpen"
            :start-index="lightboxIndex"
            @close="lightboxOpen = false"
        />
    </section>
</template>

<style scoped>
/* Gallery Masonry Grid */
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
</style>
