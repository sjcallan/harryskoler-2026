<?php

namespace App\Http\Middleware;

use App\Support\ContentVisibility;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Lets the team share a signed preview link with external reviewers.
 *
 * How it works:
 *   • Visiting any web page with `?preview=<token>` validates the token against the
 *     `preview_token` config value. On success we flip a session flag and redirect
 *     to the same URL without the query param (so the token does not leak into
 *     bookmarks, analytics, or shared links).
 *   • Visiting any web page with `?preview=off` clears the flag.
 *   • While the flag is set, public API endpoints include draft content
 *     (see `App\Support\ContentVisibility`).
 *
 * Scope: session-only, so the preview disappears when the browser is closed.
 */
class HandlePreviewMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->hasSession()) {
            return $next($request);
        }

        $value = $request->query(ContentVisibility::QUERY_PARAM);

        if ($value === null) {
            return $next($request);
        }

        if ($value === 'off') {
            $request->session()->forget(ContentVisibility::SESSION_KEY);

            return redirect()->to($this->cleanUrl($request));
        }

        $configured = (string) config('app.preview_token', '');

        if ($configured !== '' && hash_equals($configured, (string) $value)) {
            $request->session()->put(ContentVisibility::SESSION_KEY, true);

            return redirect()->to($this->cleanUrl($request));
        }

        return $next($request);
    }

    /**
     * Rebuild the current URL without the `preview` query parameter.
     */
    private function cleanUrl(Request $request): string
    {
        $query = collect($request->query())
            ->except(ContentVisibility::QUERY_PARAM)
            ->all();

        $base = $request->url();

        return $query === [] ? $base : $base.'?'.http_build_query($query);
    }
}
