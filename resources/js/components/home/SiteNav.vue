<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import SignatureLogo from '@/components/SignatureLogo.vue';
import type { NavLink } from '@/composables/useScrollTracking';

const props = withDefaults(defineProps<{
    scrollY: number;
    activeSection?: string;
    navLinks: NavLink[];
    scrollToSection?: (id: string) => void;
    subpage?: boolean;
}>(), {
    activeSection: '',
    subpage: false,
});

const mobileOpen = ref(false);

function handleLogoClick(event: Event) {
    if (!props.subpage) {
        event.preventDefault();
        props.scrollToSection?.('home');
    }
    mobileOpen.value = false;
}

function handleNavClick(event: Event, id: string) {
    mobileOpen.value = false;
    if (!props.subpage && props.scrollToSection) {
        event.preventDefault();
        props.scrollToSection(id);
    }
}
</script>

<template>
    <nav class="site-nav" :class="{ scrolled: subpage || scrollY > 80 }">
        <component
            :is="subpage ? Link : 'a'"
            :href="subpage ? '/' : '#home'"
            class="nav-logo"
            @click="handleLogoClick"
        >
            <SignatureLogo width="160" color="#ffffff" />
        </component>
        <button class="mobile-toggle" :class="{ open: mobileOpen }" @click="mobileOpen = !mobileOpen">
            <span></span><span></span><span></span>
        </button>
        <ul class="nav-links" :class="{ 'mobile-open': mobileOpen }">
            <li v-for="link in navLinks" :key="link.id">
                <component
                    :is="subpage ? Link : 'a'"
                    :href="subpage ? `/#${link.id}` : `#${link.id}`"
                    :class="{ active: !subpage && activeSection === link.id }"
                    @click="handleNavClick($event, link.id)"
                >
                    {{ link.label }}
                </component>
            </li>
        </ul>
    </nav>
</template>

<style scoped>
.site-nav {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    padding: 1.2rem 3rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.5s cubic-bezier(0.22, 1, 0.36, 1);
    mix-blend-mode: normal;
}

.site-nav.scrolled {
    background: rgba(10, 10, 10, 0.92);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    padding: 0.8rem 3rem;
    border-bottom: 1px solid rgba(184, 40, 46, 0.2);
}

.nav-logo {
    display: flex;
    align-items: center;
    text-decoration: none;
    transition: opacity 0.3s;
    margin: 0 0 -1rem 0;
    position: relative;
    z-index: 1;
}
.nav-logo:hover { opacity: 0.8; }

.nav-links {
    display: flex;
    gap: 2.2rem;
    list-style: none;
    align-items: center;
}

.nav-links a {
    color: var(--cream);
    text-decoration: none;
    font-size: 0.78rem;
    font-weight: 500;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    position: relative;
    padding-bottom: 2px;
    transition: color 0.3s;
}

.nav-links a::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0%;
    height: 1px;
    background: var(--white);
    transition: width 0.4s cubic-bezier(0.22, 1, 0.36, 1);
}

.nav-links a:hover { color: var(--white); }
.nav-links a:hover::after { width: 100%; }
.nav-links a.active { color: var(--white); }
.nav-links a.active::after { width: 100%; }

.mobile-toggle {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    width: 32px;
    height: 24px;
    position: relative;
    z-index: 1001;
}
.mobile-toggle span {
    display: block;
    width: 100%;
    height: 2px;
    background: var(--white);
    position: absolute;
    left: 0;
    transition: all 0.3s;
}
.mobile-toggle span:nth-child(1) { top: 0; }
.mobile-toggle span:nth-child(2) { top: 50%; transform: translateY(-50%); }
.mobile-toggle span:nth-child(3) { bottom: 0; }
.mobile-toggle.open span:nth-child(1) { top: 50%; transform: translateY(-50%) rotate(45deg); }
.mobile-toggle.open span:nth-child(2) { opacity: 0; }
.mobile-toggle.open span:nth-child(3) { bottom: 50%; transform: translateY(50%) rotate(-45deg); }

@media (max-width: 900px) {
    .site-nav { padding: 1rem 1.5rem; }
    .nav-links { display: none; }
    .mobile-toggle { display: block; }
    .nav-links.mobile-open {
        display: flex;
        flex-direction: column;
        position: fixed;
        inset: 0;
        background: rgba(10,10,10,0.97);
        justify-content: center;
        align-items: center;
        gap: 2rem;
        z-index: 999;
    }
    .nav-links.mobile-open a { font-size: 1.2rem; }
}
</style>
