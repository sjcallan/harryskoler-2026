<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Spinner } from '@/components/ui/spinner';
import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Search,
    Plus,
    Pencil,
    Trash2,
    ExternalLink,
    Quote,
    AlertCircle,
    GripVertical,
    Disc3,
} from 'lucide-vue-next';

const albumOptions: { slug: string; title: string }[] = [
    { slug: 'echoes', title: 'Echoes' },
    { slug: 'red-brick-hill', title: 'Red Brick Hill' },
    { slug: 'living-in-sound', title: 'Living In Sound' },
    { slug: 'two-ones', title: 'Two Ones' },
    { slug: 'work-of-heart', title: 'A Work of Heart' },
    { slug: 'reflections', title: 'Reflections on the Art of Swing' },
    { slug: 'conversations', title: 'Conversations in the Language of Jazz' },
];

const albumTitleMap: Record<string, string> = Object.fromEntries(
    albumOptions.map((a) => [a.slug, a.title]),
);

interface ReviewItem {
    id: number;
    album_slug: string;
    excerpt: string;
    body: string;
    author: string | null;
    publication: string;
    source_url: string | null;
    sort_order: number;
    created_at: string;
    updated_at: string;
}

const reviews = ref<ReviewItem[]>([]);
const loading = ref(true);
const searchQuery = ref('');
const albumFilter = ref('all');
const showForm = ref(false);
const showDeleteConfirm = ref(false);
const saving = ref(false);
const deleting = ref(false);
const editingItem = ref<ReviewItem | null>(null);
const deletingItem = ref<ReviewItem | null>(null);
const errors = ref<Record<string, string[]>>({});
const flashMessage = ref('');
const flashType = ref<'success' | 'error'>('success');

const form = ref({
    album_slug: '',
    excerpt: '',
    body: '',
    author: '',
    publication: '',
    source_url: '',
    sort_order: 0,
});

const filteredReviews = computed(() => {
    let result = reviews.value;

    if (albumFilter.value && albumFilter.value !== 'all') {
        result = result.filter((item) => item.album_slug === albumFilter.value);
    }

    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        result = result.filter(
            (item) =>
                item.excerpt.toLowerCase().includes(q) ||
                item.body.toLowerCase().includes(q) ||
                (item.author?.toLowerCase().includes(q) ?? false) ||
                item.publication.toLowerCase().includes(q),
        );
    }

    return result;
});

function getCsrfToken(): string {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta?.getAttribute('content') ?? '';
}

async function fetchReviews() {
    loading.value = true;
    try {
        const res = await fetch('/api/reviews');
        reviews.value = await res.json();
    } catch {
        flash('Failed to load reviews.', 'error');
    } finally {
        loading.value = false;
    }
}

function flash(message: string, type: 'success' | 'error' = 'success') {
    flashMessage.value = message;
    flashType.value = type;
    setTimeout(() => (flashMessage.value = ''), 4000);
}

function openCreateForm() {
    editingItem.value = null;
    errors.value = {};
    form.value = { album_slug: '', excerpt: '', body: '', author: '', publication: '', source_url: '', sort_order: 0 };
    showForm.value = true;
}

function openEditForm(item: ReviewItem) {
    editingItem.value = item;
    errors.value = {};
    form.value = {
        album_slug: item.album_slug,
        excerpt: item.excerpt,
        body: item.body,
        author: item.author ?? '',
        publication: item.publication,
        source_url: item.source_url ?? '',
        sort_order: item.sort_order,
    };
    showForm.value = true;
}

function confirmDelete(item: ReviewItem) {
    deletingItem.value = item;
    showDeleteConfirm.value = true;
}

async function saveReview() {
    saving.value = true;
    errors.value = {};

    const payload: Record<string, string | number> = {
        album_slug: form.value.album_slug,
        excerpt: form.value.excerpt,
        body: form.value.body,
        publication: form.value.publication,
        sort_order: form.value.sort_order,
    };
    if (form.value.author) payload.author = form.value.author;
    if (form.value.source_url) payload.source_url = form.value.source_url;

    const url = editingItem.value
        ? `/api/reviews/${editingItem.value.id}`
        : '/api/reviews';

    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        if (!res.ok) {
            if (res.status === 422) {
                const data = await res.json();
                errors.value = data.errors ?? {};
            } else {
                flash('Something went wrong. Please try again.', 'error');
            }
            return;
        }

        showForm.value = false;
        flash(editingItem.value ? 'Review updated.' : 'Review created.');
        await fetchReviews();
    } catch {
        flash('Network error. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
}

async function deleteReview() {
    if (!deletingItem.value) return;
    deleting.value = true;

    try {
        const res = await fetch(`/api/reviews/${deletingItem.value.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
            },
        });

        if (!res.ok) {
            flash('Failed to delete review.', 'error');
            return;
        }

        showDeleteConfirm.value = false;
        flash('Review deleted.');
        await fetchReviews();
    } catch {
        flash('Network error. Please try again.', 'error');
    } finally {
        deleting.value = false;
    }
}

onMounted(fetchReviews);
</script>

<template>
    <Card>
        <CardHeader class="flex-row items-center justify-between space-y-0">
            <CardTitle class="text-xl font-semibold">Reviews</CardTitle>
            <Button size="sm" @click="openCreateForm">
                <Plus class="size-4" />
                Add Review
            </Button>
        </CardHeader>
        <CardContent class="space-y-4">
            <!-- Flash message -->
            <div
                v-if="flashMessage"
                :class="[
                    'flex items-center gap-2 rounded-md border px-4 py-3 text-sm',
                    flashType === 'success'
                        ? 'border-green-200 bg-green-50 text-green-800 dark:border-green-800 dark:bg-green-950 dark:text-green-200'
                        : 'border-red-200 bg-red-50 text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-200',
                ]"
            >
                <AlertCircle class="size-4 shrink-0" />
                {{ flashMessage }}
            </div>

            <!-- Search & Album Filter -->
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <Search class="text-muted-foreground absolute top-1/2 left-3 size-4 -translate-y-1/2" />
                    <Input
                        v-model="searchQuery"
                        placeholder="Search reviews..."
                        class="pl-9"
                    />
                </div>
                <Select v-model="albumFilter">
                    <SelectTrigger class="w-full sm:w-[260px]">
                        <div class="flex items-center gap-2">
                            <Disc3 class="text-muted-foreground size-3.5 shrink-0" />
                            <SelectValue placeholder="All Albums" />
                        </div>
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="all">All Albums</SelectItem>
                        <SelectItem v-for="a in albumOptions" :key="a.slug" :value="a.slug">
                            {{ a.title }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <!-- Loading -->
            <div v-if="loading" class="flex items-center justify-center py-12">
                <Spinner class="text-muted-foreground size-6" />
            </div>

            <!-- Empty -->
            <div
                v-else-if="filteredReviews.length === 0"
                class="text-muted-foreground flex flex-col items-center justify-center gap-2 py-12 text-center"
            >
                <Quote class="size-10 opacity-40" />
                <p v-if="searchQuery">No results for "{{ searchQuery }}"</p>
                <p v-else-if="albumFilter !== 'all'">No reviews for this album yet.</p>
                <p v-else>No reviews yet. Add your first one.</p>
            </div>

            <!-- Reviews list -->
            <div v-else class="divide-y rounded-md border">
                <div
                    v-for="item in filteredReviews"
                    :key="item.id"
                    class="flex items-start gap-4 p-4 transition-colors hover:bg-muted/40"
                >
                    <div class="text-muted-foreground/40 hidden shrink-0 pt-0.5 sm:block">
                        <GripVertical class="size-4" />
                        <span class="text-muted-foreground mt-0.5 block text-center text-[10px]">{{ item.sort_order }}</span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="line-clamp-2 text-sm italic leading-relaxed">
                                    "{{ item.excerpt }}"
                                </p>
                                <p class="text-muted-foreground mt-1.5 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs">
                                    <span
                                        v-if="item.album_slug"
                                        class="bg-primary/10 text-primary inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide"
                                    >
                                        <Disc3 class="size-2.5" />
                                        {{ albumTitleMap[item.album_slug] ?? item.album_slug }}
                                    </span>
                                    <span v-if="item.author" class="text-foreground font-medium">{{ item.author }}</span>
                                    <span v-if="item.author"> &mdash; </span>
                                    <span class="text-primary font-medium">{{ item.publication }}</span>
                                    <a
                                        v-if="item.source_url"
                                        :href="item.source_url"
                                        target="_blank"
                                        rel="noopener"
                                        class="text-primary ml-2 inline-flex items-center gap-0.5 hover:underline"
                                    >
                                        <ExternalLink class="size-3" />
                                        Source
                                    </a>
                                </p>
                            </div>
                            <div class="flex shrink-0 items-center gap-1">
                                <Button
                                    variant="ghost"
                                    size="icon-sm"
                                    @click="openEditForm(item)"
                                >
                                    <Pencil class="size-3.5" />
                                </Button>
                                <Button
                                    variant="ghost"
                                    size="icon-sm"
                                    class="text-destructive hover:text-destructive"
                                    @click="confirmDelete(item)"
                                >
                                    <Trash2 class="size-3.5" />
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>

    <!-- Create / Edit dialog -->
    <Dialog :open="showForm" @update:open="showForm = $event">
        <DialogContent class="sm:max-w-xl">
            <DialogHeader>
                <DialogTitle>
                    {{ editingItem ? 'Edit Review' : 'New Review' }}
                </DialogTitle>
            </DialogHeader>

            <form @submit.prevent="saveReview" class="space-y-4">
                <div class="space-y-2">
                    <Label for="review-album">Album</Label>
                    <Select v-model="form.album_slug">
                        <SelectTrigger id="review-album">
                            <SelectValue placeholder="Select an album..." />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="a in albumOptions" :key="a.slug" :value="a.slug">
                                {{ a.title }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="errors.album_slug" class="text-destructive text-xs">{{ errors.album_slug[0] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <Label for="review-publication">Publication</Label>
                        <Input id="review-publication" v-model="form.publication" placeholder="e.g. JazzTimes" />
                        <p v-if="errors.publication" class="text-destructive text-xs">{{ errors.publication[0] }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="review-author">Author (optional)</Label>
                        <Input id="review-author" v-model="form.author" placeholder="Reviewer name" />
                        <p v-if="errors.author" class="text-destructive text-xs">{{ errors.author[0] }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="review-excerpt">Excerpt</Label>
                    <Textarea
                        id="review-excerpt"
                        v-model="form.excerpt"
                        placeholder="Short pull-quote shown on the homepage..."
                        class="min-h-[80px]"
                    />
                    <p v-if="errors.excerpt" class="text-destructive text-xs">{{ errors.excerpt[0] }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="review-body">Full Review</Label>
                    <Textarea
                        id="review-body"
                        v-model="form.body"
                        placeholder="Complete review text..."
                        class="min-h-[140px]"
                    />
                    <p v-if="errors.body" class="text-destructive text-xs">{{ errors.body[0] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <Label for="review-source-url">Source URL (optional)</Label>
                        <Input id="review-source-url" v-model="form.source_url" placeholder="https://" />
                        <p v-if="errors.source_url" class="text-destructive text-xs">{{ errors.source_url[0] }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="review-sort-order">Sort Order</Label>
                        <Input id="review-sort-order" v-model.number="form.sort_order" type="number" min="0" />
                        <p v-if="errors.sort_order" class="text-destructive text-xs">{{ errors.sort_order[0] }}</p>
                    </div>
                </div>

                <DialogFooter>
                    <Button type="button" variant="outline" @click="showForm = false">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="saving">
                        <Spinner v-if="saving" class="size-4" />
                        {{ editingItem ? 'Save Changes' : 'Create Review' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>

    <!-- Delete confirmation -->
    <Dialog :open="showDeleteConfirm" @update:open="showDeleteConfirm = $event">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Delete Review</DialogTitle>
            </DialogHeader>
            <p class="text-muted-foreground text-sm">
                Are you sure you want to delete the review from
                <strong class="text-foreground">{{ deletingItem?.publication }}</strong><template v-if="deletingItem?.author"> by {{ deletingItem.author }}</template>?
                This action cannot be undone.
            </p>
            <DialogFooter>
                <Button variant="outline" @click="showDeleteConfirm = false">
                    Cancel
                </Button>
                <Button variant="destructive" :disabled="deleting" @click="deleteReview">
                    <Spinner v-if="deleting" class="size-4" />
                    Delete
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
