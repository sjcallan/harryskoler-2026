import { ref, onMounted, onUnmounted } from 'vue';

export interface NavLink {
    id: string;
    label: string;
}

export function useScrollTracking(navLinks: NavLink[]) {
    const scrollY = ref(0);
    const activeSection = ref('home');

    function measureNavOffset() {
        const nav = document.querySelector('.site-nav');
        if (nav) {
            const h = nav.getBoundingClientRect().height + 16;
            document.documentElement.style.setProperty('--scroll-offset', h + 'px');
        }
    }

    function onScroll() {
        scrollY.value = window.scrollY;
        updateActiveSection();
        revealElements();
    }

    function updateActiveSection() {
        const sections = navLinks.map(l => l.id);
        for (let i = sections.length - 1; i >= 0; i--) {
            const el = document.getElementById(sections[i]);
            if (el && el.getBoundingClientRect().top <= 200) {
                if (activeSection.value !== sections[i]) {
                    activeSection.value = sections[i];
                    history.replaceState(null, '', `#${sections[i]}`);
                }
                break;
            }
        }
    }

    function revealElements() {
        document.querySelectorAll('.reveal').forEach(el => {
            const rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight * 0.85) {
                el.classList.add('visible');
            }
        });
    }

    function scrollToSection(id: string) {
        const el = document.getElementById(id);
        if (el) el.scrollIntoView({ behavior: 'smooth' });
    }

    onMounted(() => {
        measureNavOffset();
        window.addEventListener('scroll', onScroll, { passive: true });
        setTimeout(revealElements, 300);

        const hash = window.location.hash.replace('#', '');
        if (hash && navLinks.some(l => l.id === hash)) {
            let lastHeight = 0;
            let attempts = 0;
            const maxAttempts = 10;
            const interval = 300;

            function attemptScroll() {
                const el = document.getElementById(hash);
                if (!el) return;
                const currentHeight = document.documentElement.scrollHeight;
                if (currentHeight !== lastHeight || attempts === 0) {
                    lastHeight = currentHeight;
                    scrollToSection(hash);
                }
                attempts++;
                if (attempts < maxAttempts) {
                    setTimeout(attemptScroll, interval);
                }
            }
            requestAnimationFrame(attemptScroll);
        }
    });

    onUnmounted(() => {
        window.removeEventListener('scroll', onScroll);
    });

    return { scrollY, activeSection, scrollToSection };
}
