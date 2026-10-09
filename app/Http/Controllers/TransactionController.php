<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
  public function index(Request $request)
{
    $transactions = Transaction::where('user_id', auth()->id());

    if ($request->filled('transaction_date')) {
        $transactions->whereDate(
            'transaction_date',
            $request->transaction_date
        );
    }

    if ($request->filled('category_id')) {
        $transactions->where(
            'category_id',
            $request->category_id
        );
    }

    if ($request->filled('type')) {
        $transactions->where(
            'type',
            $request->type
        );
    }

    if ($request->amount_filter == 'under_100') {
    $transactions->where('amount', '<', 100000);
}

if ($request->amount_filter == '100_500') {
    $transactions->whereBetween('amount', [100000, 500000]);
}

if ($request->amount_filter == '500_1000') {
    $transactions->whereBetween('amount', [500000, 1000000]);
}

if ($request->amount_filter == 'above_1000') {
    $transactions->where('amount', '>', 1000000);
}

   $transactions = $transactions
    ->latest()
    ->paginate(10)
    ->withQueryString();

    $categories = Category::all();

    return view(
        'transactions.index',
        compact(
            'transactions',
            'categories'
        )
    );
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

    // ======================
    // Grafik batang tahunan
    // ======================
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

    // ======================
    // Pie Chart Kategori
    // ======================
    $expenseByCategory = Transaction::join(
            'categories',
            'transactions.category_id',
            '=',
            'categories.id'
        )
        ->select(
            'categories.name',
            DB::raw('SUM(transactions.amount) as total')
        )
        ->where('transactions.user_id', auth()->id())
        ->where('transactions.type', 'expense')
        ->whereMonth('transactions.transaction_date', $month)
        ->whereYear('transactions.transaction_date', $year)
        ->groupBy('categories.name')
        ->get();

    $categoryLabels = $expenseByCategory->pluck('name')->toArray();

    $categoryTotals = $expenseByCategory
        ->pluck('total')
        ->map(fn($value) => (float)$value)
        ->toArray();

    return view('dashboard', compact(
        'totalIncome',
        'totalExpense',
        'balance',
        'month',
        'year',
        'monthlyIncome',
        'monthlyExpense',
        'categoryLabels',
        'categoryTotals'
    ));
 }

 public function exportPdf(Request $request)
{
    $month = $request->month ?? now()->month;
    $year = $request->year ?? now()->year;

    $transactions = Transaction::where('user_id', auth()->id())
        ->whereMonth('transaction_date', $month)
        ->whereYear('transaction_date', $year)
        ->with('category')
        ->get();

    $totalIncome = $transactions
        ->where('type', 'income')
        ->sum('amount');

    $totalExpense = $transactions
        ->where('type', 'expense')
        ->sum('amount');

    $balance = $totalIncome - $totalExpense;

    $pdf = Pdf::loadView(
        'reports.finance-pdf',
        compact(
            'transactions',
            'month',
            'year',
            'totalIncome',
            'totalExpense',
            'balance'
        )
    );

    return $pdf->download(
        "laporan-keuangan-$month-$year.pdf"
    );
}

public function export()
{
    return Excel::download(
        new TransactionsExport,
        'laporan-keuangan.xlsx'
    );
}

 }