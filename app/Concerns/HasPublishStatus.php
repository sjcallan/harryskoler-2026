<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Adds a publishing workflow (draft / published / archived) to an Eloquent model.
 *
 * Consumers can filter lists via the provided scopes and use the static
 * helpers to validate status input from the admin UI.
 */
trait HasPublishStatus
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_ARCHIVED = 'archived';

    /**
     * @return array<int, string>
     */
    public static function availableStatuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_PUBLISHED,
            self::STATUS_ARCHIVED,
        ];
    }

    /**
     * Only rows visible to anonymous public visitors.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Rows visible to logged-in staff or anyone in preview-mode
     * (drafts + published, but never archived).
     */
    public function scopePublishedOrDraft(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_DRAFT, self::STATUS_PUBLISHED]);
    }

    /**
     * Apply visibility rules to a query based on the current viewer context.
     *
     * @param  Builder  $query
     * @param  bool  $isAuthenticated  Is there an authenticated user on the request?
     * @param  bool  $previewDrafts    Is the session in public preview-mode?
     * @param  bool  $adminContext     Is this request coming from the admin UI (auth required)?
     */
    public function scopeForViewer(
        Builder $query,
        bool $isAuthenticated,
        bool $previewDrafts,
        bool $adminContext = false,
    ): Builder {
        if ($adminContext && $isAuthenticated) {
            return $query;
        }

        if ($isAuthenticated || $previewDrafts) {
            return $query->publishedOrDraft();
        }

        return $query->published();
    }
}
