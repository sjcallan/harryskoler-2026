import { ref, onMounted, onUnmounted } from 'vue';

export interface NavLink {
    id: string;
    label: string;
}

export function useScrollTracking(navLinks: NavLink[]) {
    const scrollY = ref(0);
    const activeSection = ref('home');

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
        window.addEventListener('scroll', onScroll, { passive: true });
        setTimeout(revealElements, 300);

        const hash = window.location.hash.replace('#', '');
        if (hash && navLinks.some(l => l.id === hash)) {
            setTimeout(() => scrollToSection(hash), 400);
        }
    });

    onUnmounted(() => {
        window.removeEventListener('scroll', onScroll);
    });

    return { scrollY, activeSection, scrollToSection };
}
