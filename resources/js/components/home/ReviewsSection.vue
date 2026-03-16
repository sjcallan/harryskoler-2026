<script setup lang="ts">
import { ref, onMounted } from 'vue';

withDefaults(defineProps<{ theme?: string }>(), { theme: 'section-dark' });

interface ReviewItem {
    id: number;
    excerpt: string;
    body: string;
    author: string | null;
    publication: string;
    source_url: string | null;
}

const reviews = ref<ReviewItem[]>([]);
const loading = ref(true);
const expandedId = ref<number | null>(null);

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

        <div v-else class="reviews-container">
            <div class="review-item reveal" v-for="review in reviews" :key="review.id">
                <div class="review-content">
                    <div class="review-quote">"{{ review.excerpt }}"</div>
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
    .review-item { grid-template-columns: 1fr; gap: 1rem; }
    .review-source { text-align: left; }
}
</style>
