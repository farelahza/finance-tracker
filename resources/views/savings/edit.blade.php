<x-app-layout>

@php
    $card  = 'rounded-2xl border border-[#BE9A35] bg-[#F4E4A6] shadow-sm';
    $title = 'text-[#2A210B]';
    $muted = 'text-[#54421A]';
@endphp

<div class="py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div>
            <h1 class="font-['Playfair_Display',serif] text-3xl sm:text-4xl font-semibold tracking-tight {{ $title }}">
                ✏️ Edit Target Tabungan
            </h1>
            <p class="mt-1 text-sm {{ $muted }}">Mengubah target tidak menghapus riwayat progres yang sudah tercatat.</p>
        </div>

        <div class="{{ $card }} p-5 sm:p-6">
            @include('savings._form', [
                'goal'        => $goal,
                'action'      => route('savings-goals.update', $goal->id),
                'method'      => 'PUT',
                'submitLabel' => '💾 Simpan Perubahan',
                'cancelUrl'   => route('savings-goals.show', $goal->id),
            ])
        </div>

    </div>
</div>

</x-app-layout>