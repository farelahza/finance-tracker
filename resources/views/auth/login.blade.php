<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Masuk - Finance Tracker</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans antialiased text-[#2A210B] bg-[#E8D17F]">

        <div class="min-h-screen lg:grid lg:grid-cols-2">

            {{-- ================= PANEL KIRI (brand) ================= --}}
            <aside class="relative hidden overflow-hidden lg:flex lg:flex-col lg:justify-between
                          bg-gradient-to-br from-[#14213D] via-[#1B2B52] to-[#233763] p-12 xl:p-16">

                {{-- dekorasi --}}
                <div class="pointer-events-none absolute -right-24 -top-24 h-96 w-96 rounded-full border border-[#E3BE5B]/20"></div>
                <div class="pointer-events-none absolute -right-8 -top-8 h-64 w-64 rounded-full border border-[#E3BE5B]/20"></div>
                <div class="pointer-events-none absolute -bottom-32 -left-32 h-96 w-96 rounded-full bg-[#E3BE5B]/10 blur-3xl"></div>

                {{-- brand --}}
                <div class="relative flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl border border-[#E3BE5B]/50 bg-[#E3BE5B]/10">
                        <svg class="h-6 w-6 text-[#F0D27A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 17l5-5 4 4 8-8" />
                            <path d="M15 8h5v5" />
                        </svg>
                    </span>
                    <span class="font-['Playfair_Display',serif] text-xl font-semibold tracking-tight text-[#F0D27A]">
                        Finance Tracker
                    </span>
                </div>

                {{-- headline --}}
                <div class="relative max-w-lg">
                    <h2 class="font-['Playfair_Display',serif] text-4xl xl:text-5xl font-semibold leading-tight text-[#F6E19A]">
                        Kelola keuanganmu dengan lebih tenang.
                    </h2>
                    <p class="mt-5 text-base leading-relaxed text-[#D9C99A]">
                        Catat pemasukan dan pengeluaran, pantau saldo, lalu wujudkan target tabunganmu, semuanya di satu tempat.
                    </p>

                    <ul class="mt-10 space-y-5">
                        <li class="flex items-center gap-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#E3BE5B]/15 ring-1 ring-[#E3BE5B]/30 text-lg text-[#F0D27A]" aria-hidden="true"><x-icon name="chart" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /></span>
                            <span class="text-sm font-medium text-[#E6D08A]">Ringkasan saldo dan grafik keuangan yang jelas</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#E3BE5B]/15 ring-1 ring-[#E3BE5B]/30 text-lg text-[#F0D27A]" aria-hidden="true"><x-icon name="target" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /></span>
                            <span class="text-sm font-medium text-[#E6D08A]">Target tabungan dengan progres yang mudah dipantau</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#E3BE5B]/15 ring-1 ring-[#E3BE5B]/30 text-lg text-[#F0D27A]" aria-hidden="true"><x-icon name="file" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /></span>
                            <span class="text-sm font-medium text-[#E6D08A]">Export laporan ke Excel dan PDF</span>
                        </li>
                    </ul>
                </div>

                <p class="relative text-xs text-[#B9A56B]">&copy; {{ date('Y') }} Finance Tracker</p>
            </aside>

            {{-- ================= PANEL KANAN (form) ================= --}}
            <main class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6
                         bg-gradient-to-br from-[#EDCF72] via-[#DDB84A] to-[#C29B2E] lg:min-h-0">

                <div class="w-full max-w-md">

                    {{-- brand untuk mobile --}}
                    <div class="mb-8 flex items-center justify-center gap-3 lg:hidden">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-[#14213D] to-[#233763] shadow-md">
                            <svg class="h-6 w-6 text-[#F0D27A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 17l5-5 4 4 8-8" />
                                <path d="M15 8h5v5" />
                            </svg>
                        </span>
                        <span class="font-['Playfair_Display',serif] text-xl font-semibold tracking-tight text-[#2A210B]">
                            Finance Tracker
                        </span>
                    </div>

                    <div class="rounded-3xl border border-[#BE9A35] bg-[#F4E4A6] p-6 shadow-2xl shadow-[#8A650F]/20 sm:p-8">

                        <h1 class="font-['Playfair_Display',serif] text-3xl font-semibold tracking-tight text-[#2A210B]">
                            Selamat datang kembali
                        </h1>
                        <p class="mt-2 text-sm text-[#54421A]">
                            Masuk ke akunmu untuk melanjutkan.
                        </p>

                        {{-- pesan status (mis. setelah reset password) --}}
                        @if (session('status'))
                            <div role="status"
                                 class="mt-5 rounded-xl border border-[#176B50]/30 bg-[#176B50]/10 px-4 py-3 text-sm font-medium text-[#124F3B]">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5" x-data="{ show: false }">
                            @csrf

                            {{-- Email --}}
                            <div>
                                <label for="email" class="mb-1.5 block text-sm font-medium text-[#43340F]">Email</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#7A6630]">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <rect x="3" y="5" width="18" height="14" rx="2" />
                                            <path d="M3 7l9 6 9-6" />
                                        </svg>
                                    </span>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                                           required autofocus autocomplete="username" placeholder="nama@email.com"
                                           @error('email') aria-invalid="true" @enderror
                                           class="block w-full rounded-xl border bg-[#FBF1CC] py-3 pl-11 pr-4 text-base sm:text-sm text-[#2A210B] shadow-sm
                                                  placeholder:text-[#7A6630]/70 transition
                                                  focus:border-[#B8892B] focus:outline-none focus:ring-2 focus:ring-[#B8892B]/30
                                                  {{ $errors->has('email') ? 'border-[#8E2740]' : 'border-[#A8841F]' }}">
                                </div>
                                @error('email')
                                    <p class="mt-1.5 text-sm font-medium text-[#8E2740]">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div>
                                <label for="password" class="mb-1.5 block text-sm font-medium text-[#43340F]">Kata Sandi</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#7A6630]">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <rect x="4" y="10" width="16" height="10" rx="2" />
                                            <path d="M8 10V7a4 4 0 018 0v3" />
                                        </svg>
                                    </span>
                                    <input id="password" name="password" required autocomplete="current-password"
                                           :type="show ? 'text' : 'password'" type="password" placeholder="Masukkan kata sandi"
                                           class="block w-full rounded-xl border border-[#A8841F] bg-[#FBF1CC] py-3 pl-11 pr-12 text-base sm:text-sm text-[#2A210B] shadow-sm
                                                  placeholder:text-[#7A6630]/70 transition
                                                  focus:border-[#B8892B] focus:outline-none focus:ring-2 focus:ring-[#B8892B]/30">
                                    <button type="button" @click="show = !show"
                                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#7A6630] hover:text-[#2A210B] focus:outline-none"
                                            :aria-label="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                                        <svg x-show="!show" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                        <svg x-show="show" x-cloak style="display:none" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M3 3l18 18" />
                                            <path d="M10.6 5.1A10.7 10.7 0 0112 5c6.5 0 10 7 10 7a17 17 0 01-3.2 4.2M6.6 6.6A17 17 0 002 12s3.5 7 10 7a10 10 0 004.4-1" />
                                            <path d="M9.9 9.9a3 3 0 004.2 4.2" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1.5 text-sm font-medium text-[#8E2740]">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Ingat saya + lupa password --}}
                            <div class="flex items-center justify-between gap-3">
                                <label for="remember_me" class="inline-flex cursor-pointer items-center gap-2">
                                    <input id="remember_me" type="checkbox" name="remember"
                                           class="h-4 w-4 rounded border-[#A8841F] bg-[#FBF1CC] text-[#14213D] focus:ring-2 focus:ring-[#B8892B]/40">
                                    <span class="text-sm text-[#43340F]">Ingat saya</span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                       class="text-sm font-medium text-[#14213D] underline-offset-4 hover:underline focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40 rounded">
                                        Lupa kata sandi?
                                    </a>
                                @endif
                            </div>

                            {{-- Tombol --}}
                            <button type="submit"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl
                                           bg-gradient-to-r from-[#14213D] to-[#233763] px-5 py-3 text-sm font-semibold text-[#F0D27A]
                                           shadow-lg shadow-[#14213D]/30 transition hover:brightness-125
                                           focus:outline-none focus:ring-2 focus:ring-[#14213D]/50 focus:ring-offset-2 focus:ring-offset-[#F4E4A6]">
                                Masuk
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 12h14M13 6l6 6-6 6" />
                                </svg>
                            </button>
                        </form>
                    </div>

                </div>
            </main>
        </div>

    </body>
</html>