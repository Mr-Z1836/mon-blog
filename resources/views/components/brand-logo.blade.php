@props([
    'size' => 'md',
])

@php
    $sizeClasses = match ($size) {
        'sm' => 'text-lg',
        'lg' => 'text-3xl md:text-4xl',
        'xl' => 'text-4xl md:text-6xl',
        default => 'text-xl md:text-2xl',
    };
@endphp

<span {{ $attributes->merge(['class' => "font-black tracking-tight {$sizeClasses}"]) }}>
    <span class="text-brand-green">Built</span><span class="text-brand-yellow"> in </span><span class="text-brand-red">Benin</span>
</span>
