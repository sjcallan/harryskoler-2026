<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';

withDefaults(defineProps<{ theme?: string }>(), { theme: 'section-dark' });

interface ReviewItem {
    id: number;
    album_slug: string;
    excerpt: string;
    body: string;
    author: string | null;
    publication: string;
    source_url: string | null;
}

const albumFilters = [
    { slug: 'echoes', title: 'Echoes', cover: '/assets/images/albums/echoes.png' },
    { slug: 'red-brick-hill', title: 'Red Brick Hill', cover: '/assets/images/albums/red-brick-hill-md.png' },
    { slug: 'living-in-sound', title: 'Living In Sound', cover: '/assets/images/albums/living-in-sound-md.jpg' },
    { slug: 'two-ones', title: 'Two Ones', cover: '/assets/images/albums/two-ones-md.jpg' },
    { slug: 'work-of-heart', title: 'A Work of Heart', cover: '/assets/images/albums/a-work-of-heart-md.jpg' },
    { slug: 'reflections', title: 'Reflections', cover: '/assets/images/albums/reflections-md.jpg' },
    { slug: 'conversations', title: 'Conversations', cover: '/assets/images/albums/conversations-md.jpg' },
];

const reviews = ref<ReviewItem[]>([]);
const loading = ref(true);
const expandedId = ref<number | null>(null);
const activeAlbum = ref<string | null>(null);

const availableAlbums = computed(() => albumFilters);

const filteredReviews = computed(() => {
    if (!activeAlbum.value) return reviews.value;
    return reviews.value.filter((r) => r.album_slug === activeAlbum.value);
});

function selectAlbum(slug: string) {
    activeAlbum.value = activeAlbum.value === slug ? null : slug;
    expandedId.value = null;
}

function toggleExpanded(id: number) {
    expandedId.value = expandedId.value === id ? null : id;
}

async function fetchReviews() {
    try {
        const res = await fetch('/api/reviews');
        reviews.value = await res.json();
    } catch {
        // Silently fail on public page
    } finally {
        loading.value = false;
    }
}

onMounted(fetchReviews);
</script>

<template>
    <section id="reviews" :class="['section', theme]">
        <div class="section-header reveal">
            <span class="section-label">Critical Acclaim</span>
            <h2 class="section-title">Reviews</h2>
            <div class="section-divider"></div>
        </div>

        <div class="album-filter reveal">
            <button
                v-for="album in availableAlbums"
                :key="album.slug"
                :class="['album-filter-btn', { active: activeAlbum === album.slug }]"
                @click="selectAlbum(album.slug)"
                :aria-pressed="activeAlbum === album.slug"
                :title="album.title"
            >
                <img :src="album.cover" :alt="album.title" />
                <span class="album-filter-title">{{ album.title }}</span>
            </button>
        </div>

        <div v-if="loading" class="reviews-container">
            <div class="review-item review-skeleton" v-for="n in 4" :key="n">
                <div>
                    <div class="skeleton-line skeleton-line--xl"></div>
                    <div class="skeleton-line skeleton-line--lg"></div>
                </div>
                <div>
                    <div class="skeleton-line skeleton-line--sm" style="margin-left: auto;"></div>
                    <div class="skeleton-line skeleton-line--xs" style="margin-left: auto;"></div>
                </div>
            </div>
        </div>

        <div v-else-if="reviews.length === 0" class="reviews-empty">
            <p>Check back soon for reviews.</p>
        </div>

        <div v-else-if="filteredReviews.length === 0" class="reviews-empty">
            <p>No reviews for this album yet.</p>
        </div>

        <div v-else class="reviews-container">
            <div class="review-item" v-for="review in filteredReviews" :key="review.id">
                <div class="review-content">
                    <div class="review-quote">"{{ review.excerpt }}"</div>
                    <template v-if="review.body && review.body !== review.excerpt">
                        <transition name="review-expand">
                            <div v-if="expandedId === review.id" class="review-full">
                                <p>{{ review.body }}</p>
                                <a
                                    v-if="review.source_url"
                                    :href="review.source_url"
                                    target="_blank"
                                    rel="noopener"
                                    class="review-source-link"
                                >
                                    Read at {{ review.publication }} &rarr;
                                </a>
                            </div>
                        </transition>
                        <button
                            class="review-toggle"
                            @click="toggleExpanded(review.id)"
                        >
                            {{ expandedId === review.id ? 'Show less' : 'Read full review' }}
                        </button>
                    </template>
                </div>
                <div class="review-source">
                    <span class="review-author">{{ review.author }}</span>
                    <span class="review-pub">{{ review.publication }}</span>
                </div>
            </div>
        </div>
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

.reviews-container {
    max-width: 900px;
    margin: 0 auto;
}

.review-item {
    padding: 3rem 0;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 3rem;
    align-items: start;
}

.review-content {
    min-width: 0;
}

.review-quote {
    font-family: 'Instrument Serif', serif;
    font-size: 1.35rem;
    font-style: italic;
    line-height: 1.7;
    color: var(--cream);
}

.review-full {
    margin-top: 1.2rem;
    padding-top: 1.2rem;
    border-top: 1px solid rgba(255,255,255,0.06);
    font-size: 0.92rem;
    line-height: 1.8;
    color: rgba(232, 224, 214, 0.75);
    font-weight: 300;
}

.review-full p {
    white-space: pre-line;
}

.review-source-link {
    display: inline-block;
    margin-top: 1rem;
    font-size: 0.78rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--red);
    text-decoration: none;
    transition: opacity 0.3s;
}

.review-source-link:hover {
    opacity: 0.7;
}

.review-toggle {
    display: inline-block;
    margin-top: 0.8rem;
    background: none;
    border: none;
    padding: 0;
    font-size: 0.72rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--red);
    cursor: pointer;
    transition: opacity 0.3s;
}

.review-toggle:hover {
    opacity: 0.7;
}

.review-source {
    text-align: right;
    white-space: nowrap;
}

.review-author {
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--white);
    display: block;
}

.review-pub {
    font-size: 0.72rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--red);
}

.reviews-empty {
    text-align: center;
    padding: 3rem 0;
    color: rgba(232, 224, 214, 0.5);
    font-size: 0.95rem;
}

/* Skeleton loading */
.review-skeleton {
    pointer-events: none;
}

.skeleton-line {
    height: 0.7rem;
    border-radius: 4px;
    background: rgba(255,255,255,0.06);
    margin-bottom: 0.8rem;
}

.skeleton-line--xl { width: 90%; }
.skeleton-line--lg { width: 70%; }
.skeleton-line--sm { width: 80px; }
.skeleton-line--xs { width: 60px; }

/* Expand transition */
.review-expand-enter-active {
    transition: all 0.35s ease;
    overflow: hidden;
}

.review-expand-leave-active {
    transition: all 0.25s ease;
    overflow: hidden;
}

.review-expand-enter-from,
.review-expand-leave-to {
    opacity: 0;
    max-height: 0;
    margin-top: 0;
    padding-top: 0;
}

.review-expand-enter-to,
.review-expand-leave-from {
    opacity: 1;
    max-height: 500px;
}

@media (max-width: 900px) {
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
    .review-item { grid-template-columns: 1fr; gap: 1rem; }
    .review-source { text-align: left; }
}
</style>
