<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import ImageLightbox from './ImageLightbox.vue';
import type { LightboxImage } from './ImageLightbox.vue';

withDefaults(defineProps<{ theme?: string }>(), { theme: 'section-red-accent' });

interface RadioAirplayItem {
    id: number;
    rank: number;
    chart: string;
    detail: string | null;
    link: string | null;
    image_url: string | null;
    thumbnail_url: string | null;
    album_slug: string;
}

const albumFilters = [
    { slug: 'echoes', title: 'Echoes', cover: '/images/albums/echoes.png' },
    { slug: 'red-brick-hill', title: 'Red Brick Hill', cover: '/images/albums/red-brick-hill-md.png' },
    { slug: 'living-in-sound', title: 'Living In Sound', cover: '/images/albums/living-in-sound-md.jpg' },
    { slug: 'two-ones', title: 'Two Ones', cover: '/images/albums/two-ones-md.jpg' },
    { slug: 'work-of-heart', title: 'A Work of Heart', cover: '/images/albums/a-work-of-heart-md.jpg' },
    { slug: 'reflections', title: 'Reflections', cover: '/images/albums/reflections-md.jpg' },
    { slug: 'conversations', title: 'Conversations', cover: '/images/albums/conversations-md.jpg' },
];

const radioCharts = ref<RadioAirplayItem[]>([]);
const loading = ref(true);
const activeAlbum = ref<string | null>(null);
const lightboxOpen = ref(false);
const lightboxIndex = ref(0);

const filteredCharts = computed(() => {
    if (!activeAlbum.value) return radioCharts.value;
    return radioCharts.value.filter((r) => r.album_slug === activeAlbum.value);
});

const lightboxImages = computed<LightboxImage[]>(() =>
    filteredCharts.value
        .filter((c) => c.image_url)
        .map((c) => ({
            src: c.thumbnail_url ?? c.image_url!,
            full: c.image_url!,
            caption: `#${c.rank} ${c.chart}`,
        })),
);

function selectAlbum(slug: string) {
    activeAlbum.value = activeAlbum.value === slug ? null : slug;
}

function openLightbox(item: RadioAirplayItem) {
    if (!item.image_url) return;
    const idx = lightboxImages.value.findIndex((img) => img.full === item.image_url);
    lightboxIndex.value = idx >= 0 ? idx : 0;
    lightboxOpen.value = true;
}

async function fetchRadioAirplays() {
    try {
        const res = await fetch('/api/radio-airplays');
        radioCharts.value = await res.json();
    } catch {
        // Silently fail on public page
    } finally {
        loading.value = false;
    }
}

onMounted(fetchRadioAirplays);
</script>

<template>
    <section id="radio" :class="['section', theme]">
        <div class="section-header reveal">
            <span class="section-label">Charts</span>
            <h2 class="section-title">Radio Airplay</h2>
            <div class="section-divider"></div>
        </div>

        <div class="album-filter reveal">
            <button
                v-for="album in albumFilters"
                :key="album.slug"
                :class="['album-filter-btn', { active: activeAlbum === album.slug }]"
                @click="selectAlbum(album.slug)"
                :aria-pressed="activeAlbum === album.slug"
                :title="album.title"
            >
                <img :src="album.cover" :alt="album.title" loading="lazy" />
                <span class="album-filter-title">{{ album.title }}</span>
            </button>
        </div>

        <div v-if="loading" class="radio-charts">
            <div class="radio-item radio-skeleton" v-for="n in 4" :key="n">
                <div class="skeleton-rank"></div>
                <div class="skeleton-line skeleton-line--lg"></div>
                <div class="skeleton-line skeleton-line--md"></div>
            </div>
        </div>

        <div v-else-if="filteredCharts.length === 0" class="radio-empty">
            <p v-if="activeAlbum">No radio airplay entries for this album yet.</p>
            <p v-else>Check back soon for chart updates.</p>
        </div>

        <div v-else class="radio-charts">
            <div class="radio-item reveal" v-for="chart in filteredCharts" :key="chart.id">
                <div v-if="chart.thumbnail_url" class="radio-thumb" @click="openLightbox(chart)">
                    <img :src="chart.thumbnail_url" :alt="chart.chart" loading="lazy" />
                </div>
                <div class="chart-rank">#{{ chart.rank }}</div>
                <h3>{{ chart.chart }}</h3>
                <span v-if="chart.detail" class="chart-detail">{{ chart.detail }}</span>
                <a v-if="chart.link" :href="chart.link" target="_blank" rel="noopener" class="chart-link">
                    View &rarr;
                </a>
            </div>
        </div>

        <ImageLightbox
            :images="lightboxImages"
            :open="lightboxOpen"
            :start-index="lightboxIndex"
            @close="lightboxOpen = false"
        />
    </section>
</template>

<style scoped>
/* Album filter strip */
.album-filter {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    max-width: 900px;
    margin: 0 auto 2rem;
    padding: 0 1rem;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}
.album-filter::-webkit-scrollbar { display: none; }

.album-filter-btn {
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    transition: transform 0.3s ease, opacity 0.3s ease;
    opacity: 0.5;
}
.album-filter-btn:hover {
    opacity: 0.85;
    transform: translateY(-2px);
}
.album-filter-btn.active {
    opacity: 1;
}
.album-filter-btn img {
    width: 72px;
    height: 72px;
    object-fit: cover;
    border-radius: 4px;
    border: 2px solid transparent;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}
.album-filter-btn.active img {
    border-color: var(--red);
    box-shadow: 0 0 12px rgba(184, 40, 46, 0.35);
}
.album-filter-title {
    font-size: 0.6rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--cream);
    max-width: 80px;
    text-align: center;
    line-height: 1.3;
    transition: color 0.3s ease;
}
.album-filter-btn.active .album-filter-title {
    color: var(--red);
}

.radio-charts {
    max-width: 900px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
}

.radio-item {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
    padding: 2rem;
    transition: all 0.3s;
}

.radio-item:hover {
    border-color: var(--red);
}

.radio-thumb {
    margin-bottom: 1rem;
    cursor: pointer;
    overflow: hidden;
    border-radius: 4px;
}
.radio-thumb img {
    width: 100%;
    display: block;
    transition: transform 0.4s ease;
    filter: grayscale(20%);
}
.radio-thumb:hover img {
    transform: scale(1.03);
    filter: grayscale(0%);
}

.radio-item .chart-rank {
    font-family: 'AkzidenzGroteskPro', 'Instrument Serif', serif;
    font-weight: 700;
    font-size: 3rem;
    color: var(--red);
    line-height: 1;
    margin-bottom: 0.5rem;
}

.radio-item h3 {
    font-family: 'AkzidenzGroteskPro', 'Playfair Display', serif;
    font-weight: 500;
    font-size: 1rem;
    margin-bottom: 0.3rem;
    letter-spacing: 0.02em;
}

.radio-item .chart-detail {
    font-size: 0.78rem;
    color: rgba(232,224,214,0.5);
    letter-spacing: 0.05em;
    display: block;
}

.chart-link {
    display: inline-block;
    margin-top: 0.75rem;
    font-size: 0.72rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--red);
    text-decoration: none;
    transition: opacity 0.3s;
}
.chart-link:hover {
    opacity: 0.7;
}

.radio-empty {
    text-align: center;
    padding: 3rem 0;
    color: rgba(232, 224, 214, 0.5);
    font-size: 0.95rem;
}

/* Skeleton loading */
.radio-skeleton {
    pointer-events: none;
}

.skeleton-rank {
    width: 70px;
    height: 3rem;
    border-radius: 4px;
    background: rgba(255,255,255,0.06);
    margin-bottom: 0.5rem;
}

.skeleton-line {
    height: 0.7rem;
    border-radius: 4px;
    background: rgba(255,255,255,0.06);
    margin-bottom: 0.5rem;
}

.skeleton-line--lg { width: 75%; }
.skeleton-line--md { width: 55%; }

@media (max-width: 900px) {
    .radio-charts { grid-template-columns: 1fr; }
    .album-filter {
        justify-content: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .album-filter-btn img {
        width: 56px;
        height: 56px;
    }
    .album-filter-title {
        font-size: 0.5rem;
        max-width: 60px;
    }
}
</style>
