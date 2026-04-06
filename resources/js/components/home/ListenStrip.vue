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
            <a href="https://music.apple.com/us/album/echoes/1890977854" target="_blank" class="cta-btn cta-btn-outline">Apple Music</a>
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
