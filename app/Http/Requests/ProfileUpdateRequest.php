<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            // Foto profil: opsional, maksimal 2 MB, tipe dicek dari isi file
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],

            // Kata sandi saat ini: WAJIB hanya ketika email diubah (mencegah pengambilalihan akun
            // dari sesi login yang terbuka). Mengubah nama/foto tidak memerlukannya.
            'current_password' => [
                Rule::requiredIf(fn () => $this->isChangingEmail()),
                'nullable',
                'string',
                'current_password',
            ],
        ];
    }

    /** True bila email pada form berbeda dari email akun saat ini. */
    private function isChangingEmail(): bool
    {
        return (string) $this->input('email') !== (string) $this->user()->email;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'    => 'Nama wajib diisi.',
            'name.max'         => 'Nama maksimal 255 karakter.',
            'email.required'   => 'Email wajib diisi.',
            'email.email'      => 'Format email tidak valid.',
            'email.lowercase'  => 'Email harus ditulis dengan huruf kecil.',
            'email.unique'     => 'Email ini sudah dipakai akun lain.',
            'photo.image'      => 'File harus berupa gambar.',
            'photo.mimes'      => 'Format foto harus JPG, PNG, atau WebP.',
            'photo.max'        => 'Ukuran foto maksimal 2 MB.',
            'photo.uploaded'   => 'Foto gagal diunggah. Pastikan ukurannya tidak lebih dari 2 MB.',
            'current_password.required'         => 'Masukkan kata sandi saat ini untuk mengubah email.',
            'current_password.current_password' => 'Kata sandi saat ini salah.',
        ];
    }
}