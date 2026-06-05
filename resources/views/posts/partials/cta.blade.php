@if ($post->hasCta())
    <aside class="mt-8 rounded-2xl border border-brand-green/25 bg-gradient-to-br from-brand-red/5 via-brand-yellow/5 to-brand-green/10 p-6 text-center dark:from-brand-red/10 dark:via-brand-yellow/5 dark:to-brand-green/15">
        @if ($post->cta_title)
            <p class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ $post->cta_title }}</p>
        @endif
        <a href="{{ $post->cta_url }}" class="btn-neon mt-4 inline-flex" @if (\Illuminate\Support\Str::startsWith($post->cta_url, ['http://', 'https://'])) target="_blank" rel="noopener noreferrer" @endif>
            {{ $post->cta_text }}
        </a>
    </aside>
@endif
