<x-app-layout>

@php
    $card  = 'rounded-2xl border border-[#BE9A35] bg-[#F4E4A6] shadow-sm';
    $title = 'text-[#2A210B]';
    $muted = 'text-[#54421A]';
    $label = 'mb-1.5 block text-sm font-medium text-[#43340F]';

    $field = 'w-full rounded-xl border border-[#A8841F] bg-[#FBF1CC] text-[#2A210B] text-base sm:text-sm px-3 py-2.5 shadow-sm
        placeholder:text-[#7A6630] focus:border-[#B8892B] focus:ring-2 focus:ring-[#B8892B]/30 focus:outline-none transition';
    $btnPrimary = 'bg-gradient-to-b from-[#EBCB68] to-[#A67C1A] text-[#2A210B]
        border border-[#8A650F]/60 shadow-md shadow-[#8A650F]/30 hover:brightness-110
        focus:outline-none focus:ring-2 focus:ring-[#8A650F]/60 focus:ring-offset-2 focus:ring-offset-[#F4E4A6]';
    $btnGhost = 'border border-[#A8841F] bg-[#F4E4A6] text-[#2A210B] hover:bg-[#E8D17F]
        focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40';
    $btnEdit = 'border border-[#B8892B]/50 bg-[#B8892B]/10 text-[#6B4C0C] hover:bg-[#B8892B]/20
        focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40';
    $btnDanger = 'border border-[#8E2740]/40 bg-[#8E2740]/10 text-[#8E2740] hover:bg-[#8E2740]/20
        focus:outline-none focus:ring-2 focus:ring-[#8E2740]/40';
    $errorText = 'mt-1.5 text-sm font-medium text-[#8E2740]';

    $rp   = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.');
    $done = $goal->is_achieved;
    $bag  = $errors->getBag('contribution');
@endphp

<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- ===== Header ===== --}}
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div class="min-w-0">
                <a href="{{ route('savings-goals.index') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-[#A8841F] bg-[#F4E4A6] px-4 py-2 text-sm font-semibold text-[#2A210B] shadow-sm transition
                      hover:bg-[#E8D17F] focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40">
                <x-icon name="arrow-left" class="h-4 w-4" /> Semua Target
            </a>
                <h1 class="mt-4 font-['Playfair_Display',serif] text-3xl sm:text-4xl font-semibold tracking-tight {{ $title }} break-words">
                    {{ $goal->name }}
                </h1>
                @if($goal->description)
                    <p class="mt-2 max-w-2xl text-sm {{ $muted }} whitespace-pre-line">{{ $goal->description }}</p>
                @endif
            </div>

            <div class="flex gap-3">
                <a href="{{ route('savings-goals.edit', $goal->id) }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ $btnEdit }}">
                    <x-icon name="pencil" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Edit
                </a>

                <form action="{{ route('savings-goals.destroy', $goal->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Yakin ingin menghapus target ini beserta seluruh riwayat progresnya?')"
                            class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ $btnDanger }}">
                        <x-icon name="trash" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Hapus
                    </button>
                </form>
            </div>
        </div>

        {{-- ===== Flash ===== --}}
        @if(session('success'))
            <div role="status"
                 class="flex items-center gap-3 rounded-xl border border-[#176B50]/30 bg-[#176B50]/10 px-4 py-3 text-sm font-medium text-[#124F3B]">
                <span aria-hidden="true"><x-icon name="check-circle" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- ===== Ringkasan progres ===== --}}
        <section class="rounded-2xl border border-[#E3BE5B]/60 p-6 shadow-xl shadow-[#14213D]/30
                        bg-gradient-to-br from-[#14213D] via-[#1B2B52] to-[#233763]">

            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm font-semibold text-[#D9C99A]">Total Terkumpul</p>
                @if($done)
                    <span class="rounded-full bg-[#2FA77F]/20 px-3 py-1 text-xs font-semibold text-[#8FE3C3]"><x-icon name="check-circle" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Tercapai</span>
                @else
                    <span class="rounded-full bg-[#E3BE5B]/15 px-3 py-1 text-xs font-semibold text-[#F0D27A]"><x-icon name="clock" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Berjalan</span>
                @endif
            </div>

            <div class="mt-2 flex flex-wrap items-end justify-between gap-3">
                <p class="tabular-nums text-3xl sm:text-4xl font-bold text-[#F0D27A] break-words">
                    {{ $rp($goal->saved_amount) }}
                </p>
                <p class="text-3xl font-bold text-[#F0D27A]">{{ $goal->progress_percent }}%</p>
            </div>

            <div class="mt-4">
                <x-savings-progress :percent="$goal->progress_percent" :achieved="false" :onDark="true" height="h-4" />
            </div>

            <dl class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <dt class="text-xs text-[#B9A56B]">Nominal Target</dt>
                    <dd class="mt-1 text-lg font-semibold text-[#F0D27A]">{{ $rp($goal->target_amount) }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-[#B9A56B]">{{ $done ? 'Kelebihan dari Target' : 'Sisa Dibutuhkan' }}</dt>
                    <dd class="mt-1 text-lg font-semibold text-[#F0D27A]">
                        {{ $done ? $rp($goal->surplus_amount) : $rp($goal->remaining_amount) }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-[#B9A56B]">Deadline</dt>
                    <dd class="mt-1 text-lg font-semibold {{ $goal->is_overdue ? 'text-[#F0829A]' : 'text-[#F0D27A]' }}">
                        @if($goal->deadline)
                            <x-icon name="calendar" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> {{ $goal->deadline->format('d M Y') }}
                            @if($goal->is_overdue) <span class="text-sm">(lewat)</span> @endif
                        @else
                            —
                        @endif
                    </dd>
                </div>
            </dl>

            @if($done)
                <p class="mt-5 rounded-xl bg-[#2FA77F]/15 px-4 py-3 text-sm font-medium text-[#8FE3C3]">
                    <x-icon name="star" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Selamat! Target tabungan ini sudah tercapai.
                </p>
            @endif
        </section>

        {{-- ===== Form progres + riwayat ===== --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Form tambah progres --}}
            <aside class="order-1 lg:order-2 {{ $card }} p-5 sm:p-6 self-start">
                <h2 class="text-lg font-semibold {{ $title }}"><x-icon name="plus" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Catat Tabungan</h2>
                <p class="mb-4 mt-1 text-sm {{ $muted }}">Hanya menambah progres target ini, tidak mengubah saldo utama.</p>

                <form method="POST" action="{{ route('savings-goals.contributions.store', $goal->id) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="amount" class="{{ $label }}"><x-icon name="wallet" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Nominal (Rp) <span class="text-[#8E2740]">*</span></label>
                        <input id="amount" type="number" name="amount" min="1" step="1" inputmode="numeric" required
                               value="{{ old('amount') }}" placeholder="Contoh: 500000" class="{{ $field }}">
                        @if($bag->has('amount')) <p class="{{ $errorText }}">{{ $bag->first('amount') }}</p> @endif
                    </div>

                    <div>
                        <label for="saved_at" class="{{ $label }}"><x-icon name="calendar" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Tanggal <span class="text-[#8E2740]">*</span></label>
                        <input id="saved_at" type="date" name="saved_at" required max="{{ now()->addDay()->toDateString() }}"
                               value="{{ old('saved_at', now()->toDateString()) }}" class="{{ $field }}">
                        @if($bag->has('saved_at')) <p class="{{ $errorText }}">{{ $bag->first('saved_at') }}</p> @endif
                    </div>

                    <div>
                        <label for="note" class="{{ $label }}"><x-icon name="note" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Catatan <span class="font-normal text-[#54421A]">(opsional)</span></label>
                        <input id="note" type="text" name="note" maxlength="255"
                               value="{{ old('note') }}" placeholder="Misalnya: sisa gaji bulan ini" class="{{ $field }}">
                        @if($bag->has('note')) <p class="{{ $errorText }}">{{ $bag->first('note') }}</p> @endif
                    </div>

                    <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold transition {{ $btnPrimary }}">
                        <x-icon name="check" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Simpan Progres
                    </button>
                </form>
            </aside>

            {{-- Riwayat --}}
            <section class="order-2 lg:order-1 lg:col-span-2 overflow-hidden {{ $card }}">
                <div class="border-b border-[#BE9A35] px-5 py-4 sm:px-6">
                    <h2 class="text-lg font-semibold {{ $title }}"><x-icon name="clock" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Riwayat Penambahan Tabungan</h2>
                </div>

                @if($contributions->isEmpty())
                    <div class="px-6 py-14 text-center">
                        <div class="text-4xl" aria-hidden="true"><x-icon name="coin" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /></div>
                        <p class="mt-3 font-medium {{ $title }}">Belum ada catatan progres</p>
                        <p class="mt-1 text-sm {{ $muted }}">Catat tabungan pertamamu lewat form di samping.</p>
                    </div>
                @else
                    <ul class="divide-y divide-[#D6BC6A] md:hidden">
                        @foreach($contributions as $item)
                            <li class="flex items-start justify-between gap-3 px-5 py-4">
                                <div class="min-w-0">
                                    <p class="font-bold tabular-nums text-[#176B50]">+ {{ $rp($item->amount) }}</p>
                                    <p class="mt-0.5 text-xs text-[#54421A]">{{ $item->saved_at->format('d M Y') }}</p>
                                    @if($item->note)
                                        <p class="mt-1 break-words text-sm text-[#43340F]">{{ $item->note }}</p>
                                    @endif
                                </div>

                                <form action="{{ route('savings-goals.contributions.destroy', [$goal->id, $item->id]) }}" method="POST" class="shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Yakin ingin menghapus catatan progres ini? Total progres akan dihitung ulang.')"
                                            class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2.5 text-sm font-semibold transition {{ $btnDanger }}">
                                        <x-icon name="trash" class="h-4 w-4" /> Hapus
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>

                    <div class="hidden overflow-x-auto md:block">
                        <table class="w-full min-w-[520px] text-sm">
                            <thead class="bg-gradient-to-r from-[#14213D] to-[#233763] text-left font-semibold text-[#F0D27A]">
                                <tr>
                                    <th class="px-5 py-3">Tanggal</th>
                                    <th class="px-5 py-3 text-right">Nominal</th>
                                    <th class="px-5 py-3">Catatan</th>
                                    <th class="px-5 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#D6BC6A]">
                                @foreach($contributions as $item)
                                    <tr class="hover:bg-[#E8D17F]/70 transition-colors">
                                        <td class="px-5 py-3.5 whitespace-nowrap text-[#43340F]">
                                            {{ $item->saved_at->format('d M Y') }}
                                        </td>
                                        <td class="px-5 py-3.5 text-right font-semibold whitespace-nowrap tabular-nums text-[#176B50]">
                                            + {{ $rp($item->amount) }}
                                        </td>
                                        <td class="px-5 py-3.5 max-w-xs truncate text-[#43340F]" title="{{ $item->note }}">
                                            {{ $item->note ?: '-' }}
                                        </td>
                                        <td class="px-5 py-3.5 text-center">
                                            <form action="{{ route('savings-goals.contributions.destroy', [$goal->id, $item->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Yakin ingin menghapus catatan progres ini? Total progres akan dihitung ulang.')"
                                                        class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-semibold transition {{ $btnDanger }}">
                                                    <x-icon name="trash" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($contributions->hasPages())
                        <div class="border-t border-[#BE9A35] px-5 py-4">
                            {{ $contributions->links() }}
                        </div>
                    @endif
                @endif
            </section>
        </div>

    </div>
</div>

</x-app-layout>