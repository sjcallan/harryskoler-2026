<script setup lang="ts">
import { useIsMacDesktop } from '@/composables/useIsMacDesktop';

const { isMacDesktop } = useIsMacDesktop();
</script>

<template>
    <div class="listen-strip">
        <a href="/album/echoes" class="listen-strip-cover">
            <img src="/images/albums/echoes.png" alt="Echoes" />
        </a>
        <p>Pre-Order <em>Echoes</em> — Available May 1, 2026</p>
        <div class="listen-links">
            <a href="https://harryskoler1.bandcamp.com/album/echoes" target="_blank" class="cta-btn">Pre-Order on Bandcamp</a>
            <a href="https://music.apple.com/us/album/echoes/1890977854" target="_blank" class="cta-btn cta-btn-outline"><svg class="apple-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg> Apple Music</a>
            <a href="/album/echoes" class="cta-btn cta-btn-outline">Explore the Album</a>
        </div>
        <p v-if="isMacDesktop" class="apple-music-note">Mac users: opens in Music app. To purchase, select iTunes Store inside Music.</p>
    </div>
</template>

<style scoped>
.listen-strip {
    background: var(--red-dark);
    padding: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
    position: relative;
    z-index: 1;
}

.listen-strip-cover {
    flex-shrink: 0;
}

.listen-strip-cover img {
    width: 64px;
    height: 64px;
    object-fit: cover;
    display: block;
    box-shadow: 0 4px 16px rgba(0,0,0,0.3);
}

.listen-strip p {
    font-family: 'AkzidenzGroteskPro', 'Instrument Serif', serif;
    font-weight: 500;
    font-size: 1.3rem;
    color: var(--white);
    letter-spacing: 0.03em;
}

.listen-links {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.apple-music-note {
    width: 100%;
    text-align: center;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.72rem;
    color: rgba(255, 255, 255, 0.55);
    font-style: italic;
    letter-spacing: 0.02em;
    margin-top: -0.5rem;
}
</style>
