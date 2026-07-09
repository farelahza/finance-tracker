<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
   public function index()
{
    $transactions = Transaction::where('user_id', auth()->id())
        ->with('category')
        ->latest()
        ->get();

    return view('transactions.index', compact('transactions'));
}


    public function create()
    {
        $categories = Category::all();

        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $validated['user_id'] = auth()->id();

        Transaction::create($validated);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function edit(Transaction $transaction)
{
    if ($transaction->user_id !== auth()->id()) {
        abort(403);
    }

    $categories = Category::all();

    return view(
        'transactions.edit',
        compact('transaction', 'categories')
    );
}

    public function update(Request $request, Transaction $transaction)
{
    if ($transaction->user_id !== auth()->id()) {
        abort(403);
    }

    $validated = $request->validate([
        'type' => 'required|in:income,expense',
        'category_id' => 'required|exists:categories,id',
        'amount' => 'required|integer|min:0',
        'description' => 'nullable|string',
        'transaction_date' => 'required|date',
    ]);

    $transaction->update($validated);

    return redirect()
        ->route('transactions.index')
        ->with('success', 'Transaksi berhasil diperbarui!');
}

public function destroy(Transaction $transaction)
{
    if ($transaction->user_id !== auth()->id()) {
        abort(403);
    }

    $transaction->delete();

    return redirect()
        ->route('transactions.index')
        ->with('success', 'Transaksi berhasil dihapus!');
}

public function dashboard(Request $request)
{
    $month = $request->month ?? now()->month;
    $year = $request->year ?? now()->year;

    $transactions = Transaction::where('user_id', auth()->id())
        ->whereMonth('transaction_date', $month)
        ->whereYear('transaction_date', $year);

    $totalIncome = (clone $transactions)
        ->where('type', 'income')
        ->sum('amount');

    $totalExpense = (clone $transactions)
        ->where('type', 'expense')
        ->sum('amount');

    $balance = $totalIncome - $totalExpense;

    // Data grafik 12 bulan
    $monthlyIncome = [];
    $monthlyExpense = [];

    for ($i = 1; $i <= 12; $i++) {
        $monthlyIncome[] = Transaction::where('user_id', auth()->id())
            ->whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $i)
            ->where('type', 'income')
            ->sum('amount');

        $monthlyExpense[] = Transaction::where('user_id', auth()->id())
            ->whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $i)
            ->where('type', 'expense')
            ->sum('amount');
    }

    return view('dashboard', compact(
        'totalIncome',
        'totalExpense',
        'balance',
        'month',
        'year',
        'monthlyIncome',
        'monthlyExpense'
    ));
}

}