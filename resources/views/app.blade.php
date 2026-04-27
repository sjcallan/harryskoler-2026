<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name="description" content="The music of jazz clarinetist and educator Harry Skoler.">
        @php
            // Keep authenticated admin screens and auth flows out of search indexes.
            $path = ltrim(request()->path(), '/');
            $isPrivatePath = $path === 'admin'
                || str_starts_with($path, 'admin/')
                || str_starts_with($path, 'login')
                || str_starts_with($path, 'register')
                || str_starts_with($path, 'password')
                || str_starts_with($path, 'forgot-password')
                || str_starts_with($path, 'reset-password')
                || str_starts_with($path, 'verify-email')
                || str_starts_with($path, 'confirm-password')
                || str_starts_with($path, 'two-factor')
                || str_starts_with($path, 'settings');
        @endphp
        @if ($isPrivatePath)
            <meta name="robots" content="noindex, nofollow">
            <meta name="googlebot" content="noindex, nofollow">
        @else
            <meta name="robots" content="index, follow">
            <link rel="canonical" href="{{ url()->current() }}">
        @endif

        <meta property="og:type" content="website">
        <meta property="og:site_name" content="Harry Skoler">
        <meta property="og:title" content="Harry Skoler — Jazz Musician">
        <meta property="og:description" content="The music of jazz clarinetist and educator Harry Skoler.">
        <meta property="og:image" content="{{ asset('images/albums/echoes.png') }}">
        <meta property="og:url" content="{{ url()->current() }}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Harry Skoler — Jazz Musician">
        <meta name="twitter:description" content="The music of jazz clarinetist and educator Harry Skoler.">
        <meta name="twitter:image" content="{{ asset('images/albums/echoes.png') }}">

        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';
                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        <style>
            html { background-color: oklch(1 0 0); }
            html.dark { background-color: oklch(0.145 0 0); }
        </style>

        <title inertia>{{ config('app.name', 'Harry Skoler') }}</title>

        <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
        <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
        <meta name="apple-mobile-web-app-title" content="Harry Skoler" />
        <link rel="manifest" href="/site.webmanifest" />

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous" />

        <link rel="preload" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" /></noscript>

        <link rel="preload" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&family=DM+Sans:wght@300;400;500;600&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" /></noscript>

        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
