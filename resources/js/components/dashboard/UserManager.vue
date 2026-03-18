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
    Search,
    Plus,
    Pencil,
    Trash2,
    Users,
    AlertCircle,
    ShieldCheck,
    Mail,
} from 'lucide-vue-next';

interface UserItem {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

const users = ref<UserItem[]>([]);
const loading = ref(true);
const searchQuery = ref('');
const showForm = ref(false);
const showDeleteConfirm = ref(false);
const saving = ref(false);
const deleting = ref(false);
const editingItem = ref<UserItem | null>(null);
const deletingItem = ref<UserItem | null>(null);
const errors = ref<Record<string, string[]>>({});
const flashMessage = ref('');
const flashType = ref<'success' | 'error'>('success');

const form = ref({
    name: '',
    email: '',
    password: '',
});

const filteredUsers = computed(() => {
    if (!searchQuery.value) return users.value;
    const q = searchQuery.value.toLowerCase();
    return users.value.filter(
        (item) =>
            item.name.toLowerCase().includes(q) ||
            item.email.toLowerCase().includes(q),
    );
});

function getCsrfToken(): string {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta?.getAttribute('content') ?? '';
}

async function fetchUsers() {
    loading.value = true;
    try {
        const res = await fetch('/api/users');
        users.value = await res.json();
    } catch {
        flash('Failed to load users.', 'error');
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
    form.value = { name: '', email: '', password: '' };
    showForm.value = true;
}

function openEditForm(item: UserItem) {
    editingItem.value = item;
    errors.value = {};
    form.value = {
        name: item.name,
        email: item.email,
        password: '',
    };
    showForm.value = true;
}

function confirmDelete(item: UserItem) {
    deletingItem.value = item;
    showDeleteConfirm.value = true;
}

function formatDate(dateStr: string): string {
    return new Date(dateStr).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
}

async function saveUser() {
    saving.value = true;
    errors.value = {};

    const payload: Record<string, string> = {
        name: form.value.name,
        email: form.value.email,
    };
    if (form.value.password) {
        payload.password = form.value.password;
    }

    const url = editingItem.value
        ? `/api/users/${editingItem.value.id}`
        : '/api/users';

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
        flash(editingItem.value ? 'User updated.' : 'User created.');
        await fetchUsers();
    } catch {
        flash('Network error. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
}

async function deleteUser() {
    if (!deletingItem.value) return;
    deleting.value = true;

    try {
        const res = await fetch(`/api/users/${deletingItem.value.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
            },
        });

        if (!res.ok) {
            if (res.status === 403) {
                const data = await res.json();
                flash(data.message || 'Cannot delete this user.', 'error');
            } else {
                flash('Failed to delete user.', 'error');
            }
            return;
        }

        showDeleteConfirm.value = false;
        flash('User deleted.');
        await fetchUsers();
    } catch {
        flash('Network error. Please try again.', 'error');
    } finally {
        deleting.value = false;
    }
}

onMounted(fetchUsers);
</script>

<template>
    <Card>
        <CardHeader class="flex-row items-center justify-between space-y-0">
            <CardTitle class="text-xl font-semibold">Users</CardTitle>
            <Button size="sm" @click="openCreateForm">
                <Plus class="size-4" />
                Add User
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
                    placeholder="Search users..."
                    class="pl-9"
                />
            </div>

            <div v-if="loading" class="flex items-center justify-center py-12">
                <Spinner class="text-muted-foreground size-6" />
            </div>

            <div
                v-else-if="filteredUsers.length === 0"
                class="text-muted-foreground flex flex-col items-center justify-center gap-2 py-12 text-center"
            >
                <Users class="size-10 opacity-40" />
                <p v-if="searchQuery">No results for "{{ searchQuery }}"</p>
                <p v-else>No users yet. Add your first one.</p>
            </div>

            <div v-else class="divide-y rounded-md border">
                <div
                    v-for="item in filteredUsers"
                    :key="item.id"
                    class="flex items-center gap-4 p-4 transition-colors hover:bg-muted/40"
                >
                    <div class="bg-primary/10 text-primary flex size-10 shrink-0 items-center justify-center rounded-full">
                        <span class="text-sm font-semibold uppercase">{{ item.name.charAt(0) }}</span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <p class="truncate text-sm font-medium">{{ item.name }}</p>
                            <ShieldCheck
                                v-if="item.email_verified_at"
                                class="text-primary size-3.5 shrink-0"
                                title="Email verified"
                            />
                        </div>
                        <div class="text-muted-foreground mt-0.5 flex items-center gap-1.5 text-xs">
                            <Mail class="size-3" />
                            <span class="truncate">{{ item.email }}</span>
                            <span class="text-muted-foreground/50 mx-1">&middot;</span>
                            <span class="shrink-0">Joined {{ formatDate(item.created_at) }}</span>
                        </div>
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
        </CardContent>
    </Card>

    <!-- Create / Edit Dialog -->
    <Dialog :open="showForm" @update:open="showForm = $event">
        <DialogContent class="sm:max-w-xl">
            <DialogHeader>
                <DialogTitle>
                    {{ editingItem ? 'Edit User' : 'New User' }}
                </DialogTitle>
            </DialogHeader>

            <form @submit.prevent="saveUser" class="space-y-4">
                <div class="space-y-2">
                    <Label for="user-name">Name</Label>
                    <Input
                        id="user-name"
                        v-model="form.name"
                        placeholder="Full name"
                        required
                    />
                    <p v-if="errors.name" class="text-destructive text-xs">{{ errors.name[0] }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="user-email">Email</Label>
                    <Input
                        id="user-email"
                        v-model="form.email"
                        type="email"
                        placeholder="email@example.com"
                        required
                    />
                    <p v-if="errors.email" class="text-destructive text-xs">{{ errors.email[0] }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="user-password">
                        Password
                        <span v-if="editingItem" class="text-muted-foreground font-normal">(leave blank to keep current)</span>
                    </Label>
                    <Input
                        id="user-password"
                        v-model="form.password"
                        type="password"
                        placeholder="••••••••"
                        :required="!editingItem"
                        autocomplete="new-password"
                    />
                    <p v-if="errors.password" class="text-destructive text-xs">{{ errors.password[0] }}</p>
                </div>

                <DialogFooter>
                    <Button type="button" variant="outline" @click="showForm = false">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="saving">
                        <Spinner v-if="saving" class="size-4" />
                        {{ editingItem ? 'Save Changes' : 'Create User' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>

    <!-- Delete Confirmation Dialog -->
    <Dialog :open="showDeleteConfirm" @update:open="showDeleteConfirm = $event">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Delete User</DialogTitle>
            </DialogHeader>
            <p class="text-muted-foreground text-sm">
                Are you sure you want to delete
                <strong class="text-foreground">{{ deletingItem?.name }}</strong>
                (<span class="text-foreground">{{ deletingItem?.email }}</span>)?
                This action cannot be undone.
            </p>
            <DialogFooter>
                <Button variant="outline" @click="showDeleteConfirm = false">
                    Cancel
                </Button>
                <Button variant="destructive" :disabled="deleting" @click="deleteUser">
                    <Spinner v-if="deleting" class="size-4" />
                    Delete
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
