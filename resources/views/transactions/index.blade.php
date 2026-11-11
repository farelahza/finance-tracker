<x-app-layout>

@php
    // ===== Token warna (Midnight Navy + Champagne Gold) =====
    $card  = 'rounded-2xl border border-[#BE9A35] bg-[#F4E4A6] shadow-sm';
    $title = 'text-[#2A210B]';
    $muted = 'text-[#54421A]';
    $label = 'mb-1.5 block text-sm font-medium text-[#43340F]';

    $field = 'w-full rounded-xl border border-[#A8841F]
        bg-[#FBF1CC] text-[#2A210B]
        text-base sm:text-sm px-3 py-2.5 shadow-sm
        focus:border-[#B8892B] focus:ring-2 focus:ring-[#B8892B]/30 focus:outline-none transition';

    $btnPrimary = 'bg-gradient-to-b from-[#EBCB68] to-[#A67C1A] text-[#2A210B]
        border border-[#8A650F]/60 shadow-md shadow-[#8A650F]/30 hover:brightness-110
        focus:outline-none focus:ring-2 focus:ring-[#8A650F]/60 focus:ring-offset-2
        focus:ring-offset-[#F4E4A6]';

    $btnGhost = 'border border-[#A8841F] bg-[#F4E4A6]
        text-[#2A210B]
        hover:bg-[#E8D17F]
        focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40';


    $btnPdf = 'border border-[#8E2740]/40 bg-[#8E2740]/10
        text-[#8E2740] hover:bg-[#8E2740]/20
        focus:outline-none focus:ring-2 focus:ring-[#8E2740]/40';

    $btnEdit = 'border border-[#B8892B]/50 bg-[#B8892B]/10
        text-[#6B4C0C] hover:bg-[#B8892B]/20
        focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40';

    $filterAktif = request()->filled('transaction_date')
        || request()->filled('category_id')
        || request()->filled('type')
        || request()->filled('amount_filter');
@endphp

<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- ===== HEADER ===== --}}
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <h1 class="font-['Playfair_Display',serif] text-3xl sm:text-4xl font-semibold tracking-tight {{ $title }}">
                    Daftar Transaksi
                </h1>
                <p class="mt-1 text-sm {{ $muted }}">
                    Kelola semua pemasukan dan pengeluaran kamu
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">

                <a href="{{ route('transactions.create') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold shadow-sm transition {{ $btnPrimary }}">
                    <x-icon name="plus" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Tambah Transaksi
                </a>

            </div>
        </div>

        {{-- ===== SUCCESS ===== --}}
        @if(session('success'))
            <div role="status"
                 class="flex items-center gap-3 rounded-xl border border-[#176B50]/30 bg-[#176B50]/10
                        px-4 py-3 text-sm font-medium text-[#176B50]">
                <span aria-hidden="true"><x-icon name="check-circle" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- ===== FILTER ===== --}}
        <form method="GET" action="{{ route('transactions.index') }}" class="{{ $card }} p-4 sm:p-5">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-6 lg:items-end">

                <div>
                    <label for="transaction_date" class="{{ $label }}"><x-icon name="calendar" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Tanggal</label>
                    <input id="transaction_date" type="date" name="transaction_date"
                           value="{{ request('transaction_date') }}"
                           class="{{ $field }}">
                </div>

                <div>
                    <label for="category_id" class="{{ $label }}"><x-icon name="tag" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Kategori</label>
                    <select id="category_id" name="category_id" class="{{ $field }}">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="type" class="{{ $label }}"><x-icon name="repeat" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Tipe</label>
                    <select id="type" name="type" class="{{ $field }}">
                        <option value="">Semua Tipe</option>
                        <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>

                <div>
                    <label for="amount_filter" class="{{ $label }}"><x-icon name="wallet" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Jumlah</label>
                    <select id="amount_filter" name="amount_filter" class="{{ $field }}">
                        <option value="">Semua Jumlah</option>
                        <option value="under_100" {{ request('amount_filter') == 'under_100' ? 'selected' : '' }}>
                            Di bawah Rp100.000
                        </option>
                        <option value="100_500" {{ request('amount_filter') == '100_500' ? 'selected' : '' }}>
                            Rp100.000 - Rp500.000
                        </option>
                        <option value="500_1000" {{ request('amount_filter') == '500_1000' ? 'selected' : '' }}>
                            Rp500.000 - Rp1.000.000
                        </option>
                        <option value="above_1000" {{ request('amount_filter') == 'above_1000' ? 'selected' : '' }}>
                            Di atas Rp1.000.000
                        </option>
                    </select>
                </div>

                <div class="flex gap-3 sm:col-span-2 lg:col-span-2">
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold shadow-sm transition {{ $btnPrimary }}">
                        <x-icon name="search" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Filter
                    </button>

                    @if($filterAktif)
                        <a href="{{ route('transactions.index') }}"
                           class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ $btnGhost }}">
                            Reset
                        </a>
                    @endif
                </div>

            </div>
        </form>

        {{-- ===== LIST (mobile) ===== --}}
        <div class="space-y-3 md:hidden">
            @forelse($transactions as $transaction)
                <article class="{{ $card }} p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-semibold {{ $title }} break-words">{{ $transaction->category->name }}</p>
                            <p class="mt-0.5 text-xs {{ $muted }}">
                                {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}
                            </p>
                        </div>
                        <p class="shrink-0 text-right font-bold tabular-nums
                                  {{ $transaction->type == 'income' ? 'text-[#176B50]' : 'text-[#8E2740]' }}">
                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="mt-2.5">
                        @if($transaction->type == 'income')
                            <span class="inline-flex items-center gap-1 rounded-full bg-[#176B50]/10 px-3 py-1 text-xs font-semibold text-[#176B50]">
                                <x-icon name="arrow-up" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Pemasukan
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-[#8E2740]/10 px-3 py-1 text-xs font-semibold text-[#8E2740]">
                                <x-icon name="arrow-down" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Pengeluaran
                            </span>
                        @endif
                    </div>

                    @if($transaction->description)
                        <p class="mt-2 text-sm text-[#43340F] break-words">{{ $transaction->description }}</p>
                    @endif

                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <a href="{{ route('transactions.edit', $transaction->id) }}"
                           class="inline-flex items-center justify-center gap-1.5 rounded-lg px-3 py-2.5 text-sm font-semibold transition {{ $btnEdit }}">
                            <x-icon name="pencil" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Edit
                        </a>

                        <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    onclick="return confirm('Yakin ingin menghapus transaksi ini?')"
                                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg px-3 py-2.5 text-sm font-semibold transition {{ $btnPdf }}">
                                <x-icon name="trash" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Hapus
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="{{ $card }} px-5 py-12 text-center">
                    <div class="text-4xl text-[#54421A]" aria-hidden="true"><x-icon name="folder" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /></div>
                    <p class="mt-3 font-medium {{ $title }}">Belum ada transaksi</p>
                    <p class="mt-1 text-sm {{ $muted }}">Klik "Tambah Transaksi" untuk mencatat yang pertama.</p>
                </div>
            @endforelse
        </div>

        {{-- ===== TABLE (tablet & desktop) ===== --}}
        <div class="hidden md:block overflow-hidden {{ $card }}">

            <div class="overflow-x-auto">
                <table class="w-full min-w-[820px] text-sm">

                    <thead class="bg-gradient-to-r from-[#14213D] to-[#233763] border-b border-[#E3BE5B]/40">
                        <tr class="text-left font-semibold text-[#F0D27A]">
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Kategori</th>
                            <th class="px-5 py-3.5">Tipe</th>
                            <th class="px-5 py-3.5 text-right">Jumlah</th>
                            <th class="px-5 py-3.5">Deskripsi</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#D6BC6A]">

                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-[#E8D17F]/70 transition-colors">

                                <td class="px-5 py-4 whitespace-nowrap text-[#43340F]">
                                    {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}
                                </td>

                                <td class="px-5 py-4 font-medium {{ $title }}">
                                    {{ $transaction->category->name }}
                                </td>

                                <td class="px-5 py-4">
                                    @if($transaction->type == 'income')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-[#176B50]/10
                                                     px-3 py-1 text-xs font-semibold text-[#176B50]">
                                            <x-icon name="arrow-up" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Pemasukan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-[#8E2740]/10
                                                     px-3 py-1 text-xs font-semibold text-[#8E2740]">
                                            <x-icon name="arrow-down" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Pengeluaran
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-right font-semibold whitespace-nowrap tabular-nums
                                           {{ $transaction->type == 'income'
                                                ? 'text-[#176B50]'
                                                : 'text-[#8E2740]' }}">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>

                                <td class="px-5 py-4 max-w-xs truncate text-[#43340F]"
                                    title="{{ $transaction->description }}">
                                    {{ $transaction->description ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">

                                        <a href="{{ route('transactions.edit', $transaction->id) }}"
                                           class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-semibold transition {{ $btnEdit }}">
                                            <x-icon name="pencil" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Edit
                                        </a>

                                        <form action="{{ route('transactions.destroy', $transaction->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                onclick="return confirm('Yakin ingin menghapus transaksi ini?')"
                                                class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-semibold transition {{ $btnPdf }}">
                                                <x-icon name="trash" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-14 text-center">
                                    <div class="text-4xl" aria-hidden="true"><x-icon name="folder" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /></div>
                                    <p class="mt-3 font-medium {{ $title }}">Belum ada transaksi</p>
                                    <p class="mt-1 text-sm {{ $muted }}">
                                        Klik "Tambah Transaksi" untuk mencatat yang pertama.
                                    </p>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== PAGINATION ===== --}}
        <div>
            {{ $transactions->links() }}
        </div>

    </div>
</div>

</x-app-layout>