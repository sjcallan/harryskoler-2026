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
    Search,
    Plus,
    Pencil,
    Trash2,
    FileText,
    ImageIcon,
    AlertCircle,
    GripVertical,
    X,
} from 'lucide-vue-next';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import StatusBadge from '@/components/dashboard/StatusBadge.vue';
import { CONTENT_STATUSES, STATUS_FILTER_OPTIONS, type ContentStatus } from '@/composables/useContentStatus';

interface PressImageItem {
    id: number;
    status: ContentStatus;
    image: string;
    image_url: string;
    caption: string | null;
    sort_order: number;
}

interface PressEventItem {
    id: number;
    status: ContentStatus;
    title: string;
    slug: string;
    publication: string | null;
    description: string | null;
    date: string | null;
    pdf: string | null;
    pdf_url: string | null;
    sort_order: number;
    images: PressImageItem[];
    created_at: string;
    updated_at: string;
}

const events = ref<PressEventItem[]>([]);
const loading = ref(true);
const searchQuery = ref('');
const statusFilter = ref<'all' | ContentStatus>('all');
const showForm = ref(false);
const showDeleteConfirm = ref(false);
const saving = ref(false);
const deleting = ref(false);
const editingItem = ref<PressEventItem | null>(null);
const deletingItem = ref<PressEventItem | null>(null);
const errors = ref<Record<string, string[]>>({});
const flashMessage = ref('');
const flashType = ref<'success' | 'error'>('success');

const form = ref({
    status: 'draft' as ContentStatus,
    title: '',
    slug: '',
    publication: '',
    description: '',
    date: '',
    sort_order: 0,
    pdf: null as File | null,
    removePdf: false,
});

const existingImages = ref<PressImageItem[]>([]);
const newImages = ref<{ file: File; caption: string; preview: string }[]>([]);
const pdfPreviewName = ref<string | null>(null);

const filteredEvents = computed(() => {
    let result = events.value;

    if (statusFilter.value !== 'all') {
        result = result.filter((item) => item.status === statusFilter.value);
    }

    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        result = result.filter(
            (item) =>
                item.title.toLowerCase().includes(q) ||
                (item.publication ?? '').toLowerCase().includes(q) ||
                (item.description ?? '').toLowerCase().includes(q),
        );
    }

    return result;
});

function getCsrfToken(): string {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta?.getAttribute('content') ?? '';
}

async function fetchEvents() {
    loading.value = true;
    try {
        const res = await fetch('/api/press-events?admin=1');
        events.value = await res.json();
    } catch {
        flash('Failed to load press events.', 'error');
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
    form.value = { status: 'draft', title: '', slug: '', publication: '', description: '', date: '', sort_order: 0, pdf: null, removePdf: false };
    existingImages.value = [];
    newImages.value = [];
    pdfPreviewName.value = null;
    showForm.value = true;
}

function openEditForm(item: PressEventItem) {
    editingItem.value = item;
    errors.value = {};
    form.value = {
        status: item.status ?? 'draft',
        title: item.title,
        slug: item.slug,
        publication: item.publication ?? '',
        description: item.description ?? '',
        date: item.date ? item.date.substring(0, 10) : '',
        sort_order: item.sort_order,
        pdf: null,
        removePdf: false,
    };
    existingImages.value = [...item.images];
    newImages.value = [];
    pdfPreviewName.value = item.pdf ? item.pdf.split('/').pop() ?? null : null;
    showForm.value = true;
}

function confirmDelete(item: PressEventItem) {
    deletingItem.value = item;
    showDeleteConfirm.value = true;
}

function onPdfChange(e: Event) {
    const input = e.target as HTMLInputElement;
    const file = input.files?.[0];
    if (file) {
        form.value.pdf = file;
        form.value.removePdf = false;
        pdfPreviewName.value = file.name;
    }
}

function removePdf() {
    form.value.pdf = null;
    form.value.removePdf = true;
    pdfPreviewName.value = null;
}

function onImagesChange(e: Event) {
    const input = e.target as HTMLInputElement;
    const files = input.files;
    if (!files) return;
    for (const file of Array.from(files)) {
        newImages.value.push({
            file,
            caption: '',
            preview: URL.createObjectURL(file),
        });
    }
    input.value = '';
}

function removeExistingImage(index: number) {
    existingImages.value.splice(index, 1);
}

function removeNewImage(index: number) {
    URL.revokeObjectURL(newImages.value[index].preview);
    newImages.value.splice(index, 1);
}

async function saveEvent() {
    saving.value = true;
    errors.value = {};

    const formData = new FormData();
    formData.append('status', form.value.status);
    formData.append('title', form.value.title);
    formData.append('slug', form.value.slug);
    if (form.value.publication) formData.append('publication', form.value.publication);
    if (form.value.description) formData.append('description', form.value.description);
    if (form.value.date) formData.append('date', form.value.date);
    formData.append('sort_order', String(form.value.sort_order));

    if (form.value.pdf) {
        formData.append('pdf', form.value.pdf);
    } else if (form.value.removePdf) {
        formData.append('remove_pdf', '1');
    }

    if (editingItem.value) {
        formData.append('existing_images', JSON.stringify(
            existingImages.value.map((img, i) => ({
                id: img.id,
                caption: img.caption,
                sort_order: i,
            })),
        ));
    }

    newImages.value.forEach((img, i) => {
        formData.append('images[]', img.file);
        formData.append(`captions[${i}]`, img.caption);
    });

    const url = editingItem.value
        ? `/api/press-events/${editingItem.value.id}`
        : '/api/press-events';

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
        flash(editingItem.value ? 'Press event updated.' : 'Press event created.');
        await fetchEvents();
    } catch {
        flash('Network error. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
}

async function deleteEvent() {
    if (!deletingItem.value) return;
    deleting.value = true;

    try {
        const res = await fetch(`/api/press-events/${deletingItem.value.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
            },
        });

        if (!res.ok) {
            flash('Failed to delete press event.', 'error');
            return;
        }

        showDeleteConfirm.value = false;
        flash('Press event deleted.');
        await fetchEvents();
    } catch {
        flash('Network error. Please try again.', 'error');
    } finally {
        deleting.value = false;
    }
}

function formatDate(dateStr: string | null): string {
    if (!dateStr) return '—';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', timeZone: 'UTC' });
}

onMounted(fetchEvents);
</script>

<template>
    <Card>
        <CardHeader class="flex-row items-center justify-between space-y-0">
            <CardTitle class="text-xl font-semibold">Press Events</CardTitle>
            <Button size="sm" @click="openCreateForm">
                <Plus class="size-4" />
                Add Event
            </Button>
        </CardHeader>
        <CardContent class="space-y-4">
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

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <Search class="text-muted-foreground absolute top-1/2 left-3 size-4 -translate-y-1/2" />
                    <Input
                        v-model="searchQuery"
                        placeholder="Search press events..."
                        class="pl-9"
                    />
                </div>
                <Select v-model="statusFilter">
                    <SelectTrigger class="w-full sm:w-[180px]">
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

            <div v-if="loading" class="flex items-center justify-center py-12">
                <Spinner class="text-muted-foreground size-6" />
            </div>

            <div
                v-else-if="filteredEvents.length === 0"
                class="text-muted-foreground flex flex-col items-center justify-center gap-2 py-12 text-center"
            >
                <FileText class="size-10 opacity-40" />
                <p v-if="searchQuery">No results for "{{ searchQuery }}"</p>
                <p v-else>No press events yet. Create your first one.</p>
            </div>

            <div v-else class="divide-y rounded-md border">
                <div
                    v-for="item in filteredEvents"
                    :key="item.id"
                    class="flex items-start gap-4 p-4 transition-colors hover:bg-muted/40"
                >
                    <div
                        v-if="item.images.length > 0"
                        class="hidden size-16 shrink-0 overflow-hidden rounded-md sm:block"
                    >
                        <img
                            :src="item.images[0].image_url"
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
                                    <span v-if="item.publication">{{ item.publication }} · </span>
                                    {{ formatDate(item.date) }}
                                    <span class="ml-2 text-xs opacity-60">{{ item.images.length }} image{{ item.images.length !== 1 ? 's' : '' }}</span>
                                    <span v-if="item.pdf_url" class="ml-2">
                                        <a :href="item.pdf_url" target="_blank" rel="noopener" class="text-primary inline-flex items-center gap-0.5 hover:underline">
                                            <FileText class="size-3" />
                                            PDF
                                        </a>
                                    </span>
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
                        <p v-if="item.description" class="text-muted-foreground mt-1 line-clamp-2 text-xs">
                            {{ item.description }}
                        </p>
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>

    <!-- Create / Edit dialog -->
    <Dialog :open="showForm" @update:open="showForm = $event">
        <DialogContent class="max-h-[90vh] overflow-y-auto sm:max-w-2xl">
            <DialogHeader>
                <DialogTitle>
                    {{ editingItem ? 'Edit Press Event' : 'New Press Event' }}
                </DialogTitle>
            </DialogHeader>

            <form @submit.prevent="saveEvent" class="space-y-4">
                <div class="space-y-2">
                    <Label for="press-title">Title</Label>
                    <Input id="press-title" v-model="form.title" placeholder="e.g. Jazziz: Mingus at 100" />
                    <p v-if="errors.title" class="text-destructive text-xs">{{ errors.title[0] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <Label for="press-slug">Slug</Label>
                        <Input id="press-slug" v-model="form.slug" placeholder="auto-generated" />
                        <p v-if="errors.slug" class="text-destructive text-xs">{{ errors.slug[0] }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="press-publication">Publication</Label>
                        <Input id="press-publication" v-model="form.publication" placeholder="e.g. Jazziz Magazine" />
                        <p v-if="errors.publication" class="text-destructive text-xs">{{ errors.publication[0] }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <Label for="press-date">Date</Label>
                        <Input id="press-date" v-model="form.date" type="date" />
                        <p v-if="errors.date" class="text-destructive text-xs">{{ errors.date[0] }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="press-sort">Sort Order</Label>
                        <Input id="press-sort" v-model.number="form.sort_order" type="number" min="0" />
                        <p v-if="errors.sort_order" class="text-destructive text-xs">{{ errors.sort_order[0] }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="press-status">Status</Label>
                        <Select v-model="form.status">
                            <SelectTrigger id="press-status">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="s in CONTENT_STATUSES" :key="s.value" :value="s.value">
                                    {{ s.label }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <p v-if="errors.status" class="text-destructive text-xs">{{ errors.status[0] }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="press-description">Description (optional)</Label>
                    <Textarea
                        id="press-description"
                        v-model="form.description"
                        placeholder="Brief description of this press feature..."
                        class="min-h-[80px]"
                    />
                    <p v-if="errors.description" class="text-destructive text-xs">{{ errors.description[0] }}</p>
                </div>

                <!-- PDF Upload -->
                <div class="space-y-2">
                    <Label>PDF (optional)</Label>
                    <div class="flex items-center gap-3">
                        <div v-if="pdfPreviewName" class="flex items-center gap-2 rounded-md border px-3 py-1.5 text-sm">
                            <FileText class="size-4 shrink-0 opacity-60" />
                            <span class="truncate max-w-[200px]">{{ pdfPreviewName }}</span>
                            <button type="button" @click="removePdf" class="ml-1 opacity-60 hover:opacity-100">
                                <X class="size-3.5" />
                            </button>
                        </div>
                        <div class="flex-1">
                            <input
                                type="file"
                                accept=".pdf"
                                class="text-muted-foreground file:bg-secondary file:text-secondary-foreground w-full text-sm file:mr-3 file:rounded-md file:border-0 file:px-3 file:py-1.5 file:text-sm file:font-medium"
                                @change="onPdfChange"
                            />
                        </div>
                    </div>
                    <p v-if="errors.pdf" class="text-destructive text-xs">{{ errors.pdf[0] }}</p>
                </div>

                <!-- Images -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <Label>Images</Label>
                        <label class="bg-secondary text-secondary-foreground inline-flex cursor-pointer items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors hover:opacity-80">
                            <Plus class="size-3.5" />
                            Add Images
                            <input
                                type="file"
                                accept="image/*"
                                multiple
                                class="hidden"
                                @change="onImagesChange"
                            />
                        </label>
                    </div>

                    <!-- Existing images -->
                    <div v-if="existingImages.length > 0" class="space-y-2">
                        <p class="text-muted-foreground text-xs font-medium uppercase tracking-wider">Current Images</p>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                            <div
                                v-for="(img, i) in existingImages"
                                :key="img.id"
                                class="group relative overflow-hidden rounded-md border"
                            >
                                <img :src="img.image_url" :alt="img.caption ?? ''" class="aspect-4/3 w-full object-cover" />
                                <button
                                    type="button"
                                    class="absolute top-1.5 right-1.5 rounded-full bg-black/60 p-1 opacity-0 transition-opacity group-hover:opacity-100"
                                    @click="removeExistingImage(i)"
                                >
                                    <X class="size-3.5 text-white" />
                                </button>
                                <div class="p-2">
                                    <Input
                                        v-model="existingImages[i].caption"
                                        placeholder="Caption"
                                        class="h-7 text-xs"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- New images -->
                    <div v-if="newImages.length > 0" class="space-y-2">
                        <p class="text-muted-foreground text-xs font-medium uppercase tracking-wider">New Images</p>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                            <div
                                v-for="(img, i) in newImages"
                                :key="i"
                                class="group relative overflow-hidden rounded-md border"
                            >
                                <img :src="img.preview" alt="" class="aspect-4/3 w-full object-cover" />
                                <button
                                    type="button"
                                    class="absolute top-1.5 right-1.5 rounded-full bg-black/60 p-1 opacity-0 transition-opacity group-hover:opacity-100"
                                    @click="removeNewImage(i)"
                                >
                                    <X class="size-3.5 text-white" />
                                </button>
                                <div class="p-2">
                                    <Input
                                        v-model="newImages[i].caption"
                                        placeholder="Caption"
                                        class="h-7 text-xs"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <p v-if="errors.images" class="text-destructive text-xs">{{ errors.images[0] }}</p>
                </div>

                <DialogFooter>
                    <Button type="button" variant="outline" @click="showForm = false">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="saving">
                        <Spinner v-if="saving" class="size-4" />
                        {{ editingItem ? 'Save Changes' : 'Create Event' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>

    <!-- Delete confirmation -->
    <Dialog :open="showDeleteConfirm" @update:open="showDeleteConfirm = $event">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Delete Press Event</DialogTitle>
            </DialogHeader>
            <p class="text-muted-foreground text-sm">
                Are you sure you want to delete
                <strong class="text-foreground">{{ deletingItem?.title }}</strong>?
                This will also delete all associated images. This action cannot be undone.
            </p>
            <DialogFooter>
                <Button variant="outline" @click="showDeleteConfirm = false">
                    Cancel
                </Button>
                <Button variant="destructive" :disabled="deleting" @click="deleteEvent">
                    <Spinner v-if="deleting" class="size-4" />
                    Delete
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
