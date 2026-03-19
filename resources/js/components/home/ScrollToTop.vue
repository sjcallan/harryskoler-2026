<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

const visible = ref(false);

function onScroll() {
    visible.value = window.scrollY > 300;
}

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

onMounted(() => {
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
});

onUnmounted(() => {
    window.removeEventListener('scroll', onScroll);
});
</script>

<template>
    <Teleport to="body">
        <button
            class="scroll-to-top"
            :class="{ 'is-visible': visible }"
            aria-label="Scroll to top"
            @click="scrollToTop"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="18 15 12 9 6 15"/>
            </svg>
        </button>
    </Teleport>
</template>

<style scoped>
.scroll-to-top {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    z-index: 1000;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(184, 40, 46, 0.4);
    border-radius: 50%;
    background: rgba(10, 10, 10, 0.75);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    color: var(--cream, #e8e0d6);
    cursor: pointer;
    opacity: 0;
    pointer-events: none;
    transform: translateY(12px);
    transition: opacity 0.35s ease, transform 0.35s ease, background 0.3s, border-color 0.3s;
}

.scroll-to-top.is-visible {
    opacity: 1;
    pointer-events: auto;
    transform: translateY(0);
}

.scroll-to-top:hover {
    background: rgba(184, 40, 46, 0.25);
    border-color: var(--red, #B8282E);
    color: var(--white, #f5f0eb);
}

@media (max-width: 600px) {
    .scroll-to-top {
        bottom: 1.25rem;
        right: 1.25rem;
        width: 36px;
        height: 36px;
    }
}
</style>
