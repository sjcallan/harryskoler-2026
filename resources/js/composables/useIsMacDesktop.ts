import { ref, onMounted } from 'vue';

const isMacDesktop = ref(false);
let detected = false;

function detect() {
    if (detected) return;
    detected = true;

    if (typeof navigator === 'undefined') return;

    const platform = (navigator as any).userAgentData?.platform ?? navigator.platform ?? '';
    const isMacPlatform = /^Mac/i.test(platform) || platform === 'macOS';
    const hasTouch = navigator.maxTouchPoints > 0;

    isMacDesktop.value = isMacPlatform && !hasTouch;
}

export function useIsMacDesktop() {
    onMounted(detect);
    return { isMacDesktop };
}
