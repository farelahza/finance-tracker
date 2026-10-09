<x-app-layout>

<div class="min-h-screen bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 py-10 transition-colors duration-300">
    <div class="max-w-2xl mx-auto">

       <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-3xl p-8 transition-colors">

            <h1 class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-white">
                ➕ Tambah Transaksi
            </h1>

            <form method="POST" action="{{ route('transactions.store') }}">
                @csrf

                <div class="mb-5">
                    <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">
                        Jenis Transaksi
                    </label>

                    <select name="type"
                        class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400">
                        <option value="income">💰 Pemasukan</option>
                        <option value="expense">💸 Pengeluaran</option>
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">
                        Kategori
                    </label>

                    <select name="category_id"
                        class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400">

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="mb-5">
                    <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">
                        Nominal
                    </label>

                    <input
                        type="number"
                        name="amount"
                        class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400"
                        placeholder="Contoh: 500000">
                </div>

                <div class="mb-5">
                    <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">
                        Deskripsi
                    </label>

                    <textarea
                        name="description"
                        rows="3"
                        class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400"
                        placeholder="Contoh: Gaji bulan Juli"></textarea>
                </div>

                <div class="mb-6">
                    <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">
                        Tanggal
                    </label>

                    <input
                        type="date"
                        name="transaction_date"
                        class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400">
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