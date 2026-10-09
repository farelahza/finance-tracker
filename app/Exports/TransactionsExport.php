<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Transaction::where('user_id', auth()->id())
            ->with('category')
            ->get()
            ->map(function ($transaction) {
                return [
                    $transaction->transaction_date,
                    $transaction->category->name,
                    $transaction->type == 'income'
                        ? 'Pemasukan'
                        : 'Pengeluaran',
                    $transaction->amount,
                    $transaction->description,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kategori',
            'Tipe',
            'Jumlah',
            'Deskripsi'
        ];
    }
}