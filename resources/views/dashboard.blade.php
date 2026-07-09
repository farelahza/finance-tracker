<x-app-layout>

<div class="min-h-screen bg-gradient-to-br from-yellow-300 via-pink-300 to-purple-400 py-8">
    <div class="max-w-7xl mx-auto px-6">

        <h1 class="text-5xl font-bold mb-6 text-white drop-shadow-lg">
            Dashboard Keuangan
        </h1>

        <form method="GET"
            action="{{ route('dashboard') }}"
            class="bg-gradient-to-r from-cyan-500 to-blue-700 p-6 rounded-2xl shadow-2xl mb-8">

            <div class="flex flex-wrap gap-4 items-end">

                <div>
                    <label class="block text-white font-semibold mb-2">
                        📅 Bulan
                    </label>

                    <select name="month"
                        class="bg-white rounded-xl px-4 py-2 shadow-lg">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}"
                                {{ $month == $i ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-white font-semibold mb-2">
                        🗓️ Tahun
                    </label>

                    <select name="year"
                        class="bg-white rounded-xl px-4 py-2 shadow-lg">
                        @for($i = now()->year; $i >= now()->year - 5; $i--)
                            <option value="{{ $i }}"
                                {{ $year == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <button type="submit"
                    class="bg-yellow-400 text-black font-bold px-6 py-2 rounded-xl shadow-lg hover:scale-105 transition">
                    🔍 Filter
                </button>

            </div>

        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

            <div class="bg-blue-600 text-white p-6 rounded-xl shadow-2xl">
                <h2 class="text-lg font-semibold">
                    💰 Saldo Saat Ini
                </h2>

                <p class="text-3xl font-bold mt-3">
                    Rp {{ number_format($balance, 0, ',', '.') }}
                </p>
            </div>

            <div class="bg-green-600 text-white p-6 rounded-xl shadow-2xl">
                <h2 class="text-lg font-semibold">
                    📈 Total Pemasukan
                </h2>

                <p class="text-2xl font-bold mt-3">
                    Rp {{ number_format($totalIncome, 0, ',', '.') }}
                </p>
            </div>

            <div class="bg-red-600 text-white p-6 rounded-xl shadow-2xl">
                <h2 class="text-lg font-semibold">
                    📉 Total Pengeluaran
                </h2>

                <p class="text-2xl font-bold mt-3">
                    Rp {{ number_format($totalExpense, 0, ',', '.') }}
                </p>
            </div>

        </div>

        <div class="bg-white p-6 rounded-xl shadow-2xl">

            <h2 class="text-2xl font-bold mb-4">
                Grafik Keuangan Tahun {{ $year }}
            </h2>

            <canvas id="financeChart"></canvas>

        </div>

    </div>
</div>

</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('financeChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
        ],
        datasets: [
            {
                label: 'Pemasukan',
                data: @json($monthlyIncome),
                backgroundColor: 'rgba(34, 197, 94, 0.7)',
                borderColor: 'rgba(34, 197, 94, 1)',
                borderWidth: 1
            },
            {
                label: 'Pengeluaran',
                data: @json($monthlyExpense),
                backgroundColor: 'rgba(239, 68, 68, 0.7)',
                borderColor: 'rgba(239, 68, 68, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
