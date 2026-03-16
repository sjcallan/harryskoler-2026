<script setup lang="ts">
import type { NavLink } from '@/composables/useScrollTracking';

defineProps<{
    navLinks: NavLink[];
    scrollToSection: (id: string) => void;
}>();
</script>

<template>
    <footer class="site-footer">
        <div class="footer-inner">
            <div>
                <div class="footer-brand">Harry <span>Skoler</span></div>
                <div class="footer-tagline">Jazz Clarinetist · Berklee College of Music</div>
            </div>
            <div class="footer-rep">
                <strong>Artist Representation</strong>
                Joanne Alicandro
            </div>
            <ul class="footer-nav">
                <li v-for="link in navLinks" :key="link.id">
                    <a :href="'#' + link.id" @click.prevent="scrollToSection(link.id)">{{ link.label }}</a>
                </li>
            </ul>
            <div class="footer-copy">
                &copy; 2026 Harry Skoler. All Rights Reserved.
            </div>
        </div>
    </footer>
</template>

<style scoped>
.site-footer {
    position: relative;
    z-index: 1;
    background: var(--black);
    border-top: 1px solid rgba(255,255,255,0.06);
    padding: 4rem 3rem;
}

.footer-inner {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: start;
    flex-wrap: wrap;
    gap: 3rem;
}

.footer-brand {
    font-family: 'AkzidenzGroteskPro', 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--white);
    margin-bottom: 0.5rem;
}
.footer-brand span { color: var(--red); }

.footer-tagline {
    font-size: 0.8rem;
    color: rgba(232,224,214,0.4);
    font-style: italic;
}

.footer-nav {
    display: flex;
    gap: 2rem;
    list-style: none;
    flex-wrap: wrap;
}

.footer-nav a {
    color: rgba(232,224,214,0.5);
    text-decoration: none;
    font-size: 0.78rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    transition: color 0.3s;
}
.footer-nav a:hover { color: var(--red); }

.footer-copy {
    width: 100%;
    text-align: center;
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255,255,255,0.04);
    font-size: 0.72rem;
    color: rgba(232,224,214,0.3);
    letter-spacing: 0.08em;
}

.footer-rep {
    font-size: 0.78rem;
    color: rgba(232,224,214,0.5);
}
.footer-rep strong {
    display: block;
    font-size: 0.7rem;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--red);
    margin-bottom: 0.3rem;
}
</style>
