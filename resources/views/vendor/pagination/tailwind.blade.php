@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman" class="flex flex-col items-center gap-3 sm:flex-row sm:justify-between">

        <p class="text-sm text-[#54421A]">
            Menampilkan
            <span class="font-semibold text-[#2A210B]">{{ $paginator->firstItem() }}</span>
            -
            <span class="font-semibold text-[#2A210B]">{{ $paginator->lastItem() }}</span>
            dari
            <span class="font-semibold text-[#2A210B]">{{ $paginator->total() }}</span>
            data
        </p>

        {{-- ===== HP: Sebelumnya / Berikutnya (tombol besar, mudah diketuk) ===== --}}
        <div class="flex w-full items-center justify-between gap-3 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex cursor-not-allowed items-center gap-2 rounded-xl border border-[#BE9A35]/60 bg-[#F4E4A6]/60 px-4 py-2.5 text-sm font-semibold text-[#54421A]/60">
                    <x-icon name="arrow-left" class="h-4 w-4" /> Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="inline-flex items-center gap-2 rounded-xl border border-[#A8841F] bg-[#F4E4A6] px-4 py-2.5 text-sm font-semibold text-[#2A210B] transition hover:bg-[#E8D17F] focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40">
                    <x-icon name="arrow-left" class="h-4 w-4" /> Sebelumnya
                </a>
            @endif

            <span class="text-sm font-medium text-[#43340F]">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="inline-flex items-center gap-2 rounded-xl border border-[#A8841F] bg-[#F4E4A6] px-4 py-2.5 text-sm font-semibold text-[#2A210B] transition hover:bg-[#E8D17F] focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40">
                    Berikutnya <x-icon name="arrow-right" class="h-4 w-4" />
                </a>
            @else
                <span class="inline-flex cursor-not-allowed items-center gap-2 rounded-xl border border-[#BE9A35]/60 bg-[#F4E4A6]/60 px-4 py-2.5 text-sm font-semibold text-[#54421A]/60">
                    Berikutnya <x-icon name="arrow-right" class="h-4 w-4" />
                </span>
            @endif
        </div>

        {{-- ===== Tablet & desktop: nomor halaman ===== --}}
        <div class="hidden items-center gap-1.5 sm:flex">
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-10 w-10 cursor-not-allowed items-center justify-center rounded-lg border border-[#BE9A35]/60 bg-[#F4E4A6]/60 text-[#54421A]/60" aria-disabled="true">
                    <x-icon name="arrow-left" class="h-4 w-4" />
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya"
                   class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-[#A8841F] bg-[#F4E4A6] text-[#2A210B] transition hover:bg-[#E8D17F] focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40">
                    <x-icon name="arrow-left" class="h-4 w-4" />
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-1.5 text-sm text-[#54421A]">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                  class="inline-flex h-10 min-w-[2.5rem] items-center justify-center rounded-lg bg-gradient-to-r from-[#14213D] to-[#233763] px-3 text-sm font-semibold text-[#F0D27A]">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" aria-label="Ke halaman {{ $page }}"
                               class="inline-flex h-10 min-w-[2.5rem] items-center justify-center rounded-lg border border-[#A8841F] bg-[#F4E4A6] px-3 text-sm font-semibold text-[#2A210B] transition hover:bg-[#E8D17F] focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya"
                   class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-[#A8841F] bg-[#F4E4A6] text-[#2A210B] transition hover:bg-[#E8D17F] focus:outline-none focus:ring-2 focus:ring-[#B8892B]/40">
                    <x-icon name="arrow-right" class="h-4 w-4" />
                </a>
            @else
                <span class="inline-flex h-10 w-10 cursor-not-allowed items-center justify-center rounded-lg border border-[#BE9A35]/60 bg-[#F4E4A6]/60 text-[#54421A]/60" aria-disabled="true">
                    <x-icon name="arrow-right" class="h-4 w-4" />
                </span>
            @endif
        </div>
    </nav>
@endif