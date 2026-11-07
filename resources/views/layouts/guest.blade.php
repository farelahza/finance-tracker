<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-[#2A210B]">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-[#EDCF72] via-[#DDB84A] to-[#C29B2E]">
            <div>
                <a href="/" class="flex items-center gap-3" aria-label="Finance Tracker">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-[#14213D] to-[#233763] shadow-lg shadow-[#14213D]/30">
                        <svg class="h-7 w-7 text-[#F0D27A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 17l5-5 4 4 8-8" />
                            <path d="M15 8h5v5" />
                        </svg>
                    </span>
                    <span class="font-['Playfair_Display',serif] text-2xl font-semibold tracking-tight text-[#2A210B]">
                        Finance Tracker
                    </span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-[#F4E4A6] border border-[#BE9A35] shadow-lg overflow-hidden rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>