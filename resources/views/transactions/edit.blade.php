<x-app-layout>

@php
    $card  = 'rounded-2xl border border-[#BE9A35] bg-[#F4E4A6] shadow-sm';
    $title = 'text-[#2A210B]';
    $muted = 'text-[#54421A]';
@endphp

<div class="py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div>
            <a href="{{ route('transactions.index') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-[#A8841F] bg-[#F4E4A6] px-4 py-2 text-sm font-semibold text-[#2A210B] shadow-sm transition
                      hover:bg-[#E8D17F] focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40">
                <x-icon name="arrow-left" class="h-4 w-4" /> Semua Transaksi
            </a>
            <h1 class="mt-4 font-['Playfair_Display',serif] text-3xl sm:text-4xl font-semibold tracking-tight {{ $title }}">
                Edit Transaksi
            </h1>
            <p class="mt-1 text-sm {{ $muted }}">Perbarui data transaksi ini.</p>
        </div>

        <div class="{{ $card }} p-5 sm:p-7">
            @include('transactions._form', [
                'transaction' => $transaction,
                'categories'  => $categories,
                'action'      => route('transactions.update', $transaction->id),
                'method'      => 'PUT',
                'submitLabel' => 'Simpan Perubahan',
                'cancelUrl'   => route('transactions.index'),
            ])
        </div>

    </div>
</div>

</x-app-layout>