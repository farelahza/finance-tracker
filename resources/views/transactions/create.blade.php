<x-app-layout>

<div class="min-h-screen bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 py-10">
    <div class="max-w-2xl mx-auto">

        <div class="bg-white shadow-2xl rounded-3xl p-8">

            <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">
                ➕ Tambah Transaksi
            </h1>

            <form method="POST" action="{{ route('transactions.store') }}">
                @csrf

                <div class="mb-5">
                    <label class="block font-semibold mb-2">
                        Jenis Transaksi
                    </label>

                    <select name="type"
                        class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400">
                        <option value="income">💰 Pemasukan</option>
                        <option value="expense">💸 Pengeluaran</option>
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block font-semibold mb-2">
                        Kategori
                    </label>

                    <select name="category_id"
                        class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400">

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="mb-5">
                    <label class="block font-semibold mb-2">
                        Nominal
                    </label>

                    <input
                        type="number"
                        name="amount"
                        class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400"
                        placeholder="Contoh: 500000">
                </div>

                <div class="mb-5">
                    <label class="block font-semibold mb-2">
                        Deskripsi
                    </label>

                    <textarea
                        name="description"
                        rows="3"
                        class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400"
                        placeholder="Contoh: Gaji bulan Juli"></textarea>
                </div>

                <div class="mb-6">
                    <label class="block font-semibold mb-2">
                        Tanggal
                    </label>

                    <input
                        type="date"
                        name="transaction_date"
                        class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400">
                </div>

                <div class="flex gap-4">

    <a href="{{ route('transactions.index') }}"
       class="w-1/2 text-center bg-gray-500 text-white py-3 rounded-xl font-bold hover:bg-gray-600 transition">
        ← Kembali
    </a>

    <button
        type="submit"
        class="w-1/2 bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition">
        💾 Simpan Transaksi
    </button>

</div>
            </form>

        </div>
    </div>
</div>

</x-app-layout>