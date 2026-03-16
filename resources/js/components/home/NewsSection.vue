<script setup lang="ts">
import { ref, onMounted } from 'vue';

interface NewsItem {
    id: number;
    title: string;
    body: string;
    date: string;
    image_url: string | null;
    link: string | null;
}

const newsItems = ref<NewsItem[]>([]);
const loading = ref(true);

function formatDate(dateStr: string): string {
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { year: 'numeric', month: 'long' });
}

async function fetchNews() {
    try {
        const res = await fetch('/api/news');
        newsItems.value = await res.json();
    } catch {
        // Silently fail on public page
    } finally {
        loading.value = false;
    }
}

onMounted(fetchNews);
</script>

<template>
    <section id="news" class="section section-deep">
        <div class="section-header reveal">
            <span class="section-label">Latest</span>
            <h2 class="section-title">News</h2>
            <div class="section-divider"></div>
        </div>

        <div v-if="loading" class="news-loading">
            <div class="news-grid">
                <div class="news-card news-skeleton" v-for="n in 4" :key="n">
                    <div class="news-card-image skeleton-shimmer"></div>
                    <div class="news-card-body">
                        <div class="skeleton-line skeleton-line--sm"></div>
                        <div class="skeleton-line skeleton-line--lg"></div>
                        <div class="skeleton-line skeleton-line--md"></div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else-if="newsItems.length === 0" class="news-empty">
            <p>Check back soon for the latest news.</p>
        </div>

        <div v-else class="news-grid">
            <a
                v-for="item in newsItems"
                :key="item.id"
                :href="item.link ?? undefined"
                :target="item.link ? '_blank' : undefined"
                :rel="item.link ? 'noopener' : undefined"
                class="news-card reveal"
                :class="{ 'news-card--linked': item.link }"
            >
                <div class="news-card-image" v-if="item.image_url">
                    <img :src="item.image_url" :alt="item.title" loading="lazy">
                </div>
                <div class="news-card-body">
                    <span class="news-date">{{ formatDate(item.date) }}</span>
                    <h3>{{ item.title }}</h3>
                    <p>{{ item.body }}</p>
                </div>
            </a>
        </div>
    </section>
</template>

<style scoped>
.news-grid {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
}

.news-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
    overflow: hidden;
    transition: all 0.4s;
    text-decoration: none;
    color: inherit;
    display: block;
}

.news-card--linked {
    cursor: pointer;
}

.news-card:hover {
    border-color: rgba(184, 40, 46, 0.3);
    transform: translateY(-4px);
}

.news-card-image {
    height: 220px;
    overflow: hidden;
}

.news-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s;
}

.news-card:hover .news-card-image img {
    transform: scale(1.05);
}

.news-card-body {
    padding: 2rem;
}

.news-date {
    font-size: 0.7rem;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--red);
    margin-bottom: 0.8rem;
    display: block;
}

.news-card h3 {
    font-family: 'AkzidenzGroteskPro', 'Playfair Display', serif;
    font-weight: 500;
    font-size: 1.2rem;
    margin-bottom: 0.8rem;
    color: var(--white);
    line-height: 1.4;
    letter-spacing: 0.02em;
}

.news-card p {
    font-size: 0.9rem;
    line-height: 1.7;
    color: rgba(232, 224, 214, 0.7);
    font-weight: 300;
}

.news-empty {
    text-align: center;
    padding: 3rem 0;
    color: rgba(232, 224, 214, 0.5);
    font-size: 0.95rem;
}

/* Skeleton loading */
.news-skeleton {
    pointer-events: none;
}

.skeleton-shimmer {
    background: linear-gradient(90deg, rgba(255,255,255,0.03) 25%, rgba(255,255,255,0.06) 50%, rgba(255,255,255,0.03) 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
    height: 220px;
}

.skeleton-line {
    height: 0.7rem;
    border-radius: 4px;
    background: rgba(255,255,255,0.06);
    margin-bottom: 0.8rem;
}

.skeleton-line--sm { width: 30%; }
.skeleton-line--lg { width: 80%; }
.skeleton-line--md { width: 60%; }

@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

@media (max-width: 900px) {
    .news-grid { grid-template-columns: 1fr; }
}
</style>
