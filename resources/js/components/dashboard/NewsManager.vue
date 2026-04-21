<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { TiptapEditor } from '@/components/ui/tiptap-editor';
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
    ImageIcon,
    AlertCircle,
} from 'lucide-vue-next';
import StatusBadge from '@/components/dashboard/StatusBadge.vue';
import { CONTENT_STATUSES, STATUS_FILTER_OPTIONS, type ContentStatus } from '@/composables/useContentStatus';

interface NewsItem {
    id: number;
    status: ContentStatus;
    title: string;
    slug: string;
    body: string;
    date: string;
    image: string | null;
    image_url: string | null;
    link: string | null;
    created_at: string;
    updated_at: string;
}

const news = ref<NewsItem[]>([]);
const loading = ref(true);
const searchQuery = ref('');
const statusFilter = ref<'all' | ContentStatus>('all');
const showForm = ref(false);
const showDeleteConfirm = ref(false);
const saving = ref(false);
const deleting = ref(false);
const editingItem = ref<NewsItem | null>(null);
const deletingItem = ref<NewsItem | null>(null);
const errors = ref<Record<string, string[]>>({});
const flashMessage = ref('');
const flashType = ref<'success' | 'error'>('success');

const form = ref({
    status: 'draft' as ContentStatus,
    title: '',
    slug: '',
    body: '',
    date: '',
    link: '',
    image: null as File | null,
});

const imagePreview = ref<string | null>(null);

const filteredNews = computed(() => {
    let result = news.value;

    if (statusFilter.value !== 'all') {
        result = result.filter((item) => item.status === statusFilter.value);
    }

    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        result = result.filter(
            (item) =>
                item.title.toLowerCase().includes(q) ||
                item.body.toLowerCase().includes(q),
        );
    }

    return result;
});

function getCsrfToken(): string {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta?.getAttribute('content') ?? '';
}

async function fetchNews() {
    loading.value = true;
    try {
        const res = await fetch('/api/news?admin=1');
        news.value = await res.json();
    } catch {
        flash('Failed to load news entries.', 'error');
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
    form.value = { status: 'draft', title: '', slug: '', body: '', date: '', link: '', image: null };
    imagePreview.value = null;
    showForm.value = true;
}

function openEditForm(item: NewsItem) {
    editingItem.value = item;
    errors.value = {};
    form.value = {
        status: item.status ?? 'draft',
        title: item.title,
        slug: item.slug,
        body: item.body,
        date: item.date.substring(0, 10),
        link: item.link ?? '',
        image: null,
    };
    imagePreview.value = item.image_url;
    showForm.value = true;
}

function confirmDelete(item: NewsItem) {
    deletingItem.value = item;
    showDeleteConfirm.value = true;
}

function onImageChange(e: Event) {
    const input = e.target as HTMLInputElement;
    const file = input.files?.[0];
    if (file) {
        form.value.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
}

async function saveNews() {
    saving.value = true;
    errors.value = {};

    const formData = new FormData();
    formData.append('status', form.value.status);
    formData.append('title', form.value.title);
    formData.append('slug', form.value.slug);
    formData.append('body', form.value.body);
    formData.append('date', form.value.date);
    if (form.value.link) formData.append('link', form.value.link);
    if (form.value.image) formData.append('image', form.value.image);

    const url = editingItem.value
        ? `/api/news/${editingItem.value.id}`
        : '/api/news';

    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
            },
            body: formData,
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
        flash(editingItem.value ? 'News entry updated.' : 'News entry created.');
        await fetchNews();
    } catch {
        flash('Network error. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
}

async function deleteNews() {
    if (!deletingItem.value) return;
    deleting.value = true;

    try {
        const res = await fetch(`/api/news/${deletingItem.value.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
            },
        });

        if (!res.ok) {
            flash('Failed to delete news entry.', 'error');
            return;
        }

        showDeleteConfirm.value = false;
        flash('News entry deleted.');
        await fetchNews();
    } catch {
        flash('Network error. Please try again.', 'error');
    } finally {
        deleting.value = false;
    }
}

function formatDate(dateStr: string): string {
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric', timeZone: 'UTC' });
}

function stripHtml(html: string): string {
    const tmp = document.createElement('div');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
}

onMounted(fetchNews);
</script>

<template>
    <Card>
        <CardHeader class="flex-row items-center justify-between space-y-0">
            <CardTitle class="text-xl font-semibold">News Entries</CardTitle>
            <Button size="sm" @click="openCreateForm">
                <Plus class="size-4" />
                Add Entry
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

            <!-- Search & Status Filter -->
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <Search class="text-muted-foreground absolute top-1/2 left-3 size-4 -translate-y-1/2" />
                    <Input
                        v-model="searchQuery"
                        placeholder="Search news entries..."
                        class="pl-9"
                    />
                </div>
                <Select v-model="statusFilter">
                    <SelectTrigger class="w-full sm:w-[200px]">
                        <SelectValue placeholder="Status" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="opt in STATUS_FILTER_OPTIONS"
                            :key="opt.value"
                            :value="opt.value"
                        >
                            {{ opt.label }}
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
                v-else-if="filteredNews.length === 0"
                class="text-muted-foreground flex flex-col items-center justify-center gap-2 py-12 text-center"
            >
                <ImageIcon class="size-10 opacity-40" />
                <p v-if="searchQuery">No results for "{{ searchQuery }}"</p>
                <p v-else>No news entries yet. Create your first one.</p>
            </div>

            <!-- News list -->
            <div v-else class="divide-y rounded-md border">
                <div
                    v-for="item in filteredNews"
                    :key="item.id"
                    class="flex items-start gap-4 p-4 transition-colors hover:bg-muted/40"
                >
                    <div
                        v-if="item.image_url"
                        class="hidden size-16 shrink-0 overflow-hidden rounded-md sm:block"
                    >
                        <img
                            :src="item.image_url"
                            :alt="item.title"
                            class="size-full object-cover"
                        />
                    </div>
                    <div
                        v-else
                        class="bg-muted hidden size-16 shrink-0 items-center justify-center rounded-md sm:flex"
                    >
                        <ImageIcon class="text-muted-foreground size-6" />
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h4 class="truncate text-sm font-medium">
                                        {{ item.title }}
                                    </h4>
                                    <StatusBadge :status="item.status" compact />
                                </div>
                                <p class="text-muted-foreground mt-0.5 text-xs">
                                    {{ formatDate(item.date) }}
                                    <a
                                        v-if="item.link"
                                        :href="item.link"
                                        target="_blank"
                                        rel="noopener"
                                        class="text-primary ml-2 inline-flex items-center gap-0.5 hover:underline"
                                    >
                                        <ExternalLink class="size-3" />
                                        Link
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
                        <p class="text-muted-foreground mt-1 line-clamp-2 text-xs">
                            {{ stripHtml(item.body) }}
                        </p>
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>

    <!-- Create / Edit dialog -->
    <Dialog :open="showForm" @update:open="showForm = $event">
        <DialogContent class="sm:max-w-2xl">
            <DialogHeader>
                <DialogTitle>
                    {{ editingItem ? 'Edit News Entry' : 'New News Entry' }}
                </DialogTitle>
            </DialogHeader>

            <form @submit.prevent="saveNews" class="space-y-4">
                <div class="space-y-2">
                    <Label for="news-title">Title</Label>
                    <Input id="news-title" v-model="form.title" placeholder="Entry title" />
                    <p v-if="errors.title" class="text-destructive text-xs">{{ errors.title[0] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <Label for="news-slug">Slug</Label>
                        <Input id="news-slug" v-model="form.slug" placeholder="auto-generated" />
                        <p v-if="errors.slug" class="text-destructive text-xs">{{ errors.slug[0] }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="news-date">Date</Label>
                        <Input id="news-date" v-model="form.date" type="date" />
                        <p v-if="errors.date" class="text-destructive text-xs">{{ errors.date[0] }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="news-status">Status</Label>
                    <Select v-model="form.status">
                        <SelectTrigger id="news-status">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="s in CONTENT_STATUSES" :key="s.value" :value="s.value">
                                {{ s.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p class="text-muted-foreground text-xs">
                        {{ CONTENT_STATUSES.find((s) => s.value === form.status)?.description }}
                    </p>
                    <p v-if="errors.status" class="text-destructive text-xs">{{ errors.status[0] }}</p>
                </div>

                <div class="space-y-2">
                    <Label>Body</Label>
                    <TiptapEditor
                        v-model="form.body"
                        placeholder="Write the news entry content..."
                    />
                    <p v-if="errors.body" class="text-destructive text-xs">{{ errors.body[0] }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="news-link">Link (optional)</Label>
                    <Input id="news-link" v-model="form.link" placeholder="https://" />
                    <p v-if="errors.link" class="text-destructive text-xs">{{ errors.link[0] }}</p>
                </div>

                <div class="space-y-2">
                    <Label>Image (optional)</Label>
                    <div class="flex items-start gap-4">
                        <div
                            v-if="imagePreview"
                            class="relative size-20 shrink-0 overflow-hidden rounded-md border"
                        >
                            <img :src="imagePreview" class="size-full object-cover" alt="Preview" />
                            <button
                                type="button"
                                class="bg-background/80 absolute top-0.5 right-0.5 rounded-full p-0.5"
                                @click="form.image = null; imagePreview = null"
                            >
                                <Trash2 class="text-destructive size-3" />
                            </button>
                        </div>
                        <div class="flex-1">
                            <input
                                type="file"
                                accept="image/*"
                                class="text-muted-foreground file:bg-secondary file:text-secondary-foreground w-full text-sm file:mr-3 file:rounded-md file:border-0 file:px-3 file:py-1.5 file:text-sm file:font-medium"
                                @change="onImageChange"
                            />
                            <p v-if="errors.image" class="text-destructive mt-1 text-xs">{{ errors.image[0] }}</p>
                        </div>
                    </div>
                </div>

                <DialogFooter>
                    <Button type="button" variant="outline" @click="showForm = false">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="saving">
                        <Spinner v-if="saving" class="size-4" />
                        {{ editingItem ? 'Save Changes' : 'Create Entry' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>

    <!-- Delete confirmation -->
    <Dialog :open="showDeleteConfirm" @update:open="showDeleteConfirm = $event">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Delete News Entry</DialogTitle>
            </DialogHeader>
            <p class="text-muted-foreground text-sm">
                Are you sure you want to delete
                <strong class="text-foreground">{{ deletingItem?.title }}</strong>?
                This action cannot be undone.
            </p>
            <DialogFooter>
                <Button variant="outline" @click="showDeleteConfirm = false">
                    Cancel
                </Button>
                <Button variant="destructive" :disabled="deleting" @click="deleteNews">
                    <Spinner v-if="deleting" class="size-4" />
                    Delete
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
