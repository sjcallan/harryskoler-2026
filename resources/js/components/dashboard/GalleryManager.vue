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
    ImageIcon,
    Upload,
    Pencil,
    Trash2,
    AlertCircle,
    GripVertical,
} from 'lucide-vue-next';
import draggable from 'vuedraggable';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import StatusBadge from '@/components/dashboard/StatusBadge.vue';
import { CONTENT_STATUSES, STATUS_FILTER_OPTIONS, type ContentStatus } from '@/composables/useContentStatus';

interface GalleryImageItem {
    id: number;
    status: ContentStatus;
    image: string;
    thumbnail: string;
    image_url: string;
    thumbnail_url: string;
    caption: string | null;
    alt_text: string | null;
    credit: string | null;
    sort_order: number;
    created_at: string;
    updated_at: string;
}

const images = ref<GalleryImageItem[]>([]);
const loading = ref(true);
const uploading = ref(false);
const statusFilter = ref<'all' | ContentStatus>('all');
const uploadStatus = ref<ContentStatus>('draft');
const showEditDialog = ref(false);
const showDeleteConfirm = ref(false);
const saving = ref(false);
const deleting = ref(false);
const editingItem = ref<GalleryImageItem | null>(null);
const deletingItem = ref<GalleryImageItem | null>(null);
const errors = ref<Record<string, string[]>>({});
const flashMessage = ref('');
const flashType = ref<'success' | 'error'>('success');
const dragOver = ref(false);

const editForm = ref({
    status: 'draft' as ContentStatus,
    caption: '',
    alt_text: '',
    credit: '',
});

const filteredImages = computed(() => {
    if (statusFilter.value === 'all') return images.value;
    return images.value.filter((item) => item.status === statusFilter.value);
});

function getCsrfToken(): string {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta?.getAttribute('content') ?? '';
}

async function fetchImages() {
    loading.value = true;
    try {
        const res = await fetch('/api/gallery-images?admin=1');
        images.value = await res.json();
    } catch {
        flash('Failed to load gallery images.', 'error');
    } finally {
        loading.value = false;
    }
}

function flash(message: string, type: 'success' | 'error' = 'success') {
    flashMessage.value = message;
    flashType.value = type;
    setTimeout(() => (flashMessage.value = ''), 4000);
}

function onFileInput(e: Event) {
    const input = e.target as HTMLInputElement;
    if (input.files?.length) {
        uploadFiles(Array.from(input.files));
        input.value = '';
    }
}

function onDrop(e: DragEvent) {
    dragOver.value = false;
    const files = e.dataTransfer?.files;
    if (files?.length) {
        uploadFiles(Array.from(files).filter(f => f.type.startsWith('image/')));
    }
}

async function uploadFiles(files: File[]) {
    if (files.length === 0) return;
    uploading.value = true;
    errors.value = {};

    const formData = new FormData();
    formData.append('status', uploadStatus.value);
    files.forEach(f => formData.append('images[]', f));

    try {
        const res = await fetch('/api/gallery-images', {
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
                flash('Validation error. Check file types and sizes.', 'error');
            } else {
                flash('Upload failed. Please try again.', 'error');
            }
            return;
        }

        flash(`${files.length} image${files.length > 1 ? 's' : ''} uploaded.`);
        await fetchImages();
    } catch {
        flash('Network error. Please try again.', 'error');
    } finally {
        uploading.value = false;
    }
}

function openEditDialog(item: GalleryImageItem) {
    editingItem.value = item;
    editForm.value = {
        status: item.status ?? 'draft',
        caption: item.caption ?? '',
        alt_text: item.alt_text ?? '',
        credit: item.credit ?? '',
    };
    errors.value = {};
    showEditDialog.value = true;
}

function confirmDelete(item: GalleryImageItem) {
    deletingItem.value = item;
    showDeleteConfirm.value = true;
}

async function saveEdit() {
    if (!editingItem.value) return;
    saving.value = true;
    errors.value = {};

    try {
        const res = await fetch(`/api/gallery-images/${editingItem.value.id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(editForm.value),
        });

        if (!res.ok) {
            if (res.status === 422) {
                const data = await res.json();
                errors.value = data.errors ?? {};
            } else {
                flash('Failed to update. Please try again.', 'error');
            }
            return;
        }

        showEditDialog.value = false;
        flash('Image details updated.');
        await fetchImages();
    } catch {
        flash('Network error. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
}

async function deleteImage() {
    if (!deletingItem.value) return;
    deleting.value = true;

    try {
        const res = await fetch(`/api/gallery-images/${deletingItem.value.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
            },
        });

        if (!res.ok) {
            flash('Failed to delete image.', 'error');
            return;
        }

        showDeleteConfirm.value = false;
        flash('Image deleted.');
        await fetchImages();
    } catch {
        flash('Network error. Please try again.', 'error');
    } finally {
        deleting.value = false;
    }
}

async function onDragEnd() {
    const ids = images.value.map((item) => item.id);
    try {
        await fetch('/api/gallery-images/reorder', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ids }),
        });
        await fetchImages();
    } catch {
        flash('Failed to save order.', 'error');
    }
}

onMounted(fetchImages);
</script>

<template>
    <Card>
        <CardHeader class="flex-row items-center justify-between space-y-0">
            <CardTitle class="text-xl font-semibold">Gallery</CardTitle>
            <div class="flex items-center gap-2">
                <Select v-model="uploadStatus">
                    <SelectTrigger class="h-9 w-[150px]">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="s in CONTENT_STATUSES" :key="s.value" :value="s.value">
                            Upload as {{ s.label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <label class="cursor-pointer">
                    <Button as="span" size="sm">
                        <Upload class="size-4" />
                        Upload Images
                    </Button>
                    <input
                        type="file"
                        accept="image/*"
                        multiple
                        class="hidden"
                        @change="onFileInput"
                    />
                </label>
            </div>
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

            <!-- Status Filter -->
            <div class="flex items-center justify-end">
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

            <!-- Drop zone -->
            <div
                :class="[
                    'flex flex-col items-center justify-center rounded-lg border-2 border-dashed p-8 text-center transition-colors',
                    dragOver ? 'border-primary bg-primary/5' : 'border-muted-foreground/25',
                ]"
                @dragover.prevent="dragOver = true"
                @dragleave="dragOver = false"
                @drop.prevent="onDrop"
            >
                <Upload :class="['size-8 mb-3', dragOver ? 'text-primary' : 'text-muted-foreground/40']" />
                <p class="text-muted-foreground text-sm">
                    <span v-if="uploading"><Spinner class="mr-2 inline size-4" />Uploading...</span>
                    <span v-else>Drag & drop images here, or click <strong>Upload Images</strong> above</span>
                </p>
                <p class="text-muted-foreground/60 mt-1 text-xs">Supports JPG, PNG, GIF, WebP up to 20MB each</p>
            </div>

            <!-- Loading -->
            <div v-if="loading" class="flex items-center justify-center py-12">
                <Spinner class="text-muted-foreground size-6" />
            </div>

            <!-- Empty -->
            <div
                v-else-if="filteredImages.length === 0"
                class="text-muted-foreground flex flex-col items-center justify-center gap-2 py-12 text-center"
            >
                <ImageIcon class="size-10 opacity-40" />
                <p v-if="statusFilter !== 'all'">No {{ statusFilter }} images.</p>
                <p v-else>No gallery images yet. Upload your first one.</p>
            </div>

            <!-- Image grid (draggable when filter = all) -->
            <draggable
                v-else-if="statusFilter === 'all'"
                v-model="images"
                item-key="id"
                ghost-class="opacity-30"
                @end="onDragEnd"
                tag="div"
                class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5"
            >
                <template #item="{ element: item }">
                    <div class="group relative overflow-hidden rounded-lg border">
                        <div class="aspect-square cursor-grab active:cursor-grabbing">
                            <img
                                :src="item.thumbnail_url"
                                :alt="item.alt_text || item.caption || 'Gallery image'"
                                class="h-full w-full object-cover"
                            />
                        </div>
                        <div class="absolute top-1.5 right-1.5">
                            <StatusBadge :status="item.status" compact />
                        </div>
                        <div class="absolute inset-0 flex items-end bg-linear-to-t from-black/60 via-transparent to-transparent opacity-0 transition-opacity group-hover:opacity-100">
                            <div class="flex w-full items-center justify-between p-2">
                                <span class="truncate text-xs text-white">{{ item.caption || 'No caption' }}</span>
                                <div class="flex shrink-0 gap-1">
                                    <Button variant="ghost" size="icon-sm" class="text-white hover:text-white hover:bg-white/20" @click="openEditDialog(item)">
                                        <Pencil class="size-3.5" />
                                    </Button>
                                    <Button variant="ghost" size="icon-sm" class="text-white hover:text-red-400 hover:bg-white/20" @click="confirmDelete(item)">
                                        <Trash2 class="size-3.5" />
                                    </Button>
                                </div>
                            </div>
                        </div>
                        <div class="absolute top-1.5 left-1.5 opacity-0 transition-opacity group-hover:opacity-100">
                            <GripVertical class="size-4 text-white drop-shadow" />
                        </div>
                    </div>
                </template>
            </draggable>

            <!-- Filtered grid (not draggable) -->
            <div
                v-else
                class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5"
            >
                <div
                    v-for="item in filteredImages"
                    :key="item.id"
                    class="group relative overflow-hidden rounded-lg border"
                >
                    <div class="aspect-square">
                        <img
                            :src="item.thumbnail_url"
                            :alt="item.alt_text || item.caption || 'Gallery image'"
                            class="h-full w-full object-cover"
                        />
                    </div>
                    <div class="absolute top-1.5 right-1.5">
                        <StatusBadge :status="item.status" compact />
                    </div>
                    <div class="absolute inset-0 flex items-end bg-linear-to-t from-black/60 via-transparent to-transparent opacity-0 transition-opacity group-hover:opacity-100">
                        <div class="flex w-full items-center justify-between p-2">
                            <span class="truncate text-xs text-white">{{ item.caption || 'No caption' }}</span>
                            <div class="flex shrink-0 gap-1">
                                <Button variant="ghost" size="icon-sm" class="text-white hover:text-white hover:bg-white/20" @click="openEditDialog(item)">
                                    <Pencil class="size-3.5" />
                                </Button>
                                <Button variant="ghost" size="icon-sm" class="text-white hover:text-red-400 hover:bg-white/20" @click="confirmDelete(item)">
                                    <Trash2 class="size-3.5" />
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>

    <!-- Edit dialog -->
    <Dialog :open="showEditDialog" @update:open="showEditDialog = $event">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Edit Image Details</DialogTitle>
            </DialogHeader>

            <div v-if="editingItem" class="mb-4 overflow-hidden rounded-md">
                <img :src="editingItem.thumbnail_url" :alt="editingItem.caption || ''" class="w-full object-cover" style="max-height: 200px;" />
            </div>

            <form @submit.prevent="saveEdit" class="space-y-4">
                <div class="space-y-2">
                    <Label for="gallery-status">Status</Label>
                    <Select v-model="editForm.status">
                        <SelectTrigger id="gallery-status">
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

                <div class="space-y-2">
                    <Label for="gallery-caption">Caption</Label>
                    <Input id="gallery-caption" v-model="editForm.caption" placeholder="Photo caption..." />
                    <p v-if="errors.caption" class="text-destructive text-xs">{{ errors.caption[0] }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="gallery-alt">Alt Text</Label>
                    <Input id="gallery-alt" v-model="editForm.alt_text" placeholder="Descriptive alt text for accessibility..." />
                    <p v-if="errors.alt_text" class="text-destructive text-xs">{{ errors.alt_text[0] }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="gallery-credit">Credit</Label>
                    <Input id="gallery-credit" v-model="editForm.credit" placeholder="Photo by..." />
                    <p v-if="errors.credit" class="text-destructive text-xs">{{ errors.credit[0] }}</p>
                </div>

                <DialogFooter>
                    <Button type="button" variant="outline" @click="showEditDialog = false">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="saving">
                        <Spinner v-if="saving" class="size-4" />
                        Save Changes
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>

    <!-- Delete confirmation -->
    <Dialog :open="showDeleteConfirm" @update:open="showDeleteConfirm = $event">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Delete Gallery Image</DialogTitle>
            </DialogHeader>
            <div v-if="deletingItem" class="mb-4 overflow-hidden rounded-md">
                <img :src="deletingItem.thumbnail_url" :alt="deletingItem.caption || ''" class="w-full object-cover" style="max-height: 150px;" />
            </div>
            <p class="text-muted-foreground text-sm">
                Are you sure you want to delete this image? This action cannot be undone.
            </p>
            <DialogFooter>
                <Button variant="outline" @click="showDeleteConfirm = false">
                    Cancel
                </Button>
                <Button variant="destructive" :disabled="deleting" @click="deleteImage">
                    <Spinner v-if="deleting" class="size-4" />
                    Delete
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
