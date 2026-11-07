@php
    // Form bersama untuk Tambah & Edit transaksi.
    // Variabel: $categories, $action, $method ('POST' | 'PUT'), $submitLabel, $cancelUrl, $transaction (null saat tambah)
    $transaction = $transaction ?? null;

    $label = 'mb-1.5 block text-sm font-medium text-[#43340F]';
    $errorText = 'mt-1.5 text-sm font-medium text-[#8E2740]';

    $fieldBase = 'w-full rounded-xl border bg-[#FBF1CC] py-3 text-sm text-[#2A210B] shadow-sm
        placeholder:text-[#7A6630]/70 transition
        focus:border-[#B8892B] focus:outline-none focus:ring-2 focus:ring-[#B8892B]/30';
    $field = fn (string $name) => $fieldBase . ' ' . ($errors->has($name) ? 'border-[#8E2740]' : 'border-[#A8841F]');

    $btnPrimary = 'bg-gradient-to-b from-[#EBCB68] to-[#A67C1A] text-[#2A210B]
        border border-[#8A650F]/60 shadow-md shadow-[#8A650F]/30 hover:brightness-110
        focus:outline-none focus:ring-2 focus:ring-[#8A650F]/60 focus:ring-offset-2 focus:ring-offset-[#F4E4A6]';
    $btnGhost = 'border border-[#A8841F] bg-[#F4E4A6] text-[#2A210B] hover:bg-[#E8D17F]
        focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40';

    // Nilai awal: input lama (saat validasi gagal) > data transaksi (edit) > default
    $typeValue     = old('type', $transaction->type ?? 'income');
    $categoryValue = old('category_id', $transaction->category_id ?? '');
    $amountValue   = old('amount', $transaction ? (int) $transaction->amount : '');
    $descValue     = old('description', $transaction->description ?? '');
    $dateValue     = old('transaction_date', $transaction
        ? \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d')
        : '');
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6"
      x-data="{
          setToday() {
              const d = new Date();
              const p = (n) => String(n).padStart(2, '0');
              this.$refs.date.value = d.getFullYear() + '-' + p(d.getMonth() + 1) + '-' + p(d.getDate());
          }
      }">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    {{-- Jenis transaksi --}}
    <div role="radiogroup" aria-labelledby="type-label">
        <p id="type-label" class="{{ $label }}">Jenis Transaksi</p>

        <div class="grid grid-cols-2 gap-3">
            <label class="cursor-pointer">
                <input type="radio" name="type" value="income" class="peer sr-only" @checked($typeValue === 'income')>
                <span class="flex items-center justify-center gap-2 rounded-xl border border-[#A8841F] bg-[#FBF1CC] px-4 py-3 text-sm font-semibold text-[#43340F] transition
                             hover:bg-[#F8EBB8]
                             peer-checked:border-[#176B50] peer-checked:bg-[#176B50] peer-checked:text-white
                             peer-focus-visible:ring-2 peer-focus-visible:ring-[#B8892B]/60">
                    <x-icon name="arrow-up" class="h-4 w-4" />
                    Pemasukan
                </span>
            </label>

            <label class="cursor-pointer">
                <input type="radio" name="type" value="expense" class="peer sr-only" @checked($typeValue === 'expense')>
                <span class="flex items-center justify-center gap-2 rounded-xl border border-[#A8841F] bg-[#FBF1CC] px-4 py-3 text-sm font-semibold text-[#43340F] transition
                             hover:bg-[#F8EBB8]
                             peer-checked:border-[#8E2740] peer-checked:bg-[#8E2740] peer-checked:text-white
                             peer-focus-visible:ring-2 peer-focus-visible:ring-[#B8892B]/60">
                    <x-icon name="arrow-down" class="h-4 w-4" />
                    Pengeluaran
                </span>
            </label>
        </div>
        @error('type') <p class="{{ $errorText }}">{{ $message }}</p> @enderror
    </div>

    {{-- Kategori --}}
    <div>
        <label for="category_id" class="{{ $label }}">
            <x-icon name="tag" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Kategori
        </label>
        <select id="category_id" name="category_id" required class="{{ $field('category_id') }} px-3.5">
            <option value="" disabled @selected($categoryValue === '' || $categoryValue === null)>Pilih kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((string) $categoryValue === (string) $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id') <p class="{{ $errorText }}">{{ $message }}</p> @enderror
    </div>

    {{-- Nominal --}}
    <div>
        <label for="amount" class="{{ $label }}">
            <x-icon name="wallet" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Nominal
        </label>
        <div class="relative">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm font-semibold text-[#7A6630]">Rp</span>
            <input id="amount" type="number" name="amount" min="0" step="1" inputmode="numeric" required
                   value="{{ $amountValue }}" placeholder="500000"
                   class="{{ $field('amount') }} pl-11 pr-3.5 tabular-nums">
        </div>
        <p class="mt-1.5 text-xs text-[#54421A]">Tulis angka saja, tanpa titik atau koma.</p>
        @error('amount') <p class="{{ $errorText }}">{{ $message }}</p> @enderror
    </div>

    {{-- Deskripsi --}}
    <div>
        <label for="description" class="{{ $label }}">
            <x-icon name="note" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Deskripsi
            <span class="font-normal text-[#54421A]">(opsional)</span>
        </label>
        <textarea id="description" name="description" rows="3" placeholder="Contoh: Gaji bulan Juli"
                  class="{{ $field('description') }} px-3.5">{{ $descValue }}</textarea>
        @error('description') <p class="{{ $errorText }}">{{ $message }}</p> @enderror
    </div>

    {{-- Tanggal --}}
    <div>
        <div class="flex items-center justify-between">
            <label for="transaction_date" class="{{ $label }}">
                <x-icon name="calendar" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Tanggal
            </label>
            <button type="button" @click="setToday()"
                    class="mb-1.5 rounded text-xs font-semibold text-[#14213D] underline underline-offset-2 hover:text-[#233763]
                           focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40">
                Hari ini
            </button>
        </div>
        <input id="transaction_date" type="date" name="transaction_date" x-ref="date" required
               value="{{ $dateValue }}" class="{{ $field('transaction_date') }} px-3.5">
        @error('transaction_date') <p class="{{ $errorText }}">{{ $message }}</p> @enderror
    </div>

    {{-- Tombol --}}
    <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
        <a href="{{ $cancelUrl }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold transition {{ $btnGhost }}">
            <x-icon name="arrow-left" class="h-4 w-4" />
            Kembali
        </a>

        <button type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold transition {{ $btnPrimary }}">
            <x-icon name="check" class="h-4 w-4" />
            {{ $submitLabel }}
        </button>
    </div>
</form>