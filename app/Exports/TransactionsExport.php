<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    /** Jumlah baris data (tanpa judul), dipakai untuk pewarnaan kolom. */
    private int $rowCount = 0;

    /**
     * Tanpa argumen => semua transaksi user (perilaku lama).
     * new TransactionsExport(2026)       => seluruh tahun 2026
     * new TransactionsExport(2026, 7)    => hanya Juli 2026
     */
    public function __construct(
        private ?int $year = null,
        private ?int $month = null,
    ) {
    }

    public function collection()
    {
        $query = Transaction::where('user_id', auth()->id())
            ->with('category');

        if ($this->year !== null) {
            $query->whereYear('transaction_date', $this->year);
        }

        if ($this->month !== null) {
            $query->whereMonth('transaction_date', $this->month);
        }

        $rows = $query
            ->get()
            ->map(function ($transaction) {
                $isIncome = $transaction->type == 'income';

                return [
                    $transaction->transaction_date,
                    $transaction->category->name,
                    // Nominal masuk ke SATU kolom saja; kolom lainnya dikosongkan
                    $isIncome ? $transaction->amount : null,   // Pemasukan
                    $isIncome ? null : $transaction->amount,   // Pengeluaran
                    $transaction->description,
                ];
            });

        $this->rowCount = $rows->count();

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kategori',
            'Pemasukan',
            'Pengeluaran',
            'Deskripsi',
        ];
    }

    /** Pemisah ribuan pada kolom nominal. */
    public function columnFormats(): array
    {
        return [
            'C' => '#,##0',
            'D' => '#,##0',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Judul kolom: tebal; Pemasukan = hijau, Pengeluaran = merah
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $sheet->getStyle('C1')->getFill()
            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('176B50');
        $sheet->getStyle('C1')->getFont()->getColor()->setRGB('FFFFFF');

        $sheet->getStyle('D1')->getFill()
            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('8E2740');
        $sheet->getStyle('D1')->getFont()->getColor()->setRGB('FFFFFF');

        // Angka: hijau untuk pemasukan, merah untuk pengeluaran
        if ($this->rowCount > 0) {
            $last = $this->rowCount + 1;

            $sheet->getStyle("C2:C{$last}")->getFont()->getColor()->setRGB('176B50');
            $sheet->getStyle("D2:D{$last}")->getFont()->getColor()->setRGB('8E2740');
        }

        return [];
    }
}