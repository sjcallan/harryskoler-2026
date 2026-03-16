<script setup lang="ts">
const albums = [
    { title: 'Echoes', cover: 'https://harryskoler.com/images/albums/red-brick-hill-md.png', label: 'Coming Soon' },
    { title: 'Red Brick Hill', cover: 'https://harryskoler.com/images/albums/red-brick-hill-md.png', label: 'Sunnyside Records · 2024' },
    { title: 'Living In Sound', cover: 'https://harryskoler.com/images/albums/living-in-sound-md.jpg', label: 'Sunnyside Records · 2022' },
    { title: 'Two Ones', cover: 'https://harryskoler.com/images/albums/two-ones-md.jpg', label: 'Soliloquy Records' },
    { title: 'A Work of Heart', cover: 'https://harryskoler.com/images/albums/a-work-of-heart-md.jpg', label: 'Brownstone Recordings' },
    { title: 'Reflections', cover: 'https://harryskoler.com/images/albums/reflections-md.jpg', label: 'A Tribute to Benny Goodman' },
];
</script>

<template>
    <section id="music" class="section section-green">
        <div class="section-header reveal">
            <span class="section-label">Discography</span>
            <h2 class="section-title">Music</h2>
            <div class="section-divider"></div>
        </div>
        <div class="albums-grid reveal">
            <div :class="['album-card', { 'album-featured': index === 0 }]" v-for="(album, index) in albums" :key="album.title">
                <img :src="album.cover" :alt="album.title" loading="lazy">
                <div class="album-info">
                    <h3>{{ album.title }}</h3>
                    <span>{{ album.label }}</span>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.albums-grid {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2.5rem;
}

.album-card {
    position: relative;
    cursor: pointer;
    overflow: hidden;
    aspect-ratio: 1;
    background: var(--green-dark);
}

.album-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.8s cubic-bezier(0.22, 1, 0.36, 1), filter 0.5s;
}

.album-card:hover img {
    transform: scale(1.08);
    filter: brightness(0.6);
}

.album-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 2rem 1.5rem;
    background: linear-gradient(to top, rgba(10,10,10,0.95) 0%, transparent 100%);
    transform: translateY(30%);
    opacity: 0;
    transition: all 0.5s cubic-bezier(0.22, 1, 0.36, 1);
}

.album-card:hover .album-info {
    transform: translateY(0);
    opacity: 1;
}

.album-info h3 {
    font-family: 'AkzidenzGroteskPro', 'Playfair Display', serif;
    font-weight: 500;
    font-size: 1.1rem;
    color: var(--white);
    margin-bottom: 0.3rem;
    letter-spacing: 0.03em;
}

.album-info span {
    font-size: 0.75rem;
    color: var(--cream);
    opacity: 0.7;
    letter-spacing: 0.05em;
}

.album-featured {
    grid-column: 1 / 3;
    grid-row: 1 / 3;
    aspect-ratio: auto;
}

@media (max-width: 900px) {
    .albums-grid { grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
    .album-featured { grid-column: 1 / -1; grid-row: auto; }
}

@media (max-width: 600px) {
    .albums-grid { grid-template-columns: 1fr; }
}
</style>
