<script setup lang="ts">
withDefaults(defineProps<{ theme?: string }>(), { theme: 'section-dark' });
</script>

<template>
    <section id="about" :class="['section', theme]">
        <div class="section-header reveal">
            <span class="section-label">Biography</span>
            <h2 class="section-title">About Harry</h2>
            <div class="section-divider"></div>
        </div>
        <div class="about-grid">
            <div class="about-text reveal">
                <p>Jazz clarinetist Harry Skoler is Professor of Woodwinds at Boston's prestigious Berklee College of Music. He received his Bachelor of Music degree from Berklee, and a Master of Music degree from New England Conservatory, where he studied with legendary jazz great Jimmy Giuffre.</p>
                <div class="about-highlight">
                    "The most important thing for jazz musicians is if they can be recognized after a few notes. Skoler is clearly recognizable!"
                    <br><small class="about-highlight-source">— Lajos Dudas, Jazzpodium</small>
                </div>
                <p>His recordings include <em>Conversations in the Language of Jazz</em>, <em>Reflections on the Art of Swing</em>, <em>A Work of Heart</em>, <em>Two Ones</em>, <em>Living In Sound: The Music of Charles Mingus</em>, <em>Red Brick Hill</em>, and now the new album <em>Echoes</em> featuring Bill Frisell, Dezron Douglas, and Johnathan Blake.</p>
                <p>Performances include national jazz clubs and festivals, including the Blue Note in NYC, Regattabar and Scullers in Boston, International Clarinet Association's ClarinetFest, Cape May Jazz Festival, and tours in Costa Rica, Japan, Mexico, and Norway.</p>
                <p>Endorsements include Buffet Crampon and Vandoren. He lives in Bradford, MA with his wife Joanne.</p>
                <div class="endorsements">
                    <a href="https://vandoren.fr/en/artistes-vandoren/skoler-harry/" target="_blank">
                        <img src="/assets/images/logos/vandoren-logo.svg" alt="Vandoren" class="endorsement-logo">
                    </a>
                    <a href="http://www.buffet-crampon.com/en/artist/harry-skoler/" target="_blank">
                        <img src="/assets/images/logos/buffet-logo.svg" alt="Buffet Crampon" class="endorsement-logo">
                    </a>
                </div>
            </div>
            <div class="about-sidebar reveal">
                <div class="about-image-stack">
                    <img src="/assets/images/about/side-image-1.jpg" alt="Harry Skoler" class="about-img about-img-1">
                    <img src="/assets/images/about/side-image-2.jpg" alt="Harry Skoler performing" class="about-img about-img-2">
                    <img src="/assets/images/about/side-image-3.jpg" alt="Harry Skoler" class="about-img about-img-3">
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.about-grid {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 5rem;
    align-items: start;
}

.about-text {
    font-size: 1.05rem;
    line-height: 1.9;
    color: var(--cream);
    font-weight: 300;
}

.about-text p + p { margin-top: 1.5rem; }

.about-highlight {
    font-family: 'Instrument Serif', serif;
    font-size: 2rem;
    font-style: italic;
    color: var(--white);
    line-height: 1.4;
    border-left: 3px solid var(--red);
    padding-left: 2rem;
    margin: 2rem 0;
}

.about-highlight-source {
    color: var(--red);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.7rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    font-style: normal;
}

.about-sidebar {
    position: sticky;
    top: 120px;
}

.about-image-stack {
    position: relative;
    height: 720px;
}

.about-img {
    width: 220px;
    height: 300px;
    object-fit: cover;
    object-position: center 15%;
    position: absolute;
    border: 5px solid rgba(255, 255, 255, 0.12);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4), 0 2px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.4s ease, box-shadow 0.4s ease;
}

.about-img:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.5), 0 4px 12px rgba(0, 0, 0, 0.3);
}

.about-img-1 {
    top: 0;
    left: 0;
    z-index: 3;
    transform: rotate(-4deg);
}
.about-img-1:hover { transform: rotate(-4deg) scale(1.03); }

.about-img-2 {
    top: 200px;
    left: 50%;
    transform: translateX(-50%) rotate(3deg);
    z-index: 2;
}
.about-img-2:hover { transform: translateX(-50%) rotate(3deg) scale(1.03); }

.about-img-3 {
    top: 400px;
    left: 10px;
    z-index: 1;
    transform: rotate(-2.5deg);
}
.about-img-3:hover { transform: rotate(-2.5deg) scale(1.03); }

.endorsements {
    display: flex;
    gap: 3rem;
    margin-top: 3rem;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
}

.endorsement-logo {
    height: 70px;
    opacity: 0.6;
    filter: brightness(0) invert(1);
    transition: opacity 0.3s;
}
.endorsement-logo:hover { opacity: 1; }

@media (max-width: 900px) {
    .about-grid { grid-template-columns: 1fr; gap: 3rem; }

    .about-image-stack {
        height: 620px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .about-img {
        position: relative;
        top: auto !important;
        left: auto !important;
        width: 200px;
        height: 270px;
    }

    .about-img-2 {
        transform: rotate(3deg);
    }
    .about-img-2:hover { transform: rotate(3deg) scale(1.03); }
}
</style>
