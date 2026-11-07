<?php

namespace App\Http\Controllers;

use App\Models\SavingsGoal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SavingsContributionController extends Controller
{
    private const MESSAGES = [
        'amount.required'          => 'Nominal tabungan wajib diisi.',
        'amount.integer'           => 'Nominal harus berupa angka bulat (tanpa titik, koma, atau huruf).',
        'amount.min'               => 'Nominal harus lebih besar dari 0.',
        'amount.max'               => 'Nominal terlalu besar.',
        'saved_at.required'        => 'Tanggal pencatatan wajib diisi.',
        'saved_at.date'            => 'Format tanggal tidak valid.',
        'saved_at.before_or_equal' => 'Tanggal pencatatan tidak boleh lebih dari besok.',
        'note.max'                 => 'Catatan maksimal 255 karakter.',
    ];

    private function findOwnedGoal(Request $request, int|string $id): SavingsGoal
    {
        return SavingsGoal::ownedBy($request->user()->id)->findOrFail($id);
    }

    public function store(Request $request, int|string $goal): RedirectResponse
    {
        $goal = $this->findOwnedGoal($request, $goal);

        $validated = $request->validateWithBag('contribution', [
            'amount'   => ['required', 'integer', 'min:1', 'max:999999999999'],
            'saved_at' => ['required', 'date', 'before_or_equal:tomorrow'],
            'note'     => ['nullable', 'string', 'max:255'],
        ], self::MESSAGES);

        $before = $goal->saved_amount;

        // savings_goal_id diisi otomatis oleh relasi; transaksi/saldo utama tidak disentuh
        $goal->contributions()->create($validated);

        $reachedNow = $before < $goal->target_amount
            && ($before + $validated['amount']) >= $goal->target_amount;

        return redirect()
            ->route('savings-goals.show', $goal->id)
            ->with('success', $reachedNow
                ? 'Progres tersimpan. Selamat, target tabunganmu tercapai!'
                : 'Progres tabungan berhasil ditambahkan.');
    }

    public function destroy(Request $request, int|string $goal, int|string $contribution): RedirectResponse
    {
        $goal = $this->findOwnedGoal($request, $goal);

        // dicari lewat relasi target milik user => catatan target lain tidak bisa dihapus
        $goal->contributions()->findOrFail($contribution)->delete();

        return redirect()
            ->route('savings-goals.show', $goal->id)
            ->with('success', 'Catatan progres berhasil dihapus.');
    }
}