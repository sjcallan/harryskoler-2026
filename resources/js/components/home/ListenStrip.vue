<script setup lang="ts">
import { useIsMacDesktop } from '@/composables/useIsMacDesktop';

const { isMacDesktop } = useIsMacDesktop();
</script>

<template>
    <div class="listen-strip">
        <p>Now Available — Listen to <em>Echoes</em></p>
        <div class="listen-links">
            <a href="https://harryskoler.bandcamp.com/" target="_blank" class="cta-btn">Bandcamp</a>
            <a href="https://music.apple.com/" target="_blank" class="cta-btn cta-btn-outline">Apple Music</a>
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
    gap: 3rem;
    flex-wrap: wrap;
    position: relative;
    z-index: 1;
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
