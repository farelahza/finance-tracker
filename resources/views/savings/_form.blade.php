@php
    $label = 'mb-1.5 block text-sm font-medium text-[#43340F]';
    $field = 'w-full rounded-xl border border-[#A8841F] bg-[#FBF1CC] text-[#2A210B] text-sm px-3 py-2.5 shadow-sm
        placeholder:text-[#7A6630] focus:border-[#B8892B] focus:ring-2 focus:ring-[#B8892B]/30 focus:outline-none transition';
    $btnPrimary = 'bg-gradient-to-b from-[#EBCB68] to-[#A67C1A] text-[#2A210B]
        border border-[#8A650F]/60 shadow-md shadow-[#8A650F]/30 hover:brightness-110
        focus:outline-none focus:ring-2 focus:ring-[#8A650F]/60 focus:ring-offset-2 focus:ring-offset-[#F4E4A6]';
    $btnGhost = 'border border-[#A8841F] bg-[#F4E4A6] text-[#2A210B] hover:bg-[#E8D17F]
        focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40';
    $errorText = 'mt-1.5 text-sm font-medium text-[#8E2740]';
@endphp

<form method="POST" action="{{ $action }}" class="space-y-5">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div>
        <label for="name" class="{{ $label }}">🎯 Nama Target <span class="text-[#8E2740]">*</span></label>
        <input id="name" type="text" name="name" maxlength="100" required
               value="{{ old('name', $goal->name ?? '') }}"
               placeholder="Contoh: Laptop Baru, Motor, Liburan"
               class="{{ $field }}">
        @error('name') <p class="{{ $errorText }}">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="target_amount" class="{{ $label }}">💰 Nominal Target (Rp) <span class="text-[#8E2740]">*</span></label>
        <input id="target_amount" type="number" name="target_amount" min="1" step="1" inputmode="numeric" required
               value="{{ old('target_amount', $goal->target_amount ?? '') }}"
               placeholder="Contoh: 15000000"
               class="{{ $field }}">
        <p class="mt-1.5 text-xs text-[#54421A]">Tulis angka saja, tanpa titik atau koma.</p>
        @error('target_amount') <p class="{{ $errorText }}">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="description" class="{{ $label }}">📝 Deskripsi <span class="text-[#54421A] font-normal">(opsional)</span></label>
        <textarea id="description" name="description" rows="3" maxlength="1000"
                  placeholder="Catatan singkat tentang target ini"
                  class="{{ $field }}">{{ old('description', $goal->description ?? '') }}</textarea>
        @error('description') <p class="{{ $errorText }}">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="deadline" class="{{ $label }}">📅 Deadline <span class="text-[#54421A] font-normal">(opsional)</span></label>
        <input id="deadline" type="date" name="deadline"
               value="{{ old('deadline', optional($goal->deadline ?? null)->format('Y-m-d')) }}"
               class="{{ $field }}">
        @error('deadline') <p class="{{ $errorText }}">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
        <a href="{{ $cancelUrl }}"
           class="inline-flex items-center justify-center rounded-xl px-5 py-2.5 text-sm font-semibold transition {{ $btnGhost }}">
            Batal
        </a>
        <button type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold transition {{ $btnPrimary }}">
            {{ $submitLabel }}
        </button>
    </div>
</form>