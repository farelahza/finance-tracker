@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#E3BE5B] text-start text-base font-semibold text-[#F0D27A] bg-[#E3BE5B]/10 focus:outline-none focus:bg-[#E3BE5B]/20 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-[#B9A56B] hover:text-[#F0D27A] hover:bg-[#E3BE5B]/10 hover:border-[#E3BE5B]/50 focus:outline-none focus:text-[#F0D27A] focus:bg-[#E3BE5B]/10 focus:border-[#E3BE5B]/50 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>