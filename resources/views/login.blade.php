<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }} · Login</title>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#FDFDFC] text-[#1b1b18] flex items-center justify-center min-h-screen p-6">
        <main class="w-full max-w-md bg-white shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] border border-[#e3e3e0] rounded-lg p-6">
            <h1 class="text-lg font-semibold mb-2">Login</h1>
            <p class="text-sm text-[#706f6c] mb-4">
                Authentication is not configured yet. If you are the administrator, please set up the
                Laravel auth scaffolding for this project.
            </p>
            <a
                href="{{ url('/dashboard') }}"
                class="inline-flex items-center justify-center px-4 py-2 rounded-sm bg-[#1b1b18] text-white text-sm"
            >
                Go to dashboard
            </a>
        </main>
    </body>
</html>
