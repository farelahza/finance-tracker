@php
    $bag = $errors->getBag('userDeletion');
@endphp

<section class="space-y-5">
    <header class="flex items-start gap-3">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#8E2740]/15 text-[#8E2740]">
            <x-icon name="alert" class="h-5 w-5" />
        </span>
        <div>
            <h2 class="text-lg font-semibold text-[#8E2740]">Hapus Akun</h2>
            <p class="mt-0.5 text-sm text-[#54421A]">
                Setelah akun dihapus, seluruh data dan sumber dayanya akan dihapus permanen. Unduh dulu data yang ingin kamu simpan sebelum melanjutkan.
            </p>
        </div>
    </header>

    <button type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#8E2740] px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-[#7A1F36]
                   focus:outline-none focus:ring-2 focus:ring-[#8E2740]/50 focus:ring-offset-2 focus:ring-offset-[#F4E4A6]">
        <x-icon name="trash" class="h-4 w-4" />
        Hapus Akun
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" max-width="md" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="flex items-start gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#8E2740]/15 text-[#8E2740]">
                    <x-icon name="alert" class="h-5 w-5" />
                </span>
                <div>
                    <h2 class="text-lg font-semibold text-[#2A210B]">Yakin ingin menghapus akun?</h2>
                    <p class="mt-1 text-sm text-[#54421A]">
                        Seluruh data akunmu akan dihapus permanen dan tidak bisa dikembalikan.
                        Masukkan kata sandi untuk mengonfirmasi.
                    </p>
                </div>
            </div>

            <div class="mt-6">
                <label for="password" class="sr-only">Kata Sandi</label>
                <input id="password" name="password" type="password" placeholder="Kata sandi"
                       class="block w-full rounded-xl border bg-[#FBF1CC] px-4 py-3 text-base sm:text-sm text-[#2A210B] shadow-sm placeholder:text-[#7A6630]/70
                              focus:border-[#B8892B] focus:outline-none focus:ring-2 focus:ring-[#B8892B]/30
                              {{ $bag->has('password') ? 'border-[#8E2740]' : 'border-[#A8841F]' }}">
                @if($bag->has('password'))
                    <p class="mt-1.5 text-sm font-medium text-[#8E2740]">{{ $bag->first('password') }}</p>
                @endif
            </div>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" x-on:click="$dispatch('close')"
                        class="inline-flex items-center justify-center rounded-xl border border-[#A8841F] bg-[#F4E4A6] px-5 py-2.5 text-sm font-semibold text-[#2A210B] transition hover:bg-[#E8D17F]
                               focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40">
                    Batal
                </button>

                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#8E2740] px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-[#7A1F36]
                               focus:outline-none focus:ring-2 focus:ring-[#8E2740]/50 focus:ring-offset-2 focus:ring-offset-[#F4E4A6]">
                    <x-icon name="trash" class="h-4 w-4" />
                    Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>