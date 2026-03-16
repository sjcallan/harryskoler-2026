<script setup lang="ts">
import { ref, onMounted } from 'vue';

withDefaults(defineProps<{ theme?: string }>(), { theme: 'section-red-accent' });

interface RadioAirplayItem {
    id: number;
    rank: number;
    chart: string;
    detail: string | null;
}

const radioCharts = ref<RadioAirplayItem[]>([]);
const loading = ref(true);

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

        <div v-if="loading" class="radio-charts">
            <div class="radio-item radio-skeleton" v-for="n in 4" :key="n">
                <div class="skeleton-rank"></div>
                <div class="skeleton-line skeleton-line--lg"></div>
                <div class="skeleton-line skeleton-line--md"></div>
            </div>
        </div>

        <div v-else-if="radioCharts.length === 0" class="radio-empty">
            <p>Check back soon for chart updates.</p>
        </div>

        <div v-else class="radio-charts">
            <div class="radio-item reveal" v-for="chart in radioCharts" :key="chart.id">
                <div class="chart-rank">#{{ chart.rank }}</div>
                <h3>{{ chart.chart }}</h3>
                <span v-if="chart.detail" class="chart-detail">{{ chart.detail }}</span>
            </div>
        </div>
    </section>
</template>

<style scoped>
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
}
</style>
