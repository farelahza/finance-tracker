<x-app-layout>

@php
    $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    // ===== Token warna (Midnight Navy + Champagne Gold) =====
    $card  = 'rounded-2xl border border-[#BE9A35] bg-[#F4E4A6] shadow-sm';
    $title = 'text-[#2A210B]';
    $muted = 'text-[#54421A]';
    $label = 'mb-1.5 block text-sm font-medium text-[#43340F]';

    $field = 'w-full rounded-xl border border-[#A8841F]
        bg-[#FBF1CC] text-[#2A210B]
        text-sm py-2.5 pl-3 pr-9 shadow-sm
        focus:border-[#B8892B] focus:ring-2 focus:ring-[#B8892B]/30 focus:outline-none transition';

    $btnPrimary = 'bg-gradient-to-b from-[#EBCB68] to-[#A67C1A] text-[#2A210B]
        border border-[#8A650F]/60 shadow-md shadow-[#8A650F]/30 hover:brightness-110
        focus:outline-none focus:ring-2 focus:ring-[#8A650F]/60 focus:ring-offset-2
        focus:ring-offset-[#F4E4A6]';

    $btnGhost = 'border border-[#A8841F] bg-[#F4E4A6]
        text-[#2A210B]
        hover:bg-[#E8D17F]
        focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40';

    $btnExcel = 'border border-[#176B50]/40 bg-[#176B50]/10
        text-[#176B50] hover:bg-[#176B50]/20
        focus:outline-none focus:ring-2 focus:ring-[#176B50]/40';

    $btnPdf = 'border border-[#8E2740]/40 bg-[#8E2740]/10
        text-[#8E2740] hover:bg-[#8E2740]/20
        focus:outline-none focus:ring-2 focus:ring-[#8E2740]/40';

    $adaPengeluaran = collect($categoryTotals)->sum() > 0;
@endphp

<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- ===== Header ===== --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-['Playfair_Display',serif] text-3xl sm:text-4xl font-semibold tracking-tight {{ $title }}">
                    Dashboard Keuangan
                </h1>
                <p class="mt-1 text-sm {{ $muted }}">
                    Ringkasan keuangan {{ $namaBulan[(int) $month] ?? '' }} {{ $year }}
                </p>
            </div>

            <a href="{{ route('transactions.index') }}"
               class="inline-flex items-center justify-center gap-2 self-start sm:self-auto rounded-xl px-4 py-2.5 text-sm font-semibold shadow-sm transition {{ $btnGhost }}">
                <span aria-hidden="true">⬅️</span> Kembali ke Transaksi
            </a>
        </div>

        {{-- ===== Filter & Export ===== --}}
        <form method="GET" action="{{ route('dashboard') }}" class="{{ $card }} p-4 sm:p-5">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

                <div class="grid grid-cols-2 gap-3 sm:flex sm:items-end sm:gap-3">
                    <div class="sm:w-44">
                        <label for="month" class="{{ $label }}">📅 Bulan</label>
                        <select id="month" name="month" class="{{ $field }}">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                    {{ $namaBulan[$i] }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="sm:w-32">
                        <label for="year" class="{{ $label }}">🗓️ Tahun</label>
                        <select id="year" name="year" class="{{ $field }}">
                            @for($i = now()->year; $i >= now()->year - 10; $i--)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <button type="submit"
                            class="col-span-2 sm:col-span-1 inline-flex items-center justify-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold shadow-sm transition {{ $btnPrimary }}">
                        🔍 Filter
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:flex">
                    <a href="{{ route('transactions.export') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ $btnExcel }}">
                        📥 Export Excel
                    </a>

                    <a href="{{ route('export.pdf', ['month' => $month, 'year' => $year]) }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ $btnPdf }}">
                        📄 Export PDF
                    </a>
                </div>
            </div>
        </form>

        {{-- ===== Summary Cards ===== --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-6">

            {{-- Saldo (kartu utama: navy + aksen emas) --}}
            <div class="rounded-2xl border border-[#E3BE5B]/60 p-6 shadow-xl shadow-[#14213D]/30
                        bg-gradient-to-br from-[#14213D] via-[#1B2B52] to-[#233763]">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-[#D9C99A]">Saldo Saat Ini</h2>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E3BE5B]/15 ring-1 ring-[#E3BE5B]/30 text-xl" aria-hidden="true">💰</span>
                </div>
                <p class="mt-4 font-['Playfair_Display',serif] text-3xl font-semibold tracking-tight text-[#F0D27A] break-words">
                    Rp {{ number_format($balance, 0, ',', '.') }}
                </p>
            </div>

            {{-- Pemasukan --}}
            <div class="{{ $card }} p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-medium {{ $muted }}">Total Pemasukan</h2>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#176B50]/10 text-xl" aria-hidden="true">📈</span>
                </div>
                <p class="mt-4 font-['Playfair_Display',serif] text-2xl font-semibold tracking-tight text-[#176B50] break-words">
                    Rp {{ number_format($totalIncome, 0, ',', '.') }}
                </p>
            </div>

            {{-- Pengeluaran --}}
            <div class="{{ $card }} p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-medium {{ $muted }}">Total Pengeluaran</h2>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#8E2740]/10 text-xl" aria-hidden="true">📉</span>
                </div>
                <p class="mt-4 font-['Playfair_Display',serif] text-2xl font-semibold tracking-tight text-[#8E2740] break-words">
                    Rp {{ number_format($totalExpense, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- ===== Charts ===== --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">

            <section class="lg:col-span-3 {{ $card }} p-5 sm:p-6">
                <h2 class="text-lg font-semibold {{ $title }}">📊 Grafik Keuangan Tahun {{ $year }}</h2>
                <p class="mb-4 text-sm {{ $muted }}">Pemasukan dan pengeluaran per bulan</p>

                <div class="relative h-72 sm:h-80">
                    <canvas id="financeChart"></canvas>
                </div>
            </section>

            <section class="lg:col-span-2 {{ $card }} p-5 sm:p-6">
                <h2 class="text-lg font-semibold {{ $title }}">🥧 Pengeluaran per Kategori</h2>
                <p class="mb-4 text-sm {{ $muted }}">Porsi tiap kategori dari total pengeluaran</p>

                <div class="relative h-72 sm:h-80">
                    @if($adaPengeluaran)
                        <canvas id="categoryChart"></canvas>
                    @else
                        <div class="flex h-full flex-col items-center justify-center text-center">
                            <div class="text-4xl" aria-hidden="true">🧾</div>
                            <p class="mt-3 font-medium {{ $title }}">Belum ada pengeluaran</p>
                            <p class="mt-1 text-sm {{ $muted }}">
                                Data kategori akan muncul setelah ada transaksi pengeluaran.
                            </p>
                        </div>
                    @endif
                </div>
            </section>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    Chart.register(ChartDataLabels);
    Chart.defaults.font.family = "'Figtree', ui-sans-serif, system-ui, sans-serif";

    const theme = () => ({
        text:    '#43340F',
        grid:    'rgba(42,33,11,0.12)',
        surface: '#F4E4A6',      // sama dengan warna card
        income:  '#176B50',
        expense: '#8E2740'
    });

    const rupiah = (v) => 'Rp ' + Number(v).toLocaleString('id-ID');

    /* ---------- Bar chart ---------- */
    const financeCanvas = document.getElementById('financeChart');
    let financeChart = null;

    if (financeCanvas) {
        const t = theme();

        financeChart = new Chart(financeCanvas, {
            type: 'bar',
            data: {
                labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: @json($monthlyIncome),
                        backgroundColor: t.income,
                        borderRadius: 6,
                        borderSkipped: false,
                        maxBarThickness: 22
                    },
                    {
                        label: 'Pengeluaran',
                        data: @json($monthlyExpense),
                        backgroundColor: t.expense,
                        borderRadius: 6,
                        borderSkipped: false,
                        maxBarThickness: 22
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            color: t.text,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 8,
                            boxHeight: 8,
                            padding: 18,
                            font: { size: 13 }
                        }
                    },
                    tooltip: {
                        callbacks: { label: (ctx) => ctx.dataset.label + ': ' + rupiah(ctx.raw) }
                    },
                    datalabels: { display: false }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: t.text } },
                    y: {
                        beginAtZero: true,
                        grid: { color: t.grid },
                        border: { display: false },
                        ticks: {
                            color: t.text,
                            callback: (v) => Number(v).toLocaleString('id-ID', { notation: 'compact' })
                        }
                    }
                }
            }
        });
    }

    /* ---------- Pie chart ---------- */
    const categoryCanvas = document.getElementById('categoryChart');
    let categoryChart = null;

    if (categoryCanvas) {
        const t = theme();

        categoryChart = new Chart(categoryCanvas, {
            type: 'pie',
            data: {
                labels: @json($categoryLabels),
                datasets: [{
                    data: @json($categoryTotals),
                    backgroundColor: [
                        '#B8892B', '#1E7A5A', '#3F5E8F', '#9B2D44',
                        '#6D4F9E', '#C2703D', '#2E8F9A', '#8A7A50'
                    ],
                    borderWidth: 3,
                    borderColor: t.surface,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: t.text,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 8,
                            boxHeight: 8,
                            padding: 16,
                            font: { size: 13 }
                        }
                    },
                    tooltip: {
                        callbacks: { label: (ctx) => ctx.label + ': ' + rupiah(ctx.raw) }
                    },
                    datalabels: {
                        color: '#0D0A04',
                        font: { weight: 'bold', size: 13 },
                        display: (context) => {
                            const data = context.chart.data.datasets[0].data;
                            const total = data.reduce((s, c) => s + Number(c), 0);
                            return total > 0 && (Number(context.dataset.data[context.dataIndex]) / total) >= 0.04;
                        },
                        formatter: (value, context) => {
                            const data = context.chart.data.datasets[0].data;
                            const total = data.reduce((s, c) => s + Number(c), 0);
                            return ((Number(value) / total) * 100).toFixed(1) + '%';
                        }
                    }
                }
            }
        });
    }

});
</script>
</x-app-layout>