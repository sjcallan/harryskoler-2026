<script setup lang="ts">
import { watch, onBeforeUnmount } from 'vue';
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';
import Link from '@tiptap/extension-link';
import {
    Bold,
    Italic,
    Underline as UnderlineIcon,
    Strikethrough,
    Heading2,
    Heading3,
    List,
    ListOrdered,
    Quote,
    Link as LinkIcon,
    Unlink,
    Undo2,
    Redo2,
} from 'lucide-vue-next';

const props = withDefaults(
    defineProps<{
        modelValue?: string;
        placeholder?: string;
    }>(),
    {
        modelValue: '',
        placeholder: '',
    },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const editor = useEditor({
    content: props.modelValue,
    extensions: [
        StarterKit.configure({
            heading: { levels: [2, 3] },
        }),
        Underline,
        Link.configure({
            openOnClick: false,
            HTMLAttributes: { target: '_blank', rel: 'noopener noreferrer' },
        }),
    ],
    editorProps: {
        attributes: {
            class: 'tiptap-content',
        },
    },
    onUpdate: ({ editor: ed }) => {
        emit('update:modelValue', ed.getHTML());
    },
});

watch(
    () => props.modelValue,
    (val) => {
        if (editor.value && val !== editor.value.getHTML()) {
            editor.value.commands.setContent(val, false);
        }
    },
);

onBeforeUnmount(() => {
    editor.value?.destroy();
});

function setLink() {
    if (!editor.value) return;
    const prev = editor.value.getAttributes('link').href;
    const url = window.prompt('URL', prev ?? 'https://');
    if (url === null) return;
    if (url === '') {
        editor.value.chain().focus().extendMarkRange('link').unsetLink().run();
        return;
    }
    editor.value.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
}

type BtnDef = {
    icon: typeof Bold;
    action: () => void;
    active?: string;
    title: string;
};

function toolbar(): BtnDef[] {
    const e = editor.value;
    if (!e) return [];
    return [
        { icon: Bold, action: () => e.chain().focus().toggleBold().run(), active: 'bold', title: 'Bold' },
        { icon: Italic, action: () => e.chain().focus().toggleItalic().run(), active: 'italic', title: 'Italic' },
        { icon: UnderlineIcon, action: () => e.chain().focus().toggleUnderline().run(), active: 'underline', title: 'Underline' },
        { icon: Strikethrough, action: () => e.chain().focus().toggleStrike().run(), active: 'strike', title: 'Strikethrough' },
        { icon: Heading2, action: () => e.chain().focus().toggleHeading({ level: 2 }).run(), active: 'heading', title: 'Heading 2' },
        { icon: Heading3, action: () => e.chain().focus().toggleHeading({ level: 3 }).run(), active: 'heading', title: 'Heading 3' },
        { icon: List, action: () => e.chain().focus().toggleBulletList().run(), active: 'bulletList', title: 'Bullet list' },
        { icon: ListOrdered, action: () => e.chain().focus().toggleOrderedList().run(), active: 'orderedList', title: 'Ordered list' },
        { icon: Quote, action: () => e.chain().focus().toggleBlockquote().run(), active: 'blockquote', title: 'Blockquote' },
        { icon: LinkIcon, action: setLink, active: 'link', title: 'Link' },
        { icon: Unlink, action: () => e.chain().focus().unsetLink().run(), title: 'Remove link' },
        { icon: Undo2, action: () => e.chain().focus().undo().run(), title: 'Undo' },
        { icon: Redo2, action: () => e.chain().focus().redo().run(), title: 'Redo' },
    ];
}

function isActive(name: string): boolean {
    if (!editor.value) return false;
    if (name === 'heading') {
        return editor.value.isActive('heading');
    }
    return editor.value.isActive(name);
}
</script>

<template>
    <div class="tiptap-wrapper border-input dark:bg-input/30 overflow-hidden rounded-md border bg-transparent shadow-xs transition-[color,box-shadow] focus-within:border-ring focus-within:ring-ring/50 focus-within:ring-[3px]">
        <div v-if="editor" class="border-input flex flex-wrap items-center gap-0.5 border-b px-2 py-1.5">
            <button
                v-for="(btn, i) in toolbar()"
                :key="i"
                type="button"
                :title="btn.title"
                class="hover:bg-accent hover:text-accent-foreground inline-flex size-7 items-center justify-center rounded-sm transition-colors"
                :class="btn.active && isActive(btn.active) ? 'bg-accent text-accent-foreground' : 'text-muted-foreground'"
                @click="btn.action"
            >
                <component :is="btn.icon" class="size-3.5" />
            </button>
        </div>
        <EditorContent :editor="editor" />
    </div>
</template>

<style>
.tiptap-content {
    min-height: 120px;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    line-height: 1.625;
    outline: none;
}

.tiptap-content p {
    margin-bottom: 0.5em;
}

.tiptap-content p:last-child {
    margin-bottom: 0;
}

.tiptap-content h2 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-top: 0.75em;
    margin-bottom: 0.5em;
    line-height: 1.3;
}

.tiptap-content h3 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-top: 0.75em;
    margin-bottom: 0.5em;
    line-height: 1.3;
}

.tiptap-content ul,
.tiptap-content ol {
    padding-left: 1.5em;
    margin-bottom: 0.5em;
}

.tiptap-content ul {
    list-style: disc;
}

.tiptap-content ol {
    list-style: decimal;
}

.tiptap-content li {
    margin-bottom: 0.15em;
}

.tiptap-content blockquote {
    border-left: 3px solid var(--border);
    padding-left: 0.75em;
    margin-left: 0;
    margin-bottom: 0.5em;
    color: var(--muted-foreground);
    font-style: italic;
}

.tiptap-content a {
    color: var(--primary);
    text-decoration: underline;
    cursor: pointer;
}

.tiptap-content strong {
    font-weight: 600;
}

.tiptap-content p.is-editor-empty:first-child::before {
    content: attr(data-placeholder);
    float: left;
    color: var(--muted-foreground);
    pointer-events: none;
    height: 0;
}
</style>
