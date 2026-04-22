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
    Quote,
    AlertCircle,
    GripVertical,
    Eye,
    EyeOff,
} from 'lucide-vue-next';

interface QuoteItem {
    id: number;
    quote: string;
    person: string | null;
    company: string | null;
    is_active: boolean;
    sort_order: number;
    created_at: string;
    updated_at: string;
}

const quotes = ref<QuoteItem[]>([]);
const loading = ref(true);
const searchQuery = ref('');
const showForm = ref(false);
const showDeleteConfirm = ref(false);
const saving = ref(false);
const deleting = ref(false);
const editingItem = ref<QuoteItem | null>(null);
const deletingItem = ref<QuoteItem | null>(null);
const errors = ref<Record<string, string[]>>({});
const flashMessage = ref('');
const flashType = ref<'success' | 'error'>('success');

const form = ref({
    quote: '',
    person: '',
    company: '',
    is_active: true,
    sort_order: 0,
});

const filteredQuotes = computed(() => {
    if (!searchQuery.value) return quotes.value;
    const q = searchQuery.value.toLowerCase();
    return quotes.value.filter(
        (item) =>
            item.quote.toLowerCase().includes(q) ||
            (item.person?.toLowerCase().includes(q) ?? false) ||
            (item.company?.toLowerCase().includes(q) ?? false),
    );
});

function getCsrfToken(): string {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta?.getAttribute('content') ?? '';
}

async function fetchQuotes() {
    loading.value = true;
    try {
        const res = await fetch('/api/quotes');
        quotes.value = await res.json();
    } catch {
        flash('Failed to load quotes.', 'error');
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
    form.value = { quote: '', person: '', company: '', is_active: true, sort_order: 0 };
    showForm.value = true;
}

function openEditForm(item: QuoteItem) {
    editingItem.value = item;
    errors.value = {};
    form.value = {
        quote: item.quote,
        person: item.person ?? '',
        company: item.company ?? '',
        is_active: item.is_active,
        sort_order: item.sort_order,
    };
    showForm.value = true;
}

function confirmDelete(item: QuoteItem) {
    deletingItem.value = item;
    showDeleteConfirm.value = true;
}

function stripHtml(html: string): string {
    const tmp = document.createElement('div');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
}

async function saveQuote() {
    saving.value = true;
    errors.value = {};

    const payload: Record<string, string | number | boolean> = {
        quote: form.value.quote,
        is_active: form.value.is_active,
        sort_order: form.value.sort_order,
    };
    if (form.value.person) payload.person = form.value.person;
    if (form.value.company) payload.company = form.value.company;

    const url = editingItem.value
        ? `/api/quotes/${editingItem.value.id}`
        : '/api/quotes';

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
        flash(editingItem.value ? 'Quote updated.' : 'Quote created.');
        await fetchQuotes();
    } catch {
        flash('Network error. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
}

async function deleteQuote() {
    if (!deletingItem.value) return;
    deleting.value = true;

    try {
        const res = await fetch(`/api/quotes/${deletingItem.value.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
            },
        });

        if (!res.ok) {
            flash('Failed to delete quote.', 'error');
            return;
        }

        showDeleteConfirm.value = false;
        flash('Quote deleted.');
        await fetchQuotes();
    } catch {
        flash('Network error. Please try again.', 'error');
    } finally {
        deleting.value = false;
    }
}

onMounted(fetchQuotes);
</script>

<template>
    <Card>
        <CardHeader class="flex-row items-center justify-between space-y-0">
            <CardTitle class="text-xl font-semibold">Quotes</CardTitle>
            <Button size="sm" @click="openCreateForm">
                <Plus class="size-4" />
                Add Quote
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

            <div class="relative">
                <Search class="text-muted-foreground absolute top-1/2 left-3 size-4 -translate-y-1/2" />
                <Input
                    v-model="searchQuery"
                    placeholder="Search quotes..."
                    class="pl-9"
                />
            </div>

            <div v-if="loading" class="flex items-center justify-center py-12">
                <Spinner class="text-muted-foreground size-6" />
            </div>

            <div
                v-else-if="filteredQuotes.length === 0"
                class="text-muted-foreground flex flex-col items-center justify-center gap-2 py-12 text-center"
            >
                <Quote class="size-10 opacity-40" />
                <p v-if="searchQuery">No results for "{{ searchQuery }}"</p>
                <p v-else>No quotes yet. Add your first one.</p>
            </div>

            <div v-else class="divide-y rounded-md border">
                <div
                    v-for="item in filteredQuotes"
                    :key="item.id"
                    class="flex items-start gap-4 p-4 transition-colors hover:bg-muted/40"
                    :class="{ 'opacity-50': !item.is_active }"
                >
                    <div class="text-muted-foreground/40 hidden shrink-0 pt-0.5 sm:block">
                        <GripVertical class="size-4" />
                        <span class="text-muted-foreground mt-0.5 block text-center text-[10px]">{{ item.sort_order }}</span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="line-clamp-2 text-sm italic leading-relaxed">
                                    "{{ stripHtml(item.quote) }}"
                                </p>
                                <p class="text-muted-foreground mt-1.5 text-xs">
                                    <span v-if="item.person" class="text-foreground font-medium">{{ item.person }}</span>
                                    <span v-if="item.person && item.company"> &mdash; </span>
                                    <span
                                        v-if="item.company"
                                        class="text-primary font-medium whitespace-pre-line align-top"
                                    >{{ item.company }}</span>
                                    <span
                                        v-if="!item.is_active"
                                        class="text-muted-foreground ml-2 inline-flex items-center gap-1 text-[10px] uppercase tracking-wider"
                                    >
                                        <EyeOff class="size-3" /> Inactive
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
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>

    <Dialog :open="showForm" @update:open="showForm = $event">
        <DialogContent class="sm:max-w-xl">
            <DialogHeader>
                <DialogTitle>
                    {{ editingItem ? 'Edit Quote' : 'New Quote' }}
                </DialogTitle>
            </DialogHeader>

            <form @submit.prevent="saveQuote" class="space-y-4">
                <div class="space-y-2">
                    <Label for="quote-text">Quote</Label>
                    <Textarea
                        id="quote-text"
                        v-model="form.quote"
                        placeholder="The quote text (HTML allowed for emphasis)..."
                        class="min-h-[120px]"
                    />
                    <p v-if="errors.quote" class="text-destructive text-xs">{{ errors.quote[0] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <Label for="quote-person">Person (optional)</Label>
                        <Input id="quote-person" v-model="form.person" placeholder="Author name" />
                        <p v-if="errors.person" class="text-destructive text-xs">{{ errors.person[0] }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="quote-company">Publication / Company (optional)</Label>
                        <Textarea
                            id="quote-company"
                            v-model="form.company"
                            placeholder="e.g. JazzTimes&#10;New York, NY"
                            class="min-h-[72px]"
                            rows="2"
                        />
                        <p class="text-muted-foreground text-[11px]">Line breaks are preserved on the site.</p>
                        <p v-if="errors.company" class="text-destructive text-xs">{{ errors.company[0] }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <Label for="quote-sort-order">Sort Order</Label>
                        <Input id="quote-sort-order" v-model.number="form.sort_order" type="number" min="0" />
                        <p v-if="errors.sort_order" class="text-destructive text-xs">{{ errors.sort_order[0] }}</p>
                    </div>
                    <div class="flex items-end gap-3 pb-0.5">
                        <label class="flex cursor-pointer items-center gap-2 text-sm">
                            <input type="checkbox" v-model="form.is_active" class="accent-primary size-4 rounded" />
                            <Eye class="size-4" />
                            Active (visible on site)
                        </label>
                    </div>
                </div>

                <DialogFooter>
                    <Button type="button" variant="outline" @click="showForm = false">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="saving">
                        <Spinner v-if="saving" class="size-4" />
                        {{ editingItem ? 'Save Changes' : 'Create Quote' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>

    <Dialog :open="showDeleteConfirm" @update:open="showDeleteConfirm = $event">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Delete Quote</DialogTitle>
            </DialogHeader>
            <p class="text-muted-foreground text-sm">
                Are you sure you want to delete the quote
                <template v-if="deletingItem?.person">by <strong class="text-foreground">{{ deletingItem.person }}</strong></template>?
                This action cannot be undone.
            </p>
            <DialogFooter>
                <Button variant="outline" @click="showDeleteConfirm = false">
                    Cancel
                </Button>
                <Button variant="destructive" :disabled="deleting" @click="deleteQuote">
                    <Spinner v-if="deleting" class="size-4" />
                    Delete
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
