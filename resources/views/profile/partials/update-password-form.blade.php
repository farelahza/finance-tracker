@php
    $bag = $errors->getBag('updatePassword');

    $label     = 'mb-1.5 block text-sm font-medium text-[#43340F]';
    $errorText = 'mt-1.5 text-sm font-medium text-[#8E2740]';

    $inputBase = 'block w-full rounded-xl border bg-[#FBF1CC] py-3 pl-11 pr-12 text-base sm:text-sm text-[#2A210B] shadow-sm
        placeholder:text-[#7A6630]/70 transition
        focus:border-[#B8892B] focus:outline-none focus:ring-2 focus:ring-[#B8892B]/30';
    $input = fn (string $name) => $inputBase . ' ' . ($bag->has($name) ? 'border-[#8E2740]' : 'border-[#A8841F]');

    $btnPrimary = 'bg-gradient-to-b from-[#EBCB68] to-[#A67C1A] text-[#2A210B]
        border border-[#8A650F]/60 shadow-md shadow-[#8A650F]/30 hover:brightness-110
        focus:outline-none focus:ring-2 focus:ring-[#8A650F]/60 focus:ring-offset-2 focus:ring-offset-[#F4E4A6]';
@endphp

<section>
    <header class="mb-6 flex items-start gap-3">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#14213D] text-[#F0D27A]">
            <x-icon name="lock" class="h-5 w-5" />
        </span>
        <div>
            <h2 class="text-lg font-semibold text-[#2A210B]">Ubah Kata Sandi</h2>
            <p class="mt-0.5 text-sm text-[#54421A]">Gunakan kata sandi yang panjang dan acak agar akunmu tetap aman.</p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5" x-data="{ show: false }">
        @csrf
        @method('put')

        @foreach ([
            ['id' => 'update_password_current_password', 'name' => 'current_password', 'label' => 'Kata Sandi Saat Ini', 'auto' => 'current-password'],
            ['id' => 'update_password_password', 'name' => 'password', 'label' => 'Kata Sandi Baru', 'auto' => 'new-password'],
            ['id' => 'update_password_password_confirmation', 'name' => 'password_confirmation', 'label' => 'Konfirmasi Kata Sandi Baru', 'auto' => 'new-password'],
        ] as $f)
            <div>
                <label for="{{ $f['id'] }}" class="{{ $label }}">{{ $f['label'] }}</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#7A6630]">
                        <x-icon name="lock" class="h-5 w-5" />
                    </span>
                    <input id="{{ $f['id'] }}" name="{{ $f['name'] }}" :type="show ? 'text' : 'password'" type="password"
                           autocomplete="{{ $f['auto'] }}" class="{{ $input($f['name']) }}">
                    <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#7A6630] hover:text-[#2A210B] focus:outline-none"
                            :aria-label="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                        <span x-show="!show"><x-icon name="eye" class="h-5 w-5" /></span>
                        <span x-show="show" style="display:none"><x-icon name="eye-off" class="h-5 w-5" /></span>
                    </button>
                </div>
                @if($bag->has($f['name'])) <p class="{{ $errorText }}">{{ $bag->first($f['name']) }}</p> @endif
            </div>
        @endforeach

        <div class="flex flex-wrap items-center gap-4">
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold transition {{ $btnPrimary }}">
                <x-icon name="check" class="h-4 w-4" />
                Ubah Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 3000)"
                   class="inline-flex items-center gap-1.5 text-sm font-medium text-[#176B50]">
                    <x-icon name="check-circle" class="h-4 w-4" /> Tersimpan.
                </p>
            @endif
        </div>
    </form>
</section>