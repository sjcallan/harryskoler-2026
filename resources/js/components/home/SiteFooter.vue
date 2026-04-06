<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { NavLink } from '@/composables/useScrollTracking';
import { useIsMacDesktop } from '@/composables/useIsMacDesktop';

const { isMacDesktop } = useIsMacDesktop();

withDefaults(defineProps<{
    navLinks: NavLink[];
    scrollToSection?: (id: string) => void;
    subpage?: boolean;
}>(), {
    subpage: false,
});

const socialLinks = [
    { href: 'https://harryskoler1.bandcamp.com/album/echoes', label: 'Bandcamp', icon: 'bandcamp' },
    { href: 'https://www.facebook.com/HarrySkolerJazzClarinet', label: 'Facebook', icon: 'facebook' },
    { href: 'https://www.instagram.com/harryskoler/', label: 'Instagram', icon: 'instagram' },
    { href: 'https://music.apple.com/us/artist/harry-skoler/287182016', label: 'Apple Music', icon: 'apple' },
    { href: 'https://open.spotify.com/artist/0cVzLgjFM1aMprSetcI2y4?si=4zN2ZBLEQNmCl94UWl1E3w&dl_branch=1', label: 'Spotify', icon: 'spotify' },
];
</script>

<template>
    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-col footer-col--brand">
                <component :is="subpage ? Link : 'div'" :href="subpage ? '/' : undefined" class="footer-brand">
                    Harry <span>Skoler</span>
                </component>
                <div class="footer-tagline">Jazz Clarinetist · Berklee College of Music</div>

                <ul class="social-links">
                    <li v-for="link in socialLinks" :key="link.icon">
                        <a :href="link.href" target="_blank" rel="noopener noreferrer" :aria-label="link.label">
                            <!-- Bandcamp -->
                            <svg v-if="link.icon === 'bandcamp'" viewBox="0 0 512 512" fill="currentColor"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm48.2 326.4H113.6L207.8 177.6h190.6L304.2 334.4z"/></svg>
                            <!-- Facebook -->
                            <svg v-else-if="link.icon === 'facebook'" viewBox="0 0 448 512" fill="currentColor"><path d="M400 32H48A48 48 0 0 0 0 80v352a48 48 0 0 0 48 48h137.25V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.27c-30.81 0-40.42 19.12-40.42 38.73V256h68.78l-11 71.69h-57.78V480H400a48 48 0 0 0 48-48V80a48 48 0 0 0-48-48z"/></svg>
                            <!-- Instagram -->
                            <svg v-else-if="link.icon === 'instagram'" viewBox="0 0 448 512" fill="currentColor"><path d="M224 202.66A53.34 53.34 0 1 0 277.36 256 53.38 53.38 0 0 0 224 202.66zm124.71-41a54 54 0 0 0-30.41-30.41c-21-8.29-71-6.43-94.3-6.43s-73.25-1.93-94.31 6.43a54 54 0 0 0-30.41 30.41c-8.28 21-6.43 71.05-6.43 94.33s-1.85 73.27 6.47 94.34a54 54 0 0 0 30.41 30.41c21 8.29 71 6.43 94.31 6.43s73.24 1.93 94.3-6.43a54 54 0 0 0 30.41-30.41c8.35-21 6.43-71.05 6.43-94.33s1.92-73.26-6.43-94.33zM224 338a82 82 0 1 1 82-82 81.9 81.9 0 0 1-82 82zm85.38-148.3a19.14 19.14 0 1 1 19.13-19.14 19.1 19.1 0 0 1-19.09 19.18zM400 32H48A48 48 0 0 0 0 80v352a48 48 0 0 0 48 48h352a48 48 0 0 0 48-48V80a48 48 0 0 0-48-48zm-17.12 290c-1.29 25.63-7.14 48.34-25.85 67s-41.4 24.63-67 25.85c-26.41 1.49-105.59 1.49-132 0-25.63-1.29-48.26-7.15-67-25.85s-24.63-41.42-25.85-67c-1.49-26.42-1.49-105.61 0-132 1.29-25.63 7.07-48.34 25.85-67s41.47-24.56 67-25.78c26.41-1.49 105.59-1.49 132 0 25.63 1.29 48.33 7.15 67 25.85s24.63 41.42 25.85 67.05c1.49 26.32 1.49 105.44 0 131.88z"/></svg>
                            <!-- Apple -->
                            <svg v-else-if="link.icon === 'apple'" viewBox="0 0 384 512" fill="currentColor"><path d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z"/></svg>
                            <!-- Spotify -->
                            <svg v-else-if="link.icon === 'spotify'" viewBox="0 0 496 512" fill="currentColor"><path d="M248 8C111.1 8 0 119.1 0 256s111.1 248 248 248 248-111.1 248-248S384.9 8 248 8zm100.7 364.9c-4.2 0-6.8-1.3-10.7-3.6-62.4-37.6-135-39.2-206.7-24.5-3.9 1-9 2.6-11.9 2.6-9.7 0-15.8-7.7-15.8-15.8 0-10.3 6.1-15.2 13.6-16.8 81.9-18.1 165.6-16.5 237 26.2 6.1 3.9 9.7 7.4 9.7 16.5s-7.1 15.4-15.2 15.4zm27.8-57.6c-5.5 0-8.7-2.3-12.3-4.2-62.5-37-155.7-51.9-238.6-29.4-4.8 1.3-7.4 2.6-11.9 2.6-10.7 0-19.4-8.7-19.4-19.4s5.2-17.8 15.5-20.7c27.8-7.8 56.2-13.6 97.8-13.6 64.9 0 127.6 16.1 177 45.5 8.1 4.8 11.3 11 11.3 19.7-.1 10.8-8.5 19.5-19.4 19.5zm31.1-64.3c-5.2 0-8.7-1.3-12.9-3.9-71.2-42.5-198.5-52.7-280.9-29.7-3.6 1-8.1 2.6-12.9 2.6-13.2 0-23.3-10.3-23.3-23.6 0-13.6 8.4-21.3 17.4-23.9 35.2-10.3 74.6-15.2 117.5-15.2 73 0 149.5 15.2 205.4 47.8 7.8 4.5 12.9 10.7 12.9 22.6 0 13.6-11 23.3-23.2 23.3z"/></svg>
                        </a>
                    </li>
                </ul>
                <p v-if="isMacDesktop" class="apple-music-note">Mac users: opens in Music app. To purchase, select iTunes Store inside Music.</p>
            </div>

            <div class="footer-col footer-col--rep">
                <strong class="footer-heading">Artist Representation</strong>
                <address class="footer-address">
                    Joanne Alicandro<br>
                    58 Colby Street<br>
                    Bradford, MA 01835<br>
                    <a href="tel:+19784951506">(978) 495-1506</a><br>
                    <a href="mailto:jmalicandro@gmail.com">jmalicandro@gmail.com</a>
                </address>
            </div>

            <div class="footer-col footer-col--nav">
                <ul class="footer-nav">
                    <li v-for="link in navLinks" :key="link.id">
                        <component
                            :is="subpage ? Link : 'a'"
                            :href="subpage ? `/#${link.id}` : `#${link.id}`"
                            @click.prevent="!subpage && scrollToSection?.(link.id)"
                        >
                            {{ link.label }}
                        </component>
                    </li>
                </ul>
            </div>

            <div class="footer-bottom">
                <div class="footer-photo-credit">
                    Unless otherwise noted, all photos credited to Francesco Gargiulli
                </div>
                <div class="footer-copy">
                    &copy; {{ new Date().getFullYear() }} Harry Skoler. All Rights Reserved.
                </div>
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
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 3rem;
}

.footer-col--brand { grid-column: 1; }
.footer-col--rep   { grid-column: 2; }
.footer-col--nav   { grid-column: 3; text-align: right; }

.footer-brand {
    font-family: 'AkzidenzGroteskPro', 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--white);
    margin-bottom: 0.5rem;
    text-decoration: none;
    display: block;
}
.footer-brand span { color: var(--red); }

.footer-tagline {
    font-size: 0.8rem;
    color: rgba(232,224,214,0.4);
    font-style: italic;
    margin-bottom: 1.25rem;
}

/* Social icons */
.social-links {
    display: flex;
    gap: 0.75rem;
    list-style: none;
    padding: 0;
    margin: 0;
}

.social-links a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    color: rgba(232,224,214,0.45);
    transition: color 0.3s, transform 0.3s;
}

.social-links a:hover {
    color: var(--red);
    transform: translateY(-2px);
}

.social-links svg {
    width: 1.15rem;
    height: 1.15rem;
}

.apple-music-note {
    margin-top: 0.5rem;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.68rem;
    color: rgba(232, 224, 214, 0.4);
    font-style: italic;
    letter-spacing: 0.02em;
}

/* Representation */
.footer-heading {
    display: block;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--red);
    margin-bottom: 0.6rem;
}

.footer-address {
    font-style: normal;
    font-size: 0.78rem;
    line-height: 1.7;
    color: rgba(232,224,214,0.5);
}

.footer-address a {
    color: rgba(232,224,214,0.5);
    text-decoration: none;
    transition: color 0.3s;
}
.footer-address a:hover { color: var(--red); }

/* Nav */
.footer-nav {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
    list-style: none;
    padding: 0;
    margin: 0;
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

/* Bottom row */
.footer-bottom {
    grid-column: 1 / -1;
    border-top: 1px solid rgba(255,255,255,0.04);
    padding-top: 2rem;
    margin-top: 1rem;
    text-align: center;
}

.footer-photo-credit {
    font-size: 0.72rem;
    color: rgba(232,224,214,0.35);
    letter-spacing: 0.04em;
    font-style: italic;
    margin-bottom: 0.6rem;
}

.footer-copy {
    font-size: 0.72rem;
    color: rgba(232,224,214,0.3);
    letter-spacing: 0.08em;
}

/* Responsive */
@media (max-width: 768px) {
    .site-footer { padding: 3rem 1.5rem; }

    .footer-inner {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .footer-col--brand,
    .footer-col--rep,
    .footer-col--nav {
        grid-column: 1;
    }

    .footer-col--nav { text-align: left; }

    .footer-nav {
        flex-direction: row;
        flex-wrap: wrap;
        gap: 1rem 2rem;
    }
}

@media (max-width: 480px) {
    .site-footer { padding: 2rem 1rem; }

    .footer-inner { gap: 1.5rem; }

    .footer-brand { font-size: 1.25rem; }

    .footer-nav { gap: 0.75rem 1.5rem; }

    .footer-bottom { padding-top: 1.5rem; }
}
</style>
