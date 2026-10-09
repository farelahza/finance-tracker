<?php

namespace App\Http\Controllers;

use App\Models\SavingsGoal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SavingsGoalController extends Controller
{
    private const MESSAGES = [
        'name.required'           => 'Nama target wajib diisi.',
        'name.max'                => 'Nama target maksimal 100 karakter.',
        'target_amount.required'  => 'Nominal target wajib diisi.',
        'target_amount.integer'   => 'Nominal target harus berupa angka bulat (tanpa titik, koma, atau huruf).',
        'target_amount.min'       => 'Nominal target harus lebih dari 0.',
        'target_amount.max'       => 'Nominal target terlalu besar.',
        'description.max'         => 'Deskripsi maksimal 1000 karakter.',
        'deadline.date'           => 'Format deadline tidak valid.',
        'deadline.after_or_equal' => 'Deadline tidak boleh sebelum hari ini.',
    ];

    /** Hanya mengambil target milik user yang login; milik orang lain => 404. */
    private function findOwned(Request $request, int|string $id): SavingsGoal
    {
        return SavingsGoal::ownedBy($request->user()->id)->findOrFail($id);
    }

    private function rules(bool $creating): array
    {
        return [
            'name'          => ['required', 'string', 'max:100'],
            'target_amount' => ['required', 'integer', 'min:1', 'max:999999999999'],
            'description'   => ['nullable', 'string', 'max:1000'],
            'deadline'      => $creating
                ? ['nullable', 'date', 'after_or_equal:today']
                : ['nullable', 'date'],
        ];
    }

    public function index(Request $request): View
    {
        $goals = SavingsGoal::ownedBy($request->user()->id)
            ->withSum('contributions', 'amount')
            ->latest()
            ->get()
            // target yang masih berjalan tampil lebih dulu, yang tercapai di bawah
            ->sortBy(fn (SavingsGoal $goal) => $goal->is_achieved ? 1 : 0)
            ->values();

        return view('savings.index', compact('goals'));
    }

    public function create(): View
    {
        return view('savings.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(true), self::MESSAGES);

        $goal = new SavingsGoal($validated);
        $goal->user_id = $request->user()->id;   // tidak pernah dari input form
        $goal->save();

        return redirect()
            ->route('savings-goals.show', $goal->id)
            ->with('success', 'Target tabungan berhasil dibuat.');
    }

    public function show(Request $request, int|string $goal): View
    {
        $goal = $this->findOwned($request, $goal);
        $goal->loadSum('contributions', 'amount');

        $contributions = $goal->contributions()
            ->orderByDesc('saved_at')
            ->orderByDesc('id')
            ->paginate(10);

        return view('savings.show', compact('goal', 'contributions'));
    }

    public function edit(Request $request, int|string $goal): View
    {
        $goal = $this->findOwned($request, $goal);

        return view('savings.edit', compact('goal'));
    }

    public function update(Request $request, int|string $goal): RedirectResponse
    {
        $goal = $this->findOwned($request, $goal);

        $goal->update($request->validate($this->rules(false), self::MESSAGES));

        return redirect()
            ->route('savings-goals.show', $goal->id)
            ->with('success', 'Target tabungan berhasil diperbarui.');
    }

    public function destroy(Request $request, int|string $goal): RedirectResponse
    {
        $goal = $this->findOwned($request, $goal);
        $goal->delete();   // catatan progres ikut terhapus (cascade pada foreign key)

        return redirect()
            ->route('savings-goals.index')
            ->with('success', 'Target tabungan berhasil dihapus.');
    }
}