@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#E3BE5B] text-sm font-semibold leading-5 text-[#F0D27A] focus:outline-none focus:border-[#F7E4A0] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-[#B9A56B] hover:text-[#F0D27A] hover:border-[#E3BE5B]/50 focus:outline-none focus:text-[#F0D27A] focus:border-[#E3BE5B]/50 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>