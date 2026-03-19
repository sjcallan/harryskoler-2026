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
const history: number[] = [];
let historyPos = -1;

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

function resetCycleTimer() {
    if (cycleTimer) clearTimeout(cycleTimer);
    cycleTimer = setTimeout(showNext, 8000);
}

function transitionTo(index: number) {
    visible.value = false;
    setTimeout(() => {
        currentIndex.value = index;
        visible.value = true;
        resetCycleTimer();
    }, 400);
}

function showNext() {
    const idx = nextRandomIndex();
    history.splice(historyPos + 1);
    history.push(idx);
    historyPos = history.length - 1;
    transitionTo(idx);
}

function goNext() {
    if (historyPos < history.length - 1) {
        historyPos++;
        transitionTo(history[historyPos]);
    } else {
        showNext();
    }
}

function goPrev() {
    if (historyPos > 0) {
        historyPos--;
        transitionTo(history[historyPos]);
    }
}

const canGoPrev = computed(() => historyPos > 0);

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
            const firstIdx = nextRandomIndex();
            history.push(firstIdx);
            historyPos = 0;
            currentIndex.value = firstIdx;
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
        <button
            class="quote-arrow quote-arrow--left"
            :class="{ 'quote-arrow--disabled': !canGoPrev }"
            :disabled="!canGoPrev"
            @click="goPrev"
            aria-label="Previous quote"
        >
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M13 4L7 10L13 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

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

        <button
            class="quote-arrow quote-arrow--right"
            @click="goNext"
            aria-label="Next quote"
        >
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
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
    min-height: 160px;
    position: relative;
    z-index: 5;
}

.quote-arrow {
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 50%;
    background: rgba(232, 224, 214, 0.08);
    color: rgba(232, 224, 214, 0.5);
    cursor: pointer;
    transition: background 0.25s ease, color 0.25s ease, opacity 0.25s ease;
}

.quote-arrow:hover {
    background: rgba(232, 224, 214, 0.15);
    color: rgba(232, 224, 214, 0.85);
}

.quote-arrow--disabled {
    opacity: 0.25;
    cursor: default;
    pointer-events: none;
}

.quote-inner {
    max-width: 800px;
    text-align: center;
    opacity: 0;
    transform: translateY(6px);
    transition: opacity 0.6s cubic-bezier(0.22, 1, 0.36, 1),
                transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
    padding: 0 1.5rem;
    flex: 1;
    min-width: 0;
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
        padding: 1.4rem 1rem;
        min-height: 140px;
    }

    .quote-arrow {
        width: 30px;
        height: 30px;
    }

    .quote-arrow svg {
        width: 16px;
        height: 16px;
    }

    .quote-inner {
        padding: 0 0.75rem;
    }
}
</style>
