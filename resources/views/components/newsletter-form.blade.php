@props(['source' => 'sidebar', 'compact' => false])

<div {{ $attributes->merge(['class' => 'glass-card p-5']) }}>
    <p class="text-xs uppercase tracking-[0.2em] text-brand-yellow">Newsletter</p>
    <h2 class="mt-2 text-lg font-bold text-slate-900 dark:text-slate-100">
        {{ $compact ? 'Reste informé' : 'Ne rate rien de Built in Benin' }}
    </h2>
    @unless ($compact)
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
            Articles tech, entrepreneuriat et opportunités pour la jeunesse africaine — directement dans ta boîte mail.
        </p>
    @endunless

    <form method="POST" action="{{ route('newsletter.store') }}" class="mt-4 space-y-3">
        @csrf
        <input type="hidden" name="source" value="{{ $source }}">
        <div>
            <label for="newsletter-email-{{ $source }}" class="sr-only">Adresse e-mail</label>
            <input
                id="newsletter-email-{{ $source }}"
                type="email"
                name="email"
                value="{{ old('source') === $source ? old('email') : '' }}"
                required
                placeholder="ton@email.com"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 placeholder:text-slate-400 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
            />
            @if (old('source') === $source)
                @error('email')
                    <p class="mt-1 text-xs text-brand-red">{{ $message }}</p>
                @enderror
            @endif
        </div>
        <button type="submit" class="btn-neon w-full text-sm">S'abonner</button>
    </form>
</div>
