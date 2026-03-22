<script setup lang="ts">
defineProps<{
    src: string;
    alt?: string;
}>();
</script>

<template>
    <section class="image-break">
        <img
            :src="src"
            :alt="alt || ''"
            class="image-break__img"
            loading="lazy"
        />
    </section>
</template>

<style scoped>
.image-break {
    width: 100%;
    line-height: 0;
}

.image-break__img {
    display: block;
    width: 100%;
    max-width: 100%;
    height: auto;
}
</style>
