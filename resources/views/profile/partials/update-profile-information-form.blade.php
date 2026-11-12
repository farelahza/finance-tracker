@php
    $label     = 'mb-1.5 block text-sm font-medium text-[#43340F]';
    $errorText = 'mt-1.5 text-sm font-medium text-[#8E2740]';

    $inputBase = 'block w-full rounded-xl border bg-[#FBF1CC] py-3 pl-11 pr-4 text-base sm:text-sm text-[#2A210B] shadow-sm
        placeholder:text-[#7A6630]/70 transition
        focus:border-[#B8892B] focus:outline-none focus:ring-2 focus:ring-[#B8892B]/30';
    $input = fn (string $name) => $inputBase . ' ' . ($errors->has($name) ? 'border-[#8E2740]' : 'border-[#A8841F]');

    $btnPrimary = 'bg-gradient-to-b from-[#EBCB68] to-[#A67C1A] text-[#2A210B]
        border border-[#8A650F]/60 shadow-md shadow-[#8A650F]/30 hover:brightness-110
        focus:outline-none focus:ring-2 focus:ring-[#8A650F]/60 focus:ring-offset-2 focus:ring-offset-[#F4E4A6]';
    $btnGhost = 'border border-[#A8841F] bg-[#F4E4A6] text-[#2A210B] hover:bg-[#E8D17F]
        focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40';

    $photoUrl = $user->profile_photo_url;
    $initial  = strtoupper(mb_substr($user->name, 0, 1));
@endphp

<section>
    <header class="mb-6 flex items-start gap-3">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#14213D] text-[#F0D27A]">
            <x-icon name="user" class="h-5 w-5" />
        </span>
        <div>
            <h2 class="text-lg font-semibold text-[#2A210B]">Informasi Profil</h2>
            <p class="mt-0.5 text-sm text-[#54421A]">Perbarui foto, nama, dan alamat email akunmu.</p>
        </div>
    </header>

    {{-- form kirim ulang verifikasi (dipakai tombol di bawah lewat atribut form=) --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6"
          x-data="{
              preview: null,
              removed: false,
              currentUrl: @js($photoUrl),
              email: @js(old('email', $user->email)),
              originalEmail: @js($user->email),
              error: '',
              pick(e) {
                  const file = e.target.files[0];
                  if (!file) return;
                  if (file.size > 2 * 1024 * 1024) {
                      this.error = 'Ukuran foto maksimal 2 MB.';
                      e.target.value = '';
                      return;
                  }
                  this.error = '';
                  this.preview = URL.createObjectURL(file);
                  this.removed = false;
              },
              clear() {
                  this.preview = null;
                  this.removed = true;
                  this.error = '';
                  this.$refs.photo.value = '';
              },
              get showImage() {
                  return this.preview !== null || (this.currentUrl !== null && !this.removed);
              },
              get emailChanged() {
                  return this.email.trim() !== this.originalEmail;
              }
          }">
        @csrf
        @method('patch')

        {{-- Foto profil --}}
        <div>
            <p class="{{ $label }}">Foto Profil</p>

            <div class="flex flex-col items-start gap-5 sm:flex-row sm:items-center">
                <div class="relative h-24 w-24 shrink-0">
                    <img x-show="showImage" :src="preview ?? currentUrl" alt="Foto profil"
                         class="h-24 w-24 rounded-full object-cover shadow-lg ring-4 ring-[#E3BE5B]/70"
                         @if(! $photoUrl) style="display:none" @endif>

                    <span x-show="!showImage"
                          class="flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-b from-[#EBCB68] to-[#A67C1A] text-3xl font-bold text-[#2A210B] shadow-lg ring-4 ring-[#E3BE5B]/70"
                          @if($photoUrl) style="display:none" @endif>{{ $initial }}</span>
                </div>

                <div class="min-w-0">
                    <input x-ref="photo" id="photo" type="file" name="photo"
                           accept="image/jpeg,image/png,image/webp" class="sr-only" @change="pick($event)">
                    <input type="hidden" name="remove_photo" :value="removed ? 1 : 0" value="0">

                    <div class="flex flex-wrap gap-3">
                        <button type="button" @click="$refs.photo.click()"
                                class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ $btnGhost }}">
                            <x-icon name="camera" class="h-4 w-4" />
                            <span x-text="showImage ? 'Ganti Foto' : 'Pilih Foto'">{{ $photoUrl ? 'Ganti Foto' : 'Pilih Foto' }}</span>
                        </button>

                        <button type="button" @click="clear()" x-show="showImage"
                                class="inline-flex items-center gap-2 rounded-xl border border-[#8E2740]/40 bg-[#8E2740]/10 px-4 py-2.5 text-sm font-semibold text-[#8E2740] transition hover:bg-[#8E2740]/20
                                       focus:outline-none focus:ring-2 focus:ring-[#8E2740]/40"
                                @if(! $photoUrl) style="display:none" @endif>
                            <x-icon name="trash" class="h-4 w-4" />
                            Hapus Foto
                        </button>
                    </div>

                    <p class="mt-2 text-xs text-[#54421A]">JPG, PNG, atau WebP. Maksimal 2 MB.</p>
                    <p x-show="error" x-text="error" class="{{ $errorText }}" style="display:none"></p>
                    @error('photo') <p class="{{ $errorText }}">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Nama --}}
        <div>
            <label for="name" class="{{ $label }}">Nama</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#7A6630]">
                    <x-icon name="user" class="h-5 w-5" />
                </span>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                       required autocomplete="name" class="{{ $input('name') }}">
            </div>
            @error('name') <p class="{{ $errorText }}">{{ $message }}</p> @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="{{ $label }}">Email</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#7A6630]">
                    <x-icon name="mail" class="h-5 w-5" />
                </span>
                <input id="email" name="email" type="email" x-model="email" value="{{ old('email', $user->email) }}"
                       required autocomplete="username" class="{{ $input('email') }}">
            </div>
            @error('email') <p class="{{ $errorText }}">{{ $message }}</p> @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-xl border border-[#8A650F]/30 bg-[#8A650F]/10 px-4 py-3 text-sm text-[#43340F]">
                    Alamat email kamu belum diverifikasi.
                    <button form="send-verification"
                            class="rounded font-semibold underline underline-offset-2 hover:text-[#14213D] focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40">
                        Kirim ulang email verifikasi.
                    </button>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm font-medium text-[#176B50]">
                        Tautan verifikasi baru telah dikirim ke emailmu.
                    </p>
                @endif
            @endif
        </div>

        {{-- Kata sandi saat ini: hanya muncul (dan hanya dikirim) bila email diubah --}}
        <div x-show="emailChanged" x-transition
             @if(old('email', $user->email) === $user->email) style="display:none" @endif>
            <label for="email_confirm_password" class="{{ $label }}">Kata Sandi Saat Ini</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#7A6630]">
                    <x-icon name="lock" class="h-5 w-5" />
                </span>
                <input id="email_confirm_password" name="current_password" type="password"
                       autocomplete="current-password" :disabled="!emailChanged"
                       placeholder="Masukkan kata sandi untuk konfirmasi"
                       class="{{ $input('current_password') }}">
            </div>
            <p class="mt-1.5 text-xs text-[#54421A]">Diperlukan saat mengubah email, demi keamanan akunmu.</p>
            @error('current_password') <p class="{{ $errorText }}">{{ $message }}</p> @enderror
        </div>

        {{-- Tombol --}}
        <div class="flex flex-wrap items-center gap-4">
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold transition {{ $btnPrimary }}">
                <x-icon name="check" class="h-4 w-4" />
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 3000)"
                   class="inline-flex items-center gap-1.5 text-sm font-medium text-[#176B50]">
                    <x-icon name="check-circle" class="h-4 w-4" /> Tersimpan.
                </p>
            @endif
        </div>
    </form>
</section>