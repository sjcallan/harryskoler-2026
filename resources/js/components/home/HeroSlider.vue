<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps<{
    scrollY: number;
}>();

const heroCanvas = ref<HTMLCanvasElement | null>(null);
const currentSlide = ref(0);
const totalSlides = 3;
const slideNames = ['Echoes', 'Red Brick Hill', 'Living In Sound'];
let autoSlideTimer: ReturnType<typeof setInterval> | null = null;

function nextSlide() {
    currentSlide.value = (currentSlide.value + 1) % totalSlides;
    resetAutoSlide();
}

function prevSlide() {
    currentSlide.value = (currentSlide.value - 1 + totalSlides) % totalSlides;
    resetAutoSlide();
}

function goToSlide(i: number) {
    currentSlide.value = i;
    resetAutoSlide();
}

function resetAutoSlide() {
    if (autoSlideTimer) clearInterval(autoSlideTimer);
    autoSlideTimer = setInterval(() => {
        currentSlide.value = (currentSlide.value + 1) % totalSlides;
    }, 7000);
}

// --- Canvas animation ---
let ctx: CanvasRenderingContext2D | null = null;
let canvasW = 0;
let canvasH = 0;
let animId = 0;

function initCanvas() {
    if (!heroCanvas.value) return;
    ctx = heroCanvas.value.getContext('2d');
    resizeCanvas();
}

function resizeCanvas() {
    if (!heroCanvas.value || !ctx) return;
    const size = Math.min(window.innerWidth * 0.8, window.innerHeight * 0.75, 700);
    const dpr = window.devicePixelRatio || 1;
    heroCanvas.value.width = size * dpr;
    heroCanvas.value.height = size * dpr;
    heroCanvas.value.style.width = size + 'px';
    heroCanvas.value.style.height = size + 'px';
    ctx.scale(dpr, dpr);
    canvasW = size;
    canvasH = size;
}

function drawBrushRect(c: CanvasRenderingContext2D, cx: number, cy: number, w: number, h: number, time: number, index: number, wobble: number) {
    const hw = w / 2;
    const hh = h / 2;
    const steps = 40;
    const seed = index * 137.5;

    c.beginPath();

    for (let s = 0; s <= steps; s++) {
        const t = s / steps;
        const x = cx - hw + w * t;
        const noise = Math.sin(t * 12 + seed + time * 0.001) * wobble + Math.sin(t * 7 + seed * 2.1) * wobble * 0.6;
        const y = cy - hh + noise;
        if (s === 0) c.moveTo(x, y); else c.lineTo(x, y);
    }
    for (let s = 0; s <= steps; s++) {
        const t = s / steps;
        const y = cy - hh + h * t;
        const noise = Math.sin(t * 11 + seed + 50 + time * 0.001) * wobble + Math.cos(t * 8 + seed * 1.7) * wobble * 0.5;
        c.lineTo(cx + hw + noise, y);
    }
    for (let s = 0; s <= steps; s++) {
        const t = s / steps;
        const x = cx + hw - w * t;
        const noise = Math.sin(t * 10 + seed + 100 + time * 0.0012) * wobble + Math.sin(t * 6 + seed * 0.9) * wobble * 0.7;
        c.lineTo(x, cy + hh + noise);
    }
    for (let s = 0; s <= steps; s++) {
        const t = s / steps;
        const y = cy + hh - h * t;
        const noise = Math.sin(t * 9 + seed + 150 + time * 0.0009) * wobble + Math.cos(t * 13 + seed * 2.3) * wobble * 0.4;
        c.lineTo(cx - hw + noise, y);
    }

    c.closePath();
    c.fill();
    c.stroke();
}

function draw(time: number) {
    if (!ctx) return;
    ctx.clearRect(0, 0, canvasW, canvasH);

    const cx = canvasW / 2;
    const cy = canvasH / 2;
    const maxSize = canvasW * 0.9;
    const numSquares = 10;
    const scrollInfluence = Math.min(props.scrollY / 800, 1);

    ctx.save();
    ctx.strokeStyle = 'rgba(255,255,255,0.85)';
    ctx.lineWidth = canvasW * 0.06;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    drawBrushRect(ctx, cx, cy, maxSize * 1.02, maxSize * 1.02, time, 0, 8);
    ctx.restore();

    for (let i = numSquares - 1; i >= 0; i--) {
        const t = i / numSquares;
        const baseSize = maxSize * (1 - t * 0.85);
        const breathAmt = 12 + i * 3;
        const breathSpeed = 0.0008 + i * 0.00015;
        const scrollBreath = Math.sin(scrollInfluence * Math.PI * 2 + i * 0.6) * breathAmt * scrollInfluence;
        const timeBreath = Math.sin(time * breathSpeed + i * 0.7) * (breathAmt * 0.4);
        const size = baseSize + scrollBreath + timeBreath;

        ctx.save();
        if (i % 2 === 0) {
            ctx.fillStyle = `rgba(184, 40, 46, ${0.95 - t * 0.1})`;
            ctx.strokeStyle = `rgba(140, 28, 32, ${0.5 + t * 0.3})`;
        } else {
            ctx.fillStyle = `rgba(10, 10, 10, ${0.95 - t * 0.05})`;
            ctx.strokeStyle = 'rgba(30, 30, 30, 0.3)';
        }
        ctx.lineWidth = 2 + (numSquares - i) * 0.5;
        drawBrushRect(ctx, cx, cy, size, size, time, i, 4 + i * 0.5);
        ctx.restore();
    }

    animId = requestAnimationFrame(draw);
}

onMounted(() => {
    initCanvas();
    draw(0);
    window.addEventListener('resize', resizeCanvas);
    resetAutoSlide();
});

onUnmounted(() => {
    cancelAnimationFrame(animId);
    window.removeEventListener('resize', resizeCanvas);
    if (autoSlideTimer) clearInterval(autoSlideTimer);
});
</script>

<template>
    <section id="home" class="hero-slider">
        <div class="hero-track" :style="{ transform: 'translateX(-' + (currentSlide * 100) + '%)' }">

            <!-- SLIDE 1: Echoes -->
            <div class="hero-slide hero-slide--echoes" :class="{ 'slide-active': currentSlide === 0 }">
                <div class="hero-canvas-wrap">
                    <canvas ref="heroCanvas" id="heroCanvas"></canvas>
                </div>
                <div class="hero-content">
                    <div class="hero-artist">Harry Skoler</div>
                    <h1 class="hero-title">Echoes</h1>
                    <div class="hero-musicians">
                        <div class="hero-musician"><strong>Bill Frisell</strong>Guitar</div>
                        <div class="hero-musician"><strong>Dezron Douglas</strong>Bass</div>
                        <div class="hero-musician"><strong>Johnathan Blake</strong>Drums</div>
                    </div>
                    <div class="hero-cta-row">
                        <a href="https://harryskoler.bandcamp.com/" target="_blank" class="cta-btn">Listen Now</a>
                    </div>
                </div>
            </div>

            <!-- SLIDE 2: Red Brick Hill -->
            <div class="hero-slide hero-slide--rbh" :class="{ 'slide-active': currentSlide === 1 }">
                <div class="slide-decor">
                    <div class="deco-sq" style="width:400px;height:400px;top:10%;left:5%;--rot:8deg;border-color:rgba(184,40,46,0.08);"></div>
                    <div class="deco-sq" style="width:250px;height:250px;bottom:15%;right:10%;--rot:-5deg;border-color:rgba(184,40,46,0.06);"></div>
                    <div class="deco-sq" style="width:180px;height:180px;top:30%;right:25%;--rot:12deg;"></div>
                </div>
                <div class="hero-content">
                    <div class="hero-artist">Harry Skoler</div>
                    <h1 class="hero-title hero-title--light">Red Brick Hill</h1>
                    <div class="hero-subtitle">A story of loss and redemption — on Sunnyside Records</div>
                    <div class="hero-cta-row">
                        <a href="https://harryskoler.bandcamp.com/album/red-brick-hill" target="_blank" class="cta-btn">Bandcamp</a>
                        <a href="https://music.apple.com/us/album/red-brick-hill/1755928110" target="_blank" class="cta-btn cta-btn-outline">Apple Music</a>
                    </div>
                </div>
            </div>

            <!-- SLIDE 3: Living In Sound -->
            <div class="hero-slide hero-slide--lis" :class="{ 'slide-active': currentSlide === 2 }">
                <div class="slide-decor">
                    <div class="deco-sq" style="width:350px;height:350px;bottom:10%;left:8%;--rot:-6deg;border-color:rgba(201,169,110,0.08);"></div>
                    <div class="deco-sq" style="width:280px;height:280px;top:12%;right:8%;--rot:10deg;border-color:rgba(201,169,110,0.06);"></div>
                    <div class="deco-sq" style="width:150px;height:150px;top:45%;left:30%;--rot:-3deg;"></div>
                </div>
                <div class="hero-content">
                    <div class="hero-artist">Harry Skoler</div>
                    <h1 class="hero-title hero-title--light">Living In Sound</h1>
                    <div class="hero-subtitle">The Music of Charles Mingus — Sunnyside Records</div>
                    <div class="hero-cta-row">
                        <a href="https://harryskoler.bandcamp.com/album/living-in-sound-the-music-of-charles-mingus" target="_blank" class="cta-btn">Bandcamp</a>
                        <a href="https://music.apple.com/us/album/living-in-sound-the-music-of-charles-mingus/1614535356" target="_blank" class="cta-btn cta-btn-outline">Apple Music</a>
                    </div>
                </div>
            </div>

        </div>

        <button class="slider-arrow slider-arrow--prev" @click="prevSlide" aria-label="Previous slide">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6" /></svg>
        </button>
        <button class="slider-arrow slider-arrow--next" @click="nextSlide" aria-label="Next slide">
            <svg viewBox="0 0 24 24"><polyline points="9 6 15 12 9 18" /></svg>
        </button>

        <div class="slider-dots">
            <button v-for="(slide, i) in slideNames" :key="i"
                    class="slider-dot" :class="{ active: currentSlide === i }"
                    @click="goToSlide(i)" :aria-label="'Go to ' + slide">
            </button>
        </div>
    </section>
</template>

<style scoped>
.hero-slider {
    position: relative;
    height: 100vh;
    min-height: 700px;
    overflow: hidden;
}

.hero-track {
    display: flex;
    height: 100%;
    transition: transform 0.8s cubic-bezier(0.22, 1, 0.36, 1);
}

.hero-slide {
    min-width: 100%;
    height: 100%;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.hero-slide--echoes { background: var(--green); }
.hero-slide--rbh { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); }
.hero-slide--lis { background: linear-gradient(135deg, #2d1b3d 0%, #1a1a2e 50%, #0d1b2a 100%); }

.hero-canvas-wrap {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

#heroCanvas {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.hero-content {
    position: relative;
    z-index: 10;
    text-align: center;
    pointer-events: none;
}

.hero-artist {
    font-family: 'AkzidenzGroteskPro', 'Playfair Display', serif;
    font-weight: 500;
    font-size: clamp(1.2rem, 2.5vw, 1.8rem);
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--white);
    margin-bottom: 0.5rem;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.7s 0.15s cubic-bezier(0.22, 1, 0.36, 1);
}

.hero-title {
    font-family: 'AkzidenzGroteskPro', 'Instrument Serif', serif;
    font-weight: 700;
    font-size: clamp(4rem, 12vw, 10rem);
    color: var(--black);
    line-height: 0.9;
    letter-spacing: -0.02em;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.7s 0.3s cubic-bezier(0.22, 1, 0.36, 1);
    text-shadow: 0 2px 40px rgba(0,0,0,0.15);
}

.hero-title--light { color: var(--white); }

.hero-musicians {
    margin-top: 2rem;
    display: flex;
    gap: 3rem;
    justify-content: center;
    flex-wrap: wrap;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.7s 0.45s cubic-bezier(0.22, 1, 0.36, 1);
}

.hero-subtitle {
    font-family: 'DM Sans', sans-serif;
    font-weight: 300;
    font-size: 1rem;
    letter-spacing: 0.08em;
    color: var(--cream);
    margin-top: 1.5rem;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.7s 0.45s cubic-bezier(0.22, 1, 0.36, 1);
}

.hero-cta-row {
    margin-top: 2.5rem;
    display: flex;
    gap: 1.2rem;
    justify-content: center;
    pointer-events: auto;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.7s 0.6s cubic-bezier(0.22, 1, 0.36, 1);
}

.slide-active .hero-artist,
.slide-active .hero-title,
.slide-active .hero-musicians,
.slide-active .hero-subtitle,
.slide-active .hero-cta-row {
    opacity: 1;
    transform: translateY(0);
}

.hero-musician {
    font-family: 'DM Sans', sans-serif;
    font-weight: 300;
    font-size: 0.9rem;
    letter-spacing: 0.08em;
    color: var(--white);
}

.hero-musician strong {
    display: block;
    font-weight: 600;
    font-size: 1rem;
}

.slider-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 20;
    width: 52px;
    height: 52px;
    border: 1px solid rgba(245,240,235,0.3);
    background: rgba(10,10,10,0.25);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    color: var(--white);
    font-size: 1.3rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.35s;
    border-radius: 0;
    padding: 0;
}

.slider-arrow:hover {
    background: var(--red);
    border-color: var(--red);
    transform: translateY(-50%) scale(1.08);
}

.slider-arrow--prev { left: 2rem; }
.slider-arrow--next { right: 2rem; }

.slider-arrow svg {
    width: 20px;
    height: 20px;
    stroke: currentColor;
    stroke-width: 2;
    fill: none;
}

.slider-dots {
    position: absolute;
    bottom: 2.5rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 20;
    display: flex;
    gap: 1.2rem;
    align-items: center;
}

.slider-dot {
    width: 10px;
    height: 10px;
    border: 1px solid rgba(245,240,235,0.5);
    background: transparent;
    cursor: pointer;
    transition: all 0.4s;
    padding: 0;
    position: relative;
}

.slider-dot::after {
    content: '';
    position: absolute;
    inset: 1px;
    background: var(--white);
    transform: scale(0);
    transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
}

.slider-dot.active { border-color: var(--red-bright); }
.slider-dot.active::after { background: var(--red-bright); transform: scale(1); }
.slider-dot:hover { border-color: var(--white); }

.slide-decor {
    position: absolute;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
}

.slide-decor .deco-sq {
    position: absolute;
    border: 1px solid rgba(245,240,235,0.06);
    transition: all 1.4s cubic-bezier(0.22, 1, 0.36, 1);
    transform: scale(0.8) rotate(var(--rot, 0deg));
}

.slide-active .deco-sq {
    transform: scale(1) rotate(var(--rot, 0deg));
    border-color: rgba(245,240,235,0.1);
}

@media (max-width: 900px) {
    .hero-musicians { gap: 2rem; }
    .slider-arrow { width: 42px; height: 42px; }
    .slider-arrow--prev { left: 1rem; }
    .slider-arrow--next { right: 1rem; }
}

@media (max-width: 600px) {
    .hero-musicians { flex-direction: column; gap: 1rem; }
}
</style>
