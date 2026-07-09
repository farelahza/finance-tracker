<x-app-layout>

<div class="min-h-screen bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 py-10">

    <div class="max-w-7xl mx-auto px-6">

        <div class="flex justify-between items-center mb-8">

            <h1 class="text-4xl font-bold text-gray-800">
                📋 Daftar Transaksi
            </h1>

            <a href="{{ route('transactions.create') }}"
               class="bg-blue-600 text-white px-5 py-3 rounded-xl shadow-lg hover:bg-blue-700 transition">
                ➕ Tambah Transaksi
            </a>

        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

            <table class="w-full">

                <thead class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white">

                    <tr>
                        <th class="p-4 text-left">Tanggal</th>
                        <th class="p-4 text-left">Kategori</th>
                        <th class="p-4 text-left">Tipe</th>
                        <th class="p-4 text-left">Jumlah</th>
                        <th class="p-4 text-left">Deskripsi</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($transactions as $transaction)

                        <tr class="border-b hover:bg-gray-50 transition">

                            <td class="p-4">
                                {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}
                            </td>

                            <td class="p-4 font-medium">
                                {{ $transaction->category->name }}
                            </td>

                            <td class="p-4">

                                @if($transaction->type == 'income')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                                        💰 Pemasukan
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">
                                        💸 Pengeluaran
                                    </span>
                                @endif

                            </td>

                            <td class="p-4 font-bold">
                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>

                            <td class="p-4">
                                {{ $transaction->description ?? '-' }}
                            </td>

                            <td class="p-4 text-center">

                                <div class="flex justify-center gap-2">

                                    <a href="{{ route('transactions.edit', $transaction->id) }}"
                                       class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                                        ✏️ Edit
                                    </a>

                                    <form action="{{ route('transactions.destroy', $transaction->id) }}"
                                          method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            onclick="return confirm('Yakin ingin menghapus transaksi ini?')"
                                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                                            🗑️ Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center p-10 text-gray-500">
                                Belum ada transaksi.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

</x-app-layout>