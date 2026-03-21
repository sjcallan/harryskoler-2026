<script setup lang="ts">
import { ref, onMounted } from 'vue';

withDefaults(defineProps<{ theme?: string }>(), { theme: 'section-dark' });

interface PressImage {
    id: number;
    image_url: string;
    caption: string | null;
    sort_order: number;
}

interface PressEvent {
    id: number;
    title: string;
    publication: string | null;
    description: string | null;
    date: string | null;
    pdf_url: string | null;
    images: PressImage[];
}

const events = ref<PressEvent[]>([]);
const loading = ref(true);
const lightboxImage = ref<PressImage | null>(null);
const lightboxEventImages = ref<PressImage[]>([]);

function openLightbox(image: PressImage, allImages: PressImage[]) {
    lightboxImage.value = image;
    lightboxEventImages.value = allImages;
}

function closeLightbox() {
    lightboxImage.value = null;
    lightboxEventImages.value = [];
}

function lightboxPrev() {
    if (!lightboxImage.value) return;
    const idx = lightboxEventImages.value.findIndex(img => img.id === lightboxImage.value!.id);
    const prev = idx > 0 ? idx - 1 : lightboxEventImages.value.length - 1;
    lightboxImage.value = lightboxEventImages.value[prev];
}

function lightboxNext() {
    if (!lightboxImage.value) return;
    const idx = lightboxEventImages.value.findIndex(img => img.id === lightboxImage.value!.id);
    const next = idx < lightboxEventImages.value.length - 1 ? idx + 1 : 0;
    lightboxImage.value = lightboxEventImages.value[next];
}

function onLightboxKeydown(e: KeyboardEvent) {
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') lightboxPrev();
    if (e.key === 'ArrowRight') lightboxNext();
}

function formatDate(dateStr: string | null): string {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', timeZone: 'UTC' });
}

async function fetchPress() {
    try {
        const res = await fetch('/api/press-events');
        events.value = await res.json();
    } catch {
        // Silently fail on public page
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    fetchPress();
    window.addEventListener('keydown', onLightboxKeydown);
});
</script>

<template>
    <section id="press" :class="['section', theme]">
        <div class="section-header reveal">
            <span class="section-label">Featured In</span>
            <h2 class="section-title">Press</h2>
            <div class="section-divider"></div>
        </div>

        <!-- Loading skeletons -->
        <div v-if="loading" class="press-container">
            <div class="press-event press-skeleton" v-for="n in 3" :key="n">
                <div class="press-event-header">
                    <div class="skeleton-line skeleton-line--lg"></div>
                    <div class="skeleton-line skeleton-line--sm"></div>
                </div>
                <div class="press-image-grid">
                    <div class="press-image-thumb skeleton-shimmer" v-for="m in 4" :key="m"></div>
                </div>
            </div>
        </div>

        <div v-else-if="events.length === 0" class="press-empty">
            <p>Check back soon for press features.</p>
        </div>

        <div v-else class="press-container">
            <div
                v-for="event in events"
                :key="event.id"
                class="press-event reveal"
            >
                <div class="press-event-header">
                    <div class="press-event-info">
                        <span v-if="event.publication" class="press-publication">{{ event.publication }}</span>
                        <h3 class="press-event-title">{{ event.title }}</h3>
                        <span v-if="event.date" class="press-date">{{ formatDate(event.date) }}</span>
                    </div>
                    <div class="press-event-actions">
                        <a
                            v-if="event.pdf_url"
                            :href="event.pdf_url"
                            target="_blank"
                            rel="noopener"
                            class="press-pdf-link"
                        >
                            View PDF
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        </a>
                    </div>
                </div>

                <div class="press-event-body">
                    <p v-if="event.description" class="press-description">{{ event.description }}</p>
                    <div v-if="event.images.length" class="press-image-grid">
                        <button
                            v-for="img in event.images"
                            :key="img.id"
                            class="press-image-thumb"
                            @click="openLightbox(img, event.images)"
                        >
                            <img :src="img.image_url" :alt="img.caption ?? event.title" />
                            <div class="press-image-overlay">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                            </div>
                            <span v-if="img.caption" class="press-image-caption">{{ img.caption }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lightbox -->
        <teleport to="body">
            <transition name="lightbox-fade">
                <div v-if="lightboxImage" class="press-lightbox" @click.self="closeLightbox">
                    <button class="press-lightbox-close" @click="closeLightbox">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>

                    <button
                        v-if="lightboxEventImages.length > 1"
                        class="press-lightbox-nav press-lightbox-prev"
                        @click="lightboxPrev"
                    >
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>

                    <div class="press-lightbox-content">
                        <img :src="lightboxImage.image_url" :alt="lightboxImage.caption ?? ''" />
                        <p v-if="lightboxImage.caption" class="press-lightbox-caption">{{ lightboxImage.caption }}</p>
                    </div>

                    <button
                        v-if="lightboxEventImages.length > 1"
                        class="press-lightbox-nav press-lightbox-next"
                        @click="lightboxNext"
                    >
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>
            </transition>
        </teleport>
    </section>
</template>

<style scoped>
.press-container {
    max-width: 1000px;
    margin: 0 auto;
}

.press-event {
    border-bottom: 1px solid rgba(255,255,255,0.06);
}

.press-event:first-child {
    border-top: 1px solid rgba(255,255,255,0.06);
}

.press-event-header {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
    padding: 2rem 0 1rem;
    color: inherit;
}

.press-event-info {
    min-width: 0;
}

.press-publication {
    font-size: 0.68rem;
    font-weight: 600;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--white);
    display: block;
    margin-bottom: 0.4rem;
}

.press-event-title {
    font-family: 'AkzidenzGroteskPro', 'Playfair Display', serif;
    font-weight: 500;
    font-size: 1.3rem;
    color: var(--white);
    line-height: 1.3;
    letter-spacing: 0.01em;
}

.press-date {
    font-size: 0.75rem;
    color: rgba(232, 224, 214, 0.45);
    margin-top: 0.3rem;
    display: block;
    letter-spacing: 0.04em;
}

.press-event-actions {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-shrink: 0;
}

.press-pdf-link {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--white);
    text-decoration: none;
    padding: 0.5rem 1rem;
    border: 1px solid rgba(255, 255, 255, 0.5);
    transition: all 0.3s;
    white-space: nowrap;
}

.press-pdf-link:hover {
    background: var(--white);
    color: var(--dark);
}

.press-event-body {
    padding-bottom: 2.5rem;
}

.press-description {
    font-size: 0.9rem;
    line-height: 1.7;
    color: rgba(232, 224, 214, 0.65);
    font-weight: 300;
    margin-bottom: 1.5rem;
    max-width: 700px;
}

.press-image-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 1rem;
}

.press-image-thumb {
    position: relative;
    overflow: hidden;
    cursor: pointer;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
    transition: border-color 0.3s;
    padding: 0;
    display: block;
    color: inherit;
}

.press-image-thumb:hover {
    border-color: rgba(255, 255, 255, 0.3);
}

.press-image-thumb img {
    width: 100%;
    aspect-ratio: 4 / 3;
    object-fit: cover;
    display: block;
    transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1), filter 0.5s;
    filter: grayscale(20%);
}

.press-image-thumb:hover img {
    transform: scale(1.06);
    filter: grayscale(0%);
}

.press-image-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(10, 10, 10, 0.5);
    opacity: 0;
    transition: opacity 0.3s;
    color: var(--white);
}

.press-image-thumb:hover .press-image-overlay {
    opacity: 1;
}

.press-image-caption {
    display: block;
    padding: 0.6rem 0.8rem;
    font-size: 0.7rem;
    color: rgba(232, 224, 214, 0.5);
    letter-spacing: 0.03em;
    line-height: 1.3;
}

/* Lightbox */
.press-lightbox {
    position: fixed;
    inset: 0;
    z-index: 10000;
    background: rgba(0, 0, 0, 0.92);
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(8px);
}

.press-lightbox-close {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    background: none;
    border: none;
    color: rgba(255,255,255,0.7);
    cursor: pointer;
    z-index: 2;
    transition: color 0.2s;
    padding: 0.5rem;
}

.press-lightbox-close:hover {
    color: white;
}

.press-lightbox-content {
    max-width: 85vw;
    max-height: 85vh;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.press-lightbox-content img {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
    box-shadow: 0 20px 60px rgba(0,0,0,0.4);
}

.press-lightbox-caption {
    margin-top: 1rem;
    font-size: 0.85rem;
    color: rgba(255,255,255,0.6);
    text-align: center;
    letter-spacing: 0.03em;
}

.press-lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.7);
    cursor: pointer;
    z-index: 2;
    padding: 1rem 0.75rem;
    transition: all 0.2s;
}

.press-lightbox-nav:hover {
    background: rgba(255,255,255,0.1);
    color: white;
}

.press-lightbox-prev {
    left: 1.5rem;
}

.press-lightbox-next {
    right: 1.5rem;
}

.lightbox-fade-enter-active {
    transition: opacity 0.25s ease;
}

.lightbox-fade-leave-active {
    transition: opacity 0.2s ease;
}

.lightbox-fade-enter-from,
.lightbox-fade-leave-to {
    opacity: 0;
}

/* Empty / loading states */
.press-empty {
    text-align: center;
    padding: 3rem 0;
    color: rgba(232, 224, 214, 0.5);
    font-size: 0.95rem;
}

.press-skeleton {
    pointer-events: none;
}

.skeleton-shimmer {
    background: linear-gradient(90deg, rgba(255,255,255,0.03) 25%, rgba(255,255,255,0.06) 50%, rgba(255,255,255,0.03) 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
    aspect-ratio: 4 / 3;
}

.skeleton-line {
    height: 0.7rem;
    border-radius: 4px;
    background: rgba(255,255,255,0.06);
    margin-bottom: 0.5rem;
}

.skeleton-line--lg { width: 55%; }
.skeleton-line--sm { width: 25%; }

@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

@media (max-width: 900px) {
    .press-image-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    }

    .press-event-header {
        flex-wrap: wrap;
        gap: 1rem;
    }

    .press-pdf-link {
        font-size: 0.65rem;
        padding: 0.4rem 0.8rem;
    }
}

@media (max-width: 600px) {
    .press-image-grid {
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .press-lightbox-nav {
        display: none;
    }
}
</style>
