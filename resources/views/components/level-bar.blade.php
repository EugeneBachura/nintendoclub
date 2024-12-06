@props(['currentExp' => 0, 'requiredExp' => 100])

@php
    if (!$currentExp) {
        $currentExp = 0;
    }
    if (!$requiredExp || $requiredExp < $currentExp) {
        $requiredExp = $currentExp;
    }

    $displayExp = $currentExp === $requiredExp ? '' : " - $currentExp/$requiredExp";
    $progressWidth = ($currentExp / $requiredExp) * 100;
@endphp

<div class="relative bg-gray-300 rounded-full h-6">
    <div class="absolute left-0 top-0 h-full bg-accent rounded-full" style="width: {{ $progressWidth }}%;"></div>

    <span class="absolute w-full text-center text-black text-sm font-bold leading-6">
        {{ $slot }} {{ $displayExp }}
    </span>
</div>
