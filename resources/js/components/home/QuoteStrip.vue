<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue';

interface QuoteData {
    id: number;
    quote: string;
    person: string | null;
    company: string | null;
}

const quotes = ref<QuoteData[]>([]);
const currentIndex = ref(-1);
const visible = ref(false);
const loaded = ref(false);

let shuffled: number[] = [];
let shufflePos = 0;
let cycleTimer: ReturnType<typeof setTimeout> | null = null;

function buildShuffledOrder() {
    shuffled = quotes.value.map((_, i) => i);
    for (let i = shuffled.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
    }
    shufflePos = 0;
}

function nextRandomIndex(): number {
    if (shufflePos >= shuffled.length) {
        const lastShown = shuffled[shuffled.length - 1];
        buildShuffledOrder();
        if (shuffled[0] === lastShown && shuffled.length > 1) {
            [shuffled[0], shuffled[1]] = [shuffled[1], shuffled[0]];
        }
    }
    return shuffled[shufflePos++];
}

function showNext() {
    visible.value = false;

    setTimeout(() => {
        currentIndex.value = nextRandomIndex();
        visible.value = true;

        cycleTimer = setTimeout(showNext, 8000);
    }, 600);
}

const currentQuote = computed(() => {
    if (currentIndex.value < 0 || !quotes.value.length) return null;
    return quotes.value[currentIndex.value];
});

async function fetchQuotes() {
    try {
        const res = await fetch('/api/quotes/active');
        const data: QuoteData[] = await res.json();
        if (data.length) {
            quotes.value = data;
            loaded.value = true;
            buildShuffledOrder();
            currentIndex.value = nextRandomIndex();
            setTimeout(() => { visible.value = true; }, 100);
            cycleTimer = setTimeout(showNext, 8000);
        }
    } catch {
        // Silently fail — quotes are supplementary
    }
}

onMounted(fetchQuotes);

onUnmounted(() => {
    if (cycleTimer) clearTimeout(cycleTimer);
});
</script>

<template>
    <div class="quote-strip" v-if="loaded">
        <div class="quote-inner" :class="{ 'quote-visible': visible }">
            <template v-if="currentQuote">
                <p class="quote-text" v-html="'&ldquo;' + currentQuote.quote + '&rdquo;'"></p>
                <p class="quote-attribution" v-if="currentQuote.person || currentQuote.company">
                    <span v-if="currentQuote.person" class="quote-person">{{ currentQuote.person }}</span>
                    <span v-if="currentQuote.person && currentQuote.company"> &mdash; </span>
                    <span v-if="currentQuote.company" class="quote-company">{{ currentQuote.company }}</span>
                </p>
            </template>
        </div>
    </div>
</template>

<style scoped>
.quote-strip {
    width: 100%;
    background: var(--black);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.8rem 3rem;
    min-height: 90px;
    position: relative;
    z-index: 5;
}

.quote-inner {
    max-width: 800px;
    text-align: center;
    opacity: 0;
    transform: translateY(6px);
    transition: opacity 0.6s cubic-bezier(0.22, 1, 0.36, 1),
                transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
}

.quote-inner.quote-visible {
    opacity: 1;
    transform: translateY(0);
}

.quote-text {
    font-family: 'DM Sans', sans-serif;
    font-weight: 300;
    font-style: italic;
    font-size: clamp(0.85rem, 1.3vw, 1rem);
    line-height: 1.65;
    color: var(--cream);
    letter-spacing: 0.01em;
}

.quote-text :deep(em) {
    font-style: normal;
    font-weight: 500;
    color: var(--white);
}

.quote-attribution {
    margin-top: 0.5rem;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.75rem;
    letter-spacing: 0.08em;
    color: rgba(232, 224, 214, 0.5);
}

.quote-person {
    font-weight: 500;
    color: rgba(232, 224, 214, 0.7);
}

.quote-company {
    font-weight: 400;
}

@media (max-width: 600px) {
    .quote-strip {
        padding: 1.4rem 1.5rem;
        min-height: 80px;
    }
}
</style>
