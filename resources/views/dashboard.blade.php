<x-app-layout>

@php
    $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
    $periode = ($namaBulan[(int) $month] ?? '') . ' ' . $year;

    // ===== Token warna (Emas + Navy) =====
    $card  = 'rounded-2xl border border-[#BE9A35] bg-[#F4E4A6] shadow-sm';
    $title = 'text-[#2A210B]';
    $muted = 'text-[#54421A]';
    $label = 'mb-1.5 block text-sm font-medium text-[#43340F]';

    $field = 'w-full rounded-xl border border-[#A8841F] bg-[#FBF1CC] text-[#2A210B]
        text-base sm:text-sm py-2.5 pl-3 pr-9 shadow-sm
        focus:border-[#B8892B] focus:ring-2 focus:ring-[#B8892B]/30 focus:outline-none transition';

    $btnPrimary = 'bg-gradient-to-b from-[#EBCB68] to-[#A67C1A] text-[#2A210B]
        border border-[#8A650F]/60 shadow-md shadow-[#8A650F]/30 hover:brightness-110
        focus:outline-none focus:ring-2 focus:ring-[#8A650F]/60 focus:ring-offset-2
        focus:ring-offset-[#F4E4A6]';

    $btnExcel = 'border border-[#176B50]/40 bg-[#176B50]/10 text-[#176B50] hover:bg-[#176B50]/20
        focus:outline-none focus:ring-2 focus:ring-[#176B50]/40';


    // ===== Data kategori (hanya membaca variabel dari controller) =====
    $palette = ['#B8892B', '#1E7A5A', '#3F5E8F', '#9B2D44', '#6D4F9E', '#C2703D', '#2E8F9A', '#8A7A50'];

    $catLabels = collect($categoryLabels)->values();
    $catTotals = collect($categoryTotals)->values();
    $sumCat    = (float) $catTotals->sum();

    $catRows = $catLabels->map(function ($label, $i) use ($catTotals, $palette, $sumCat) {
        $amount = (float) ($catTotals[$i] ?? 0);

        return [
            'label'  => $label,
            'amount' => $amount,
            'color'  => $palette[$i % count($palette)],
            'pct'    => $sumCat > 0 ? round($amount / $sumCat * 100, 1) : 0,
        ];
    });

    $adaPengeluaran = $sumCat > 0;
    $adaDataTahun   = (collect($monthlyIncome)->sum() + collect($monthlyExpense)->sum()) > 0;
    $tidakAdaData   = ((float) $totalIncome == 0.0) && ((float) $totalExpense == 0.0);
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
                    Ringkasan keuangan {{ $periode }}
                </p>
            </div>

            <a href="{{ route('transactions.create') }}"
               class="inline-flex items-center justify-center gap-2 self-start sm:self-auto rounded-xl px-5 py-2.5 text-sm font-semibold transition {{ $btnPrimary }}">
                <x-icon name="plus" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Tambah Transaksi
            </a>
        </div>

        {{-- ===== Filter & Export ===== --}}
        <form method="GET" action="{{ route('dashboard') }}" class="{{ $card }} p-4 sm:p-5">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

                <div class="grid grid-cols-2 gap-3 sm:flex sm:items-end sm:gap-3">
                    <div class="sm:w-44">
                        <label for="month" class="{{ $label }}"><x-icon name="calendar" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Bulan</label>
                        <select id="month" name="month" class="{{ $field }}">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                    {{ $namaBulan[$i] }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="sm:w-32">
                        <label for="year" class="{{ $label }}"><x-icon name="calendar" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Tahun</label>
                        <select id="year" name="year" class="{{ $field }}">
                            @for($i = now()->year; $i >= now()->year - 10; $i--)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <button type="submit"
                            class="col-span-2 sm:col-span-1 inline-flex items-center justify-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold transition {{ $btnPrimary }}">
                        <x-icon name="search" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Filter
                    </button>
                </div>

                <div class="relative w-full sm:w-auto" x-data="{ open: false }"
                     @click.outside="open = false" @keydown.escape.window="open = false">

                    <button type="button" @click="open = !open" aria-haspopup="menu" :aria-expanded="open"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition sm:w-auto {{ $btnExcel }}">
                        <x-icon name="download" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Export Excel
                        <svg class="h-4 w-4 fill-current transition-transform" :class="open ? 'rotate-180' : ''"
                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                  clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="open" x-transition role="menu" style="display:none"
                         class="absolute inset-x-0 z-30 mt-2 overflow-hidden rounded-xl border border-[#BE9A35] bg-[#F4E4A6] shadow-xl sm:inset-x-auto sm:right-0 sm:w-72">

                        <a role="menuitem"
                           href="{{ route('transactions.export', ['scope' => 'month', 'month' => $month, 'year' => $year]) }}"
                           class="flex items-start gap-3 px-4 py-3 transition hover:bg-[#E8D17F] focus:bg-[#E8D17F] focus:outline-none">
                            <span class="mt-0.5 text-[#176B50]"><x-icon name="calendar" class="h-5 w-5" /></span>
                            <span>
                                <span class="block text-sm font-semibold text-[#2A210B]">Bulan sesuai filter</span>
                                <span class="block text-xs text-[#54421A]">Hanya transaksi {{ $periode }}</span>
                            </span>
                        </a>

                        <a role="menuitem"
                           href="{{ route('transactions.export', ['scope' => 'year', 'year' => $year]) }}"
                           class="flex items-start gap-3 border-t border-[#BE9A35]/60 px-4 py-3 transition hover:bg-[#E8D17F] focus:bg-[#E8D17F] focus:outline-none">
                            <span class="mt-0.5 text-[#176B50]"><x-icon name="chart" class="h-5 w-5" /></span>
                            <span>
                                <span class="block text-sm font-semibold text-[#2A210B]">Seluruh tahun {{ $year }}</span>
                                <span class="block text-xs text-[#54421A]">Semua transaksi Januari - Desember</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </form>

        {{-- ===== Info bila periode kosong ===== --}}
        @if($tidakAdaData)
            <div role="status"
                 class="flex items-start gap-3 rounded-xl border border-[#14213D]/25 bg-[#14213D]/5 px-4 py-3 text-sm text-[#43340F]">
                <span class="mt-0.5 text-[#14213D]"><x-icon name="info" class="h-5 w-5" /></span>
                <p>
                    Belum ada transaksi pada <strong>{{ $periode }}</strong>, jadi total pemasukan dan pengeluaran masih Rp 0.
                    Pilih bulan lain pada filter, atau
                    <a href="{{ route('transactions.create') }}" class="font-semibold text-[#14213D] underline underline-offset-2">tambah transaksi</a>.
                </p>
            </div>
        @endif

        {{-- ===== Summary Cards ===== --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-6">

            {{-- Saldo (kartu utama: navy + teks emas) --}}
            <div class="rounded-2xl border border-[#E3BE5B]/60 p-6 shadow-xl shadow-[#14213D]/30
                        bg-gradient-to-br from-[#14213D] via-[#1B2B52] to-[#233763]">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-[#D9C99A]">Saldo Saat Ini</h2>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E3BE5B]/15 ring-1 ring-[#E3BE5B]/30 text-xl text-[#F0D27A]" aria-hidden="true"><x-icon name="wallet" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /></span>
                </div>
                <p class="mt-4 text-3xl font-bold tabular-nums tracking-tight text-[#F0D27A] break-words">
                    Rp {{ number_format($balance, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-[#B9A56B]">Akumulasi seluruh transaksi</p>
            </div>

            {{-- Pemasukan --}}
            <div class="{{ $card }} p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-medium {{ $muted }}">Total Pemasukan</h2>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#176B50]/10 text-xl text-[#176B50]" aria-hidden="true"><x-icon name="trend-up" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /></span>
                </div>
                <p class="mt-4 text-2xl font-bold tabular-nums tracking-tight text-[#176B50] break-words">
                    Rp {{ number_format($totalIncome, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs {{ $muted }}">Periode {{ $periode }}</p>
            </div>

            {{-- Pengeluaran --}}
            <div class="{{ $card }} p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-medium {{ $muted }}">Total Pengeluaran</h2>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#8E2740]/10 text-xl text-[#8E2740]" aria-hidden="true"><x-icon name="trend-down" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /></span>
                </div>
                <p class="mt-4 text-2xl font-bold tabular-nums tracking-tight text-[#8E2740] break-words">
                    Rp {{ number_format($totalExpense, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs {{ $muted }}">Periode {{ $periode }}</p>
            </div>
        </div>

        {{-- ===== Charts ===== --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">

            <section class="lg:col-span-3 {{ $card }} p-5 sm:p-6">
                <h2 class="text-lg font-semibold {{ $title }}"><x-icon name="chart" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Grafik Keuangan Tahun {{ $year }}</h2>
                <p class="mb-4 text-sm {{ $muted }}">Pemasukan dan pengeluaran per bulan. {{ $namaBulan[(int) $month] ?? '' }} ditandai.</p>

                <div class="relative h-72 sm:h-80">
                    @if($adaDataTahun)
                        <canvas id="financeChart"></canvas>
                    @else
                        <div class="flex h-full flex-col items-center justify-center text-center">
                            <div class="text-4xl text-[#54421A]" aria-hidden="true"><x-icon name="chart" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /></div>
                            <p class="mt-3 font-medium {{ $title }}">Belum ada data tahun {{ $year }}</p>
                            <p class="mt-1 text-sm {{ $muted }}">Grafik muncul setelah ada transaksi pada tahun ini.</p>
                        </div>
                    @endif
                </div>
            </section>

            <section class="lg:col-span-2 {{ $card }} p-5 sm:p-6">
                <h2 class="text-lg font-semibold {{ $title }}"><x-icon name="pie" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /> Pengeluaran per Kategori</h2>
                <p class="mb-4 text-sm {{ $muted }}">Porsi tiap kategori, {{ $periode }}</p>

                @if($adaPengeluaran)
                    <div class="relative mx-auto h-56 w-56 sm:h-60 sm:w-60">
                        <canvas id="categoryChart"></canvas>
                    </div>

                    <ul class="mt-5 max-h-52 space-y-2.5 overflow-y-auto pr-1">
                        @foreach($catRows as $row)
                            <li class="flex items-center justify-between gap-3 text-sm">
                                <span class="flex min-w-0 items-center gap-2.5">
                                    <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background-color: {{ $row['color'] }}"></span>
                                    <span class="truncate text-[#43340F]">{{ $row['label'] }}</span>
                                </span>
                                <span class="shrink-0 text-right">
                                    <span class="font-semibold tabular-nums {{ $title }}">Rp {{ number_format($row['amount'], 0, ',', '.') }}</span>
                                    <span class="ml-1 text-xs tabular-nums {{ $muted }}">{{ number_format($row['pct'], 1, ',', '.') }}%</span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="flex h-72 flex-col items-center justify-center text-center">
                        <div class="text-4xl text-[#54421A]" aria-hidden="true"><x-icon name="receipt" class="h-[1.15em] w-[1.15em] align-[-0.2em]" /></div>
                        <p class="mt-3 font-medium {{ $title }}">Belum ada pengeluaran</p>
                        <p class="mt-1 text-sm {{ $muted }}">
                            Data kategori akan muncul setelah ada transaksi pengeluaran di {{ $periode }}.
                        </p>
                    </div>
                @endif
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

    const C = {
        text:    '#43340F',
        grid:    'rgba(42,33,11,0.12)',
        surface: '#F4E4A6',      // sama dengan warna card
        income:  '#176B50',
        expense: '#8E2740'
    };

    const rupiah = (v) => 'Rp ' + Number(v).toLocaleString('id-ID');
    const selectedMonth = {{ (int) $month }} - 1;

    /* ---------- Bar chart: pemasukan vs pengeluaran ---------- */
    const financeCanvas = document.getElementById('financeChart');

    if (financeCanvas) {

        // pita latar yang menandai bulan terpilih
        const monthBand = {
            id: 'monthBand',
            beforeDatasetsDraw(chart) {
                const area = chart.chartArea;
                const step = (area.right - area.left) / 12;
                const ctx  = chart.ctx;

                ctx.save();
                ctx.fillStyle = 'rgba(20,33,61,0.10)';
                ctx.fillRect(area.left + step * selectedMonth, area.top, step, area.bottom - area.top);
                ctx.restore();
            }
        };

        new Chart(financeCanvas, {
            type: 'bar',
            data: {
                labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: @json($monthlyIncome),
                        backgroundColor: C.income,
                        borderRadius: 6,
                        borderSkipped: false,
                        maxBarThickness: 22
                    },
                    {
                        label: 'Pengeluaran',
                        data: @json($monthlyExpense),
                        backgroundColor: C.expense,
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
                            color: C.text,
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
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: C.text,
                            autoSkip: false,
                            maxRotation: 0,
                            font: (ctx) => ({
                                size: window.innerWidth < 640 ? 10 : 12,
                                weight: ctx.index === selectedMonth ? 'bold' : 'normal'
                            })
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: C.grid },
                        border: { display: false },
                        ticks: {
                            color: C.text,
                            callback: (v) => Number(v).toLocaleString('id-ID', { notation: 'compact' })
                        }
                    }
                }
            },
            plugins: [monthBand]
        });
    }

    /* ---------- Doughnut chart: pengeluaran per kategori ---------- */
    const categoryCanvas = document.getElementById('categoryChart');

    if (categoryCanvas) {
        const labels = @json($catRows->pluck('label')->values());
        const amounts = @json($catRows->pluck('amount')->values());
        const colors = @json($catRows->pluck('color')->values());
        const total = @json($sumCat);

        // total pengeluaran di tengah lingkaran
        const centerText = {
            id: 'centerText',
            afterDraw(chart) {
                const area = chart.chartArea;
                const x = (area.left + area.right) / 2;
                const y = (area.top + area.bottom) / 2;
                const inner = (area.right - area.left) * 0.68;
                const txt = rupiah(total);
                const ctx = chart.ctx;

                ctx.save();
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';

                ctx.fillStyle = '#54421A';
                ctx.font = '500 12px Figtree, sans-serif';
                ctx.fillText('Total', x, y - 14);

                let size = 18;
                do {
                    ctx.font = '700 ' + size + 'px Figtree, sans-serif';
                    size--;
                } while (ctx.measureText(txt).width > inner * 0.88 && size > 10);

                ctx.fillStyle = '#2A210B';
                ctx.fillText(txt, x, y + 8);
                ctx.restore();
            }
        };

        new Chart(categoryCanvas, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: amounts,
                    backgroundColor: colors,
                    borderWidth: amounts.length > 1 ? 3 : 0,
                    borderColor: C.surface,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: (ctx) => ctx.label + ': ' + rupiah(ctx.raw) }
                    },
                    datalabels: { display: false }
                }
            },
            plugins: [centerText]
        });
    }
});
</script>
</x-app-layout>