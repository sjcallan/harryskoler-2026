<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Centralised content-visibility rules used by the API controllers.
 *
 * There are three viewer contexts:
 *  1. Admin UI  — request opts-in with `?admin=1` while authenticated. Sees every status
 *                 and may further narrow with `?status=draft|published|archived`.
 *  2. Signed-in staff on the public site — auto-sees drafts + published, never archived.
 *  3. Preview session — anonymous viewer that presented a valid preview token during the
 *                       session. Same visibility as signed-in staff.
 *  4. Everyone else — only published content.
 */
class ContentVisibility
{
    public const SESSION_KEY = 'preview_drafts';
    public const QUERY_PARAM = 'preview';

    public static function isPreviewSession(Request $request): bool
    {
        if (! $request->hasSession()) {
            return false;
        }

        return (bool) $request->session()->get(self::SESSION_KEY, false);
    }

    public static function isAdminContext(Request $request): bool
    {
        return $request->boolean('admin') && (bool) $request->user();
    }

    /**
     * Apply draft/published/archived filtering to the given query.
     */
    public static function apply(Builder $query, Request $request): Builder
    {
        $authenticated = (bool) $request->user();
        $adminContext = self::isAdminContext($request);
        $previewSession = self::isPreviewSession($request);

        if ($adminContext) {
            if ($status = $request->input('status')) {
                $query->where('status', $status);
            }

            return $query;
        }

        if ($authenticated || $previewSession) {
            return $query->publishedOrDraft();
        }

        return $query->published();
    }

    /**
     * Determine if a given status is viewable for the current request context.
     * Used to short-circuit `show` endpoints for archived / draft content.
     */
    public static function canView(Request $request, string $status): bool
    {
        if (self::isAdminContext($request)) {
            return true;
        }

        if ($status === 'published') {
            return true;
        }

        if ($status === 'draft') {
            return (bool) $request->user() || self::isPreviewSession($request);
        }

        // archived — hidden from public site regardless.
        return false;
    }
}
