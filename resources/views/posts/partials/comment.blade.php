<div class="{{ $depth > 0 ? 'ms-6 border-s-2 border-slate-200 ps-4 dark:border-slate-700' : '' }} border-b border-slate-200 pb-3 dark:border-slate-700">
    <p class="text-sm font-medium text-slate-900 dark:text-slate-100">
        @if ($comment->user->username)
            <span class="text-brand-red">@{{ $comment->user->username }}</span>
        @else
            {{ $comment->user->name }}
        @endif
        @if ($comment->mentionedUser)
            <span class="text-brand-red">→ @{{ $comment->mentionedUser->username ?? $comment->mentionedUser->name }}</span>
        @endif
    </p>
    <p class="text-sm text-slate-600 dark:text-slate-400">{!! \App\Support\CommentMentions::formatContent($comment->contenu) !!}</p>
    <div class="mt-2 flex flex-wrap items-start gap-3 text-xs">
        @auth
            <button type="button" class="text-brand-red reply-btn" data-parent="{{ $comment->id }}">Répondre</button>
        @endauth
        <details class="group">
            <summary class="cursor-pointer list-none text-brand-red marker:content-none [&::-webkit-details-marker]:hidden">
                Signaler
            </summary>
            <form method="POST" action="{{ route('posts.comments.report', [$post, $comment]) }}" class="mt-2 max-w-md space-y-2 rounded-lg border border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-900">
                @csrf
                <label for="report-reason-{{ $comment->id }}" class="block text-xs font-medium text-slate-700 dark:text-slate-300">
                    Pourquoi signales-tu ce commentaire ?
                </label>
                <textarea
                    id="report-reason-{{ $comment->id }}"
                    name="reason"
                    rows="3"
                    required
                    minlength="10"
                    maxlength="1000"
                    placeholder="Ex. : insultes, spam, hors sujet…"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-800 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                >{{ old('reason') }}</textarea>
                <button type="submit" class="rounded-lg bg-brand-red px-3 py-1.5 text-xs font-semibold text-white">
                    Envoyer le signalement
                </button>
            </form>
        </details>
    </div>
    @foreach ($comment->children as $child)
        @include('posts.partials.comment', ['comment' => $child, 'post' => $post, 'depth' => $depth + 1])
    @endforeach
</div>
