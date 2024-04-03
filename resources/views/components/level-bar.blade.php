@props(['currentExp', 'requiredExp'])
@php
    if (!$currentExp) $currentExp = 0;
    if (!$requiredExp) $requiredExp = $currentExp;
    if ($currentExp == $requiredExp) {
        $displayExp = "";
    }
     else {
        $displayExp = " - $currentExp/$requiredExp";
     }
@endphp
<div class="relative bg-gray-300 rounded-full h-6">
    <div class="absolute left-0 top-0 h-full bg-accent rounded-full" style="width: {{ ($currentExp/$requiredExp)*100 }}%;"></div>
    <span class="absolute w-full text-center text-black text-sm font-bold leading-6">
        {{ $slot }} {{ $displayExp }}
    </span>
</div>
