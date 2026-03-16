<template>
    <section id="events" class="section section-dark">
        <div class="section-header reveal">
            <span class="section-label">Live</span>
            <h2 class="section-title">Events</h2>
            <div class="section-divider"></div>
        </div>
        <div class="events-wrap reveal">
            <div class="events-placeholder">
                <p>Upcoming performances to be announced</p>
                <span>Stay tuned for upcoming events — follow Harry on social media for the latest updates</span>
            </div>
        </div>
    </section>
</template>

<style scoped>
.events-wrap {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}

.events-placeholder {
    padding: 4rem 2rem;
    border: 1px dashed rgba(184, 40, 46, 0.3);
    border-radius: 4px;
}

.events-placeholder p {
    font-family: 'Instrument Serif', serif;
    font-size: 1.5rem;
    color: var(--cream);
    font-style: italic;
}

.events-placeholder span {
    display: block;
    margin-top: 1rem;
    font-size: 0.85rem;
    color: rgba(232,224,214,0.5);
}
</style>
