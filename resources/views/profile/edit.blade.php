<x-app-layout>

@php
    $card  = 'rounded-2xl border border-[#BE9A35] bg-[#F4E4A6] shadow-sm';
    $title = 'text-[#2A210B]';
    $muted = 'text-[#54421A]';

    $photoUrl = $user->profile_photo_url;
    $initial  = strtoupper(mb_substr($user->name, 0, 1));
    $joined   = $user->created_at ? $user->created_at->format('d M Y') : null;
@endphp

<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Judul --}}
        <div>
            <h1 class="font-['Playfair_Display',serif] text-3xl sm:text-4xl font-semibold tracking-tight {{ $title }}">
                Profil Saya
            </h1>
            <p class="mt-1 text-sm {{ $muted }}">Kelola informasi akun, foto, dan keamanan kamu.</p>
        </div>

        {{-- Ringkasan akun --}}
        <div class="rounded-2xl border border-[#E3BE5B]/60 p-6 shadow-xl shadow-[#14213D]/30
                    bg-gradient-to-br from-[#14213D] via-[#1B2B52] to-[#233763]">
            <div class="flex items-center gap-5">
                @if($photoUrl)
                    <img src="{{ $photoUrl }}" alt="Foto profil {{ $user->name }}"
                         class="h-20 w-20 shrink-0 rounded-full object-cover ring-4 ring-[#E3BE5B]/60">
                @else
                    <span class="flex h-20 w-20 shrink-0 items-center justify-center rounded-full bg-gradient-to-b from-[#EBCB68] to-[#A67C1A] text-3xl font-bold text-[#2A210B] ring-4 ring-[#E3BE5B]/60">
                        {{ $initial }}
                    </span>
                @endif

                <div class="min-w-0">
                    <p class="truncate font-['Playfair_Display',serif] text-2xl font-semibold text-[#F0D27A]">{{ $user->name }}</p>
                    <p class="truncate text-sm text-[#D9C99A]">{{ $user->email }}</p>
                    @if($joined)
                        <p class="mt-1 text-xs text-[#B9A56B]">Bergabung sejak {{ $joined }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Informasi profil + foto --}}
        <div class="{{ $card }} p-5 sm:p-7">
            @include('profile.partials.update-profile-information-form')
        </div>

        {{-- Kata sandi --}}
        <div class="{{ $card }} p-5 sm:p-7">
            @include('profile.partials.update-password-form')
        </div>

        {{-- Hapus akun --}}
        <div class="rounded-2xl border border-[#8E2740]/40 bg-[#F4E4A6] p-5 shadow-sm sm:p-7">
            @include('profile.partials.delete-user-form')
        </div>

    </div>
</div>

</x-app-layout>