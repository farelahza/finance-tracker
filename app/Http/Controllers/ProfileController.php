<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Hanya nama & email yang boleh diisi massal; foto ditangani terpisah di bawah.
        $user->fill($request->safe()->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $oldPhotoPath = $user->profile_photo_path;

        if ($request->hasFile('photo')) {
            // Nama file diacak otomatis oleh Laravel, disimpan di storage/app/public/profile-photos
            $user->profile_photo_path = $request->file('photo')->store('profile-photos', 'public');
        } elseif ($request->boolean('remove_photo')) {
            $user->profile_photo_path = null;
        }

        $user->save();

        // Foto lama baru dihapus setelah data berhasil disimpan
        if ($oldPhotoPath && $oldPhotoPath !== $user->profile_photo_path) {
            Storage::disk('public')->delete($oldPhotoPath);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $photoPath = $user->profile_photo_path;

        Auth::logout();

        $user->delete();

        if ($photoPath) {
            Storage::disk('public')->delete($photoPath);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}