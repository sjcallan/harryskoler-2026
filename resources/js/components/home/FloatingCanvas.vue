<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps<{
    scrollY: number;
}>();

const canvas = ref<HTMLCanvasElement | null>(null);
let ctx: CanvasRenderingContext2D | null = null;
let w = 0;
let h = 0;
let animId = 0;

function init() {
    if (!canvas.value) return;
    ctx = canvas.value.getContext('2d');
    resize();
}

function resize() {
    if (!canvas.value || !ctx) return;
    const dpr = window.devicePixelRatio || 1;
    w = window.innerWidth;
    h = window.innerHeight;
    canvas.value.width = w * dpr;
    canvas.value.height = h * dpr;
    canvas.value.style.width = w + 'px';
    canvas.value.style.height = h + 'px';
    ctx.scale(dpr, dpr);
}

function draw(time: number) {
    if (!ctx) return;
    ctx.clearRect(0, 0, w, h);

    const scrollFactor = props.scrollY * 0.3;
    const numSquares = 6;

    for (let i = 0; i < numSquares; i++) {
        const phase = (i / numSquares) * Math.PI * 2;
        const xBase = w * (0.15 + (i % 3) * 0.35);
        const yBase = h * (0.2 + Math.floor(i / 3) * 0.5);
        const x = xBase + Math.sin(time * 0.0004 + phase) * 30;
        const y = yBase + Math.cos(time * 0.0003 + phase) * 20 - scrollFactor % h;
        const size = 80 + Math.sin(time * 0.0006 + i) * 30 + (props.scrollY * 0.02);
        const rotation = Math.sin(time * 0.0002 + i * 1.5) * 0.1;

        ctx.save();
        ctx.translate(x, ((y % (h + 200)) + h + 200) % (h + 200) - 100);
        ctx.rotate(rotation);
        ctx.strokeStyle = i % 2 === 0 ? 'rgba(184,40,46,0.4)' : 'rgba(46,107,74,0.3)';
        ctx.lineWidth = 1.5;
        ctx.strokeRect(-size / 2, -size / 2, size, size);
        ctx.restore();
    }

    animId = requestAnimationFrame(draw);
}

onMounted(() => {
    init();
    draw(0);
    window.addEventListener('resize', resize);
});

onUnmounted(() => {
    cancelAnimationFrame(animId);
    window.removeEventListener('resize', resize);
});
</script>

<template>
    <div class="floating-canvas-wrap" :class="{ visible: scrollY > 400 }">
        <canvas ref="canvas"></canvas>
    </div>
</template>

<style scoped>
.floating-canvas-wrap {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    pointer-events: none;
    opacity: 0.06;
    transition: opacity 0.5s;
}
.floating-canvas-wrap.visible { opacity: 0.08; }
</style>
