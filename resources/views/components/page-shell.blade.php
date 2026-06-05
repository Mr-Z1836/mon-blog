@props(['title'])

<div class="mx-auto max-w-3xl p-6">
    <div class="glass-card p-8 md:p-10 space-y-6">
        {{ $slot }}
    </div>
</div>
