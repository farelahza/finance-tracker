<x-app-layout>

<div class="min-h-screen bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 py-10">

    <div class="max-w-2xl mx-auto">

        <div class="bg-white shadow-2xl rounded-3xl p-8">

            <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">
                ✏️ Edit Transaksi
            </h1>

            <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="mb-5">
                    <label class="block font-semibold mb-2 text-gray-700">
                        Jenis Transaksi
                    </label>

                    <select
                        name="type"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                        <option value="income"
                            {{ $transaction->type == 'income' ? 'selected' : '' }}>
                            💰 Pemasukan
                        </option>

                        <option value="expense"
                            {{ $transaction->type == 'expense' ? 'selected' : '' }}>
                            💸 Pengeluaran
                        </option>
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block font-semibold mb-2 text-gray-700">
                        Kategori
                    </label>

                    <select
                        name="category_id"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                        @foreach($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                {{ $transaction->category_id == $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block font-semibold mb-2 text-gray-700">
                        Jumlah
                    </label>

                    <input
                        type="number"
                        name="amount"
                        value="{{ $transaction->amount }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="Contoh: 500000"
                    >
                </div>

                <div class="mb-5">
                    <label class="block font-semibold mb-2 text-gray-700">
                        Deskripsi
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="Masukkan deskripsi transaksi..."
                    >{{ $transaction->description }}</textarea>
                </div>

                <div class="mb-8">
                    <label class="block font-semibold mb-2 text-gray-700">
                        Tanggal Transaksi
                    </label>

                    <input
                        type="date"
                        name="transaction_date"
                        value="{{ $transaction->transaction_date }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                </div>

                <div class="flex gap-4">

                    <a href="{{ route('transactions.index') }}"
                       class="w-1/2 text-center bg-gray-500 text-white py-3 rounded-xl font-bold hover:bg-gray-600 transition">
                        ← Kembali
                    </a>

                    <button
                        type="submit"
                        class="w-1/2 bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition"
                    >
                        💾 Update Transaksi
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</x-app-layout>