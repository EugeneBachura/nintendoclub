@props(['currentExp' => 0, 'requiredExp' => 100])

@php
    // Проверка значений текущего опыта и требуемого опыта для предотвращения деления на ноль
    if (!$currentExp) {
        $currentExp = 0;
    }
    if (!$requiredExp || $requiredExp < $currentExp) {
        $requiredExp = $currentExp;
    }

    // Определение строки отображения опыта: если опыт равен требуемому, строка пустая
    $displayExp = $currentExp === $requiredExp ? '' : " - $currentExp/$requiredExp";

    // Вычисление процента заполнения полосы опыта
    $progressWidth = ($currentExp / $requiredExp) * 100;
@endphp

<div class="relative bg-gray-300 rounded-full h-6">
    {{-- Полоса заполнения опыта --}}
    <div class="absolute left-0 top-0 h-full bg-accent rounded-full" style="width: {{ $progressWidth }}%;"></div>

    {{-- Текстовый вывод уровня и текущего опыта --}}
    <span class="absolute w-full text-center text-black text-sm font-bold leading-6">
        {{ $slot }} {{ $displayExp }}
    </span>
</div>
