<x-app-layout>

@php
    $card  = 'rounded-2xl border border-[#BE9A35] bg-[#F4E4A6] shadow-sm';
    $title = 'text-[#2A210B]';
    $muted = 'text-[#54421A]';

    $btnPrimary = 'bg-gradient-to-b from-[#EBCB68] to-[#A67C1A] text-[#2A210B]
        border border-[#8A650F]/60 shadow-md shadow-[#8A650F]/30 hover:brightness-110
        focus:outline-none focus:ring-2 focus:ring-[#8A650F]/60 focus:ring-offset-2 focus:ring-offset-[#F4E4A6]';
    $btnGhost = 'border border-[#A8841F] bg-[#F4E4A6] text-[#2A210B] hover:bg-[#E8D17F]
        focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40';

    $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.');

    $totalTarget   = $goals->sum('target_amount');
    $totalSaved    = $goals->sum(fn ($g) => $g->saved_amount);
    $achievedCount = $goals->filter(fn ($g) => $g->is_achieved)->count();
@endphp

<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- ===== Header ===== --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-['Playfair_Display',serif] text-3xl sm:text-4xl font-semibold tracking-tight {{ $title }}">
                    Target Tabungan
                </h1>
                <p class="mt-1 text-sm {{ $muted }}">
                    Catat progres tabungan impianmu. Tidak memengaruhi saldo dan transaksi utama.
                </p>
            </div>

            <a href="{{ route('savings-goals.create') }}"
               class="inline-flex items-center justify-center gap-2 self-start sm:self-auto rounded-xl px-5 py-2.5 text-sm font-semibold transition {{ $btnPrimary }}">
                <x-icon name="plus" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Tambah Target
            </a>
        </div>

        {{-- ===== Flash ===== --}}
        @if(session('success'))
            <div role="status"
                 class="flex items-center gap-3 rounded-xl border border-[#176B50]/30 bg-[#176B50]/10 px-4 py-3 text-sm font-medium text-[#124F3B]">
                <span aria-hidden="true"><x-icon name="check-circle" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($goals->isEmpty())

            {{-- ===== Empty state ===== --}}
            <div class="{{ $card }} px-6 py-16 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-[#14213D] text-4xl text-[#F0D27A] shadow-lg" aria-hidden="true"><x-icon name="target" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /></div>
                <h2 class="mt-6 font-['Playfair_Display',serif] text-2xl font-semibold {{ $title }}">
                    Belum ada target tabungan
                </h2>
                <p class="mx-auto mt-2 max-w-md text-sm {{ $muted }}">
                    Mulai dengan satu target, misalnya laptop baru, motor, atau liburan.
                    Catat tabunganmu sedikit demi sedikit dan pantau progresnya di sini.
                </p>
                <a href="{{ route('savings-goals.create') }}"
                   class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold transition {{ $btnPrimary }}">
                    <x-icon name="plus" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Buat Target Pertama
                </a>
            </div>

        @else

            {{-- ===== Ringkasan ===== --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-6">

                <div class="rounded-2xl border border-[#E3BE5B]/60 p-5 shadow-xl shadow-[#14213D]/30
                            bg-gradient-to-br from-[#14213D] via-[#1B2B52] to-[#233763]">
                    <p class="text-sm font-semibold text-[#D9C99A]">Total Terkumpul</p>
                    <p class="mt-3 tabular-nums text-2xl font-bold text-[#F0D27A] break-words">
                        {{ $rp($totalSaved) }}
                    </p>
                </div>

                <div class="{{ $card }} p-5">
                    <p class="text-sm font-medium {{ $muted }}">Total Target</p>
                    <p class="mt-3 tabular-nums text-2xl font-bold {{ $title }} break-words">
                        {{ $rp($totalTarget) }}
                    </p>
                </div>

                <div class="{{ $card }} p-5">
                    <p class="text-sm font-medium {{ $muted }}">Target Tercapai</p>
                    <p class="mt-3 tabular-nums text-2xl font-bold text-[#176B50]">
                        {{ $achievedCount }} <span class="text-base font-medium {{ $muted }}">dari {{ $goals->count() }} target</span>
                    </p>
                </div>
            </div>

            {{-- ===== Daftar target ===== --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach($goals as $goal)
                    @php $done = $goal->is_achieved; @endphp

                    <article class="{{ $card }} flex flex-col p-5 sm:p-6">

                        <div class="flex items-start justify-between gap-3">
                            <h2 class="text-lg font-semibold leading-snug {{ $title }} break-words">
                                <a href="{{ route('savings-goals.show', $goal->id) }}" class="hover:underline">
                                    {{ $goal->name }}
                                </a>
                            </h2>

                            @if($done)
                                <span class="shrink-0 rounded-full bg-[#176B50]/15 px-3 py-1 text-xs font-semibold text-[#176B50]">
                                    <x-icon name="check-circle" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Tercapai
                                </span>
                            @else
                                <span class="shrink-0 rounded-full bg-[#14213D]/10 px-3 py-1 text-xs font-semibold text-[#14213D]">
                                    <x-icon name="clock" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Berjalan
                                </span>
                            @endif
                        </div>

                        <div class="mt-4 flex items-end justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-xs {{ $muted }}">Terkumpul</p>
                                <p class="tabular-nums text-xl font-bold {{ $title }} break-words">
                                    {{ $rp($goal->saved_amount) }}
                                </p>
                            </div>
                            <p class="shrink-0 text-2xl font-bold {{ $done ? 'text-[#176B50]' : 'text-[#14213D]' }}">
                                {{ $goal->progress_percent }}%
                            </p>
                        </div>

                        <div class="mt-3">
                            <x-savings-progress :percent="$goal->progress_percent" :achieved="$done" />
                        </div>

                        <dl class="mt-4 space-y-1.5 text-sm">
                            <div class="flex justify-between gap-3">
                                <dt class="{{ $muted }}">Target</dt>
                                <dd class="font-semibold {{ $title }}">{{ $rp($goal->target_amount) }}</dd>
                            </div>
                            <div class="flex justify-between gap-3">
                                <dt class="{{ $muted }}">{{ $done ? 'Kelebihan' : 'Sisa dibutuhkan' }}</dt>
                                <dd class="font-semibold {{ $done ? 'text-[#176B50]' : 'text-[#8E2740]' }}">
                                    {{ $done ? $rp($goal->surplus_amount) : $rp($goal->remaining_amount) }}
                                </dd>
                            </div>
                            @if($goal->deadline)
                                <div class="flex justify-between gap-3">
                                    <dt class="{{ $muted }}">Deadline</dt>
                                    <dd class="font-semibold {{ $goal->is_overdue ? 'text-[#8E2740]' : $title }}">
                                        <x-icon name="calendar" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> {{ $goal->deadline->format('d M Y') }}
                                        @if($goal->is_overdue) <span class="text-xs">(lewat)</span> @endif
                                    </dd>
                                </div>
                            @endif
                        </dl>

                        <div class="mt-5 pt-1">
                            <a href="{{ route('savings-goals.show', $goal->id) }}"
                               class="inline-flex w-full items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ $btnGhost }}">
                                Lihat Detail & Catat Progres →
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

        @endif

    </div>
</div>

</x-app-layout>