@props(['percent' => 0, 'achieved' => false, 'height' => 'h-3', 'onDark' => false])

@php
    $track = $onDark ? 'bg-white/15' : 'bg-[#2A210B]/15';
    $fill  = $achieved
        ? 'bg-gradient-to-r from-[#176B50] to-[#2FA77F]'
        : ($onDark ? 'bg-gradient-to-r from-[#E3BE5B] to-[#F6E19A]' : 'bg-gradient-to-r from-[#14213D] to-[#3F5E8F]');
    $width = $percent > 0 ? max(2, min(100, (int) $percent)) : 0;
@endphp

<div class="w-full overflow-hidden rounded-full {{ $track }} {{ $height }}"
     role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ (int) $percent }}">
    <div class="h-full rounded-full transition-all duration-500 {{ $fill }}" style="width: {{ $width }}%"></div>
</div>