<script setup lang="ts">
const galleryImages = [
    { src: 'https://harryskoler.com/images/media/thumbnails/wayland/harry-skoler-wayland-ma-1.jpg', caption: 'Wayland, MA 2023 — Photo by Jean-Pierre Ducondi' },
    { src: 'https://harryskoler.com/images/about/side-image-3.jpg', caption: 'Harry Skoler' },
    { src: 'https://harryskoler.com/images/media/thumbnails/wayland/harry-skoler-wayland-ma-2.jpg', caption: 'Wayland, MA 2023 — Photo by Jean-Pierre Ducondi' },
    { src: 'https://harryskoler.com/images/cartoon.jpg', caption: "How Harry's life was influenced by Charles Mingus — Artwork by Dave Chisholm" },
    { src: 'https://harryskoler.com/images/media/thumbnails/wayland/harry-skoler-wayland-ma-3.jpg', caption: 'Wayland, MA 2023 — Photo by Jean-Pierre Ducondi' },
    { src: 'https://harryskoler.com/images/about/side-image-1.jpg', caption: 'Harry Skoler' },
];
</script>

<template>
    <section id="gallery" class="section section-green">
        <div class="section-header reveal">
            <span class="section-label">Visual</span>
            <h2 class="section-title">Gallery</h2>
            <div class="section-divider"></div>
        </div>
        <div class="gallery-masonry">
            <div class="gallery-item reveal" v-for="(img, i) in galleryImages" :key="i">
                <img :src="img.src" :alt="img.caption" loading="lazy">
                <div class="gallery-caption">{{ img.caption }}</div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.gallery-masonry {
    max-width: 1200px;
    margin: 0 auto;
    columns: 3;
    column-gap: 1.5rem;
}

.gallery-item {
    break-inside: avoid;
    margin-bottom: 1.5rem;
    overflow: hidden;
    position: relative;
    cursor: pointer;
}

.gallery-item img {
    width: 100%;
    display: block;
    transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
    filter: grayscale(30%);
}

.gallery-item:hover img {
    transform: scale(1.05);
    filter: grayscale(0%);
}

.gallery-item .gallery-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 1.5rem;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    opacity: 0;
    transition: opacity 0.4s;
    font-size: 0.8rem;
    color: var(--cream);
}

.gallery-item:hover .gallery-caption { opacity: 1; }

@media (max-width: 900px) {
    .gallery-masonry { columns: 2; }
}

@media (max-width: 600px) {
    .gallery-masonry { columns: 1; }
}
</style>
