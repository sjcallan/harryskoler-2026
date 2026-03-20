<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
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
    Radio,
    AlertCircle,
    GripVertical,
    Disc3,
    ImageIcon,
    Link2,
    X,
} from 'lucide-vue-next';
import draggable from 'vuedraggable';

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

interface RadioAirplayItem {
    id: number;
    rank: number;
    chart: string;
    detail: string | null;
    link: string | null;
    image: string | null;
    thumbnail: string | null;
    image_url: string | null;
    thumbnail_url: string | null;
    album_slug: string;
    sort_order: number;
    created_at: string;
    updated_at: string;
}

const entries = ref<RadioAirplayItem[]>([]);
const loading = ref(true);
const searchQuery = ref('');
const albumFilter = ref('all');
const showForm = ref(false);
const showDeleteConfirm = ref(false);
const saving = ref(false);
const deleting = ref(false);
const editingItem = ref<RadioAirplayItem | null>(null);
const deletingItem = ref<RadioAirplayItem | null>(null);
const errors = ref<Record<string, string[]>>({});
const flashMessage = ref('');
const flashType = ref<'success' | 'error'>('success');

const form = ref({
    rank: 1,
    chart: '',
    detail: '',
    link: '',
    album_slug: '',
    sort_order: 0,
    image: null as File | null,
    removeImage: false,
});
const imagePreview = ref<string | null>(null);

const filteredEntries = computed(() => {
    let result = entries.value;

    if (albumFilter.value && albumFilter.value !== 'all') {
        result = result.filter((item) => item.album_slug === albumFilter.value);
    }

    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        result = result.filter(
            (item) =>
                item.chart.toLowerCase().includes(q) ||
                (item.detail?.toLowerCase().includes(q) ?? false),
        );
    }

    return result;
});

function getCsrfToken(): string {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta?.getAttribute('content') ?? '';
}

async function fetchEntries() {
    loading.value = true;
    try {
        const res = await fetch('/api/radio-airplays');
        entries.value = await res.json();
    } catch {
        flash('Failed to load radio airplay entries.', 'error');
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
    form.value = { rank: 1, chart: '', detail: '', link: '', album_slug: '', sort_order: 0, image: null, removeImage: false };
    imagePreview.value = null;
    showForm.value = true;
}

function openEditForm(item: RadioAirplayItem) {
    editingItem.value = item;
    errors.value = {};
    form.value = {
        rank: item.rank,
        chart: item.chart,
        detail: item.detail ?? '',
        link: item.link ?? '',
        album_slug: item.album_slug ?? '',
        sort_order: item.sort_order,
        image: null,
        removeImage: false,
    };
    imagePreview.value = item.thumbnail_url ?? null;
    showForm.value = true;
}

function confirmDelete(item: RadioAirplayItem) {
    deletingItem.value = item;
    showDeleteConfirm.value = true;
}

function onImageChange(e: Event) {
    const input = e.target as HTMLInputElement;
    const file = input.files?.[0];
    if (file) {
        form.value.image = file;
        form.value.removeImage = false;
        imagePreview.value = URL.createObjectURL(file);
    }
}

function removeImage() {
    form.value.image = null;
    form.value.removeImage = true;
    imagePreview.value = null;
}

async function saveEntry() {
    saving.value = true;
    errors.value = {};

    const formData = new FormData();
    formData.append('rank', String(form.value.rank));
    formData.append('chart', form.value.chart);
    formData.append('sort_order', String(form.value.sort_order));
    if (form.value.detail) formData.append('detail', form.value.detail);
    if (form.value.link) formData.append('link', form.value.link);
    if (form.value.album_slug) formData.append('album_slug', form.value.album_slug);
    if (form.value.image) formData.append('image', form.value.image);
    if (form.value.removeImage) formData.append('remove_image', '1');

    const url = editingItem.value
        ? `/api/radio-airplays/${editingItem.value.id}`
        : '/api/radio-airplays';

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
        flash(editingItem.value ? 'Entry updated.' : 'Entry created.');
        await fetchEntries();
    } catch {
        flash('Network error. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
}

async function deleteEntry() {
    if (!deletingItem.value) return;
    deleting.value = true;

    try {
        const res = await fetch(`/api/radio-airplays/${deletingItem.value.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
            },
        });

        if (!res.ok) {
            flash('Failed to delete entry.', 'error');
            return;
        }

        showDeleteConfirm.value = false;
        flash('Entry deleted.');
        await fetchEntries();
    } catch {
        flash('Network error. Please try again.', 'error');
    } finally {
        deleting.value = false;
    }
}

async function onDragEnd() {
    const ids = filteredEntries.value.map((item) => item.id);
    try {
        await fetch('/api/radio-airplays/reorder', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ids }),
        });
        await fetchEntries();
    } catch {
        flash('Failed to save order.', 'error');
    }
}

onMounted(fetchEntries);
</script>

<template>
    <Card>
        <CardHeader class="flex-row items-center justify-between space-y-0">
            <CardTitle class="text-xl font-semibold">Radio Airplay</CardTitle>
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

            <!-- Search & Album Filter -->
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <Search class="text-muted-foreground absolute top-1/2 left-3 size-4 -translate-y-1/2" />
                    <Input
                        v-model="searchQuery"
                        placeholder="Search radio airplay entries..."
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
                v-else-if="filteredEntries.length === 0"
                class="text-muted-foreground flex flex-col items-center justify-center gap-2 py-12 text-center"
            >
                <Radio class="size-10 opacity-40" />
                <p v-if="searchQuery">No results for "{{ searchQuery }}"</p>
                <p v-else-if="albumFilter !== 'all'">No entries for this album yet.</p>
                <p v-else>No radio airplay entries yet. Add your first one.</p>
            </div>

            <!-- Entries list -->
            <draggable
                v-else
                v-model="filteredEntries"
                item-key="id"
                handle=".drag-handle"
                ghost-class="opacity-30"
                @end="onDragEnd"
                tag="div"
                class="divide-y rounded-md border"
            >
                <template #item="{ element: item }">
                    <div class="flex items-center gap-4 p-4 transition-colors hover:bg-muted/40">
                        <div class="drag-handle text-muted-foreground/40 hidden shrink-0 cursor-grab sm:block active:cursor-grabbing">
                            <GripVertical class="size-4" />
                            <span class="text-muted-foreground mt-0.5 block text-center text-[10px]">{{ item.sort_order }}</span>
                        </div>

                        <div v-if="item.thumbnail_url" class="shrink-0">
                            <img :src="item.thumbnail_url" :alt="item.chart" class="size-10 rounded-md object-cover" />
                        </div>

                        <div class="bg-primary/10 text-primary flex size-10 shrink-0 items-center justify-center rounded-md font-bold">
                            #{{ item.rank }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <h4 class="truncate text-sm font-medium">{{ item.chart }}</h4>
                            <p v-if="item.detail" class="text-muted-foreground mt-0.5 truncate text-xs">
                                {{ item.detail }}
                            </p>
                            <div class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-1">
                                <span
                                    v-if="item.album_slug"
                                    class="bg-primary/10 text-primary inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide"
                                >
                                    <Disc3 class="size-2.5" />
                                    {{ albumTitleMap[item.album_slug] ?? item.album_slug }}
                                </span>
                                <a
                                    v-if="item.link"
                                    :href="item.link"
                                    target="_blank"
                                    rel="noopener"
                                    class="text-primary inline-flex items-center gap-0.5 text-[10px] hover:underline"
                                >
                                    <Link2 class="size-2.5" />
                                    Link
                                </a>
                            </div>
                        </div>

                        <div class="flex shrink-0 items-center gap-1">
                            <Button variant="ghost" size="icon-sm" @click="openEditForm(item)">
                                <Pencil class="size-3.5" />
                            </Button>
                            <Button variant="ghost" size="icon-sm" class="text-destructive hover:text-destructive" @click="confirmDelete(item)">
                                <Trash2 class="size-3.5" />
                            </Button>
                        </div>
                    </div>
                </template>
            </draggable>
        </CardContent>
    </Card>

    <!-- Create / Edit dialog -->
    <Dialog :open="showForm" @update:open="showForm = $event">
        <DialogContent class="sm:max-w-lg">
            <DialogHeader>
                <DialogTitle>
                    {{ editingItem ? 'Edit Radio Airplay Entry' : 'New Radio Airplay Entry' }}
                </DialogTitle>
            </DialogHeader>

            <form @submit.prevent="saveEntry" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <Label for="radio-rank">Chart Rank</Label>
                        <Input id="radio-rank" v-model.number="form.rank" type="number" min="1" placeholder="#" />
                        <p v-if="errors.rank" class="text-destructive text-xs">{{ errors.rank[0] }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="radio-sort-order">Sort Order</Label>
                        <Input id="radio-sort-order" v-model.number="form.sort_order" type="number" min="0" />
                        <p v-if="errors.sort_order" class="text-destructive text-xs">{{ errors.sort_order[0] }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="radio-chart">Chart Name</Label>
                    <Input id="radio-chart" v-model="form.chart" placeholder="e.g. NACC Top 30 Jazz" />
                    <p v-if="errors.chart" class="text-destructive text-xs">{{ errors.chart[0] }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="radio-detail">Detail (optional)</Label>
                    <Input id="radio-detail" v-model="form.detail" placeholder="e.g. North American College Chart · Aug 2024" />
                    <p v-if="errors.detail" class="text-destructive text-xs">{{ errors.detail[0] }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="radio-link">Link (optional)</Label>
                    <Input id="radio-link" v-model="form.link" placeholder="https://" />
                    <p v-if="errors.link" class="text-destructive text-xs">{{ errors.link[0] }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="radio-album">Album</Label>
                    <Select v-model="form.album_slug">
                        <SelectTrigger id="radio-album">
                            <SelectValue placeholder="Select an album (optional)" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="">None</SelectItem>
                            <SelectItem v-for="a in albumOptions" :key="a.slug" :value="a.slug">
                                {{ a.title }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="errors.album_slug" class="text-destructive text-xs">{{ errors.album_slug[0] }}</p>
                </div>

                <div class="space-y-2">
                    <Label>Image (optional)</Label>
                    <div v-if="imagePreview" class="flex items-center gap-3">
                        <img :src="imagePreview" class="size-16 rounded-md object-cover" />
                        <Button type="button" variant="ghost" size="icon-sm" @click="removeImage">
                            <X class="size-4" />
                        </Button>
                    </div>
                    <input
                        type="file"
                        accept="image/*"
                        class="text-muted-foreground block w-full text-sm file:mr-4 file:rounded-md file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary-foreground hover:file:bg-primary/90"
                        @change="onImageChange"
                    />
                    <p v-if="errors.image" class="text-destructive text-xs">{{ errors.image[0] }}</p>
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
                <DialogTitle>Delete Radio Airplay Entry</DialogTitle>
            </DialogHeader>
            <p class="text-muted-foreground text-sm">
                Are you sure you want to delete
                <strong class="text-foreground">#{{ deletingItem?.rank }} {{ deletingItem?.chart }}</strong>?
                This action cannot be undone.
            </p>
            <DialogFooter>
                <Button variant="outline" @click="showDeleteConfirm = false">
                    Cancel
                </Button>
                <Button variant="destructive" :disabled="deleting" @click="deleteEntry">
                    <Spinner v-if="deleting" class="size-4" />
                    Delete
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
