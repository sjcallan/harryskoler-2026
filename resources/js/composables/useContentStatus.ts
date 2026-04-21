export type ContentStatus = 'draft' | 'published' | 'archived';

export const CONTENT_STATUSES: { value: ContentStatus; label: string; description: string }[] = [
    { value: 'draft', label: 'Draft', description: 'Hidden from the public site. Staff and preview links still see it.' },
    { value: 'published', label: 'Published', description: 'Visible on the public website to everyone.' },
    { value: 'archived', label: 'Archived', description: 'Hidden everywhere on the public site. Retained for records.' },
];

export function isContentStatus(value: unknown): value is ContentStatus {
    return value === 'draft' || value === 'published' || value === 'archived';
}

/**
 * Filter options usable in list-level status filters. Includes an "all" sentinel.
 */
export const STATUS_FILTER_OPTIONS = [
    { value: 'all', label: 'All statuses' },
    ...CONTENT_STATUSES.map(({ value, label }) => ({ value, label })),
];
