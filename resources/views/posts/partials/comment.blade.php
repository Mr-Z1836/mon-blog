<div class="{{ $depth > 0 ? 'ms-6 border-s-2 border-slate-200 ps-4 dark:border-slate-700' : '' }} border-b border-slate-200 pb-3 dark:border-slate-700">
    <p class="text-sm font-medium text-slate-900 dark:text-slate-100">
        {{ $comment->user->name }}
        @if ($comment->user->username)
            <span class="text-slate-500">@{{ $comment->user->username }}</span>
        @endif
        @if ($comment->mentionedUser)
            <span class="text-brand-red">→ @{{ $comment->mentionedUser->username ?? $comment->mentionedUser->name }}</span>
        @endif
    </p>
    <p class="text-sm text-slate-600 dark:text-slate-400">{!! \App\Support\CommentMentions::formatContent($comment->content) !!}</p>
    @auth
        <div class="mt-2 flex gap-3 text-xs">
            <button type="button" class="text-brand-red reply-btn" data-parent="{{ $comment->id }}">Répondre</button>
            <form method="POST" action="{{ route('posts.comments.report', [$post, $comment]) }}" class="inline" onsubmit="return confirm('Signaler ce commentaire ?')">
                @csrf
                <input type="hidden" name="reason" value="Contenu inapproprié signalé par un lecteur.">
                <button type="submit" class="text-brand-red">Signaler</button>
            </form>
        </div>
    @endauth
    @foreach ($comment->children as $child)
        @include('posts.partials.comment', ['comment' => $child, 'post' => $post, 'depth' => $depth + 1])
    @endforeach
</div>
