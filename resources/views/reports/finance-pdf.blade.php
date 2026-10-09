<h1>Laporan Keuangan {{ $month }}/{{ $year }}</h1>

<p>Total Pemasukan:
Rp {{ number_format($totalIncome,0,',','.') }}</p>

<p>Total Pengeluaran:
Rp {{ number_format($totalExpense,0,',','.') }}</p>

<p>Saldo:
Rp {{ number_format($balance,0,',','.') }}</p>

<table border="1" width="100%" cellspacing="0" cellpadding="8">
    <tr>
        <th>Tanggal</th>
        <th>Kategori</th>
        <th>Tipe</th>
        <th>Jumlah</th>
    </tr>

    @foreach($transactions as $transaction)
    <tr>
        <td>{{ $transaction->transaction_date }}</td>
        <td>{{ $transaction->category->name }}</td>
        <td>{{ $transaction->type }}</td>
        <td>
            Rp {{ number_format($transaction->amount,0,',','.') }}
        </td>
    </tr>
    @endforeach
</table>