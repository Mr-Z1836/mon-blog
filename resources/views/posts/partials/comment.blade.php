@php
    $replyPlaceholder = 'Répondre à '.$comment->user->publicHandle().'…';
    $isOwner = auth()->id() === $comment->user_id;
    $showEditForm = $isOwner && $errors->any() && (int) old('_comment_id') === $comment->id;
    $showReplyForm = ! $isOwner && $errors->any() && (int) old('parent_id') === $comment->id;
@endphp

<div
    class="{{ $depth > 0 ? 'ms-6 border-s-2 border-slate-200 ps-4 dark:border-slate-700' : '' }} border-b border-slate-200 pb-3 dark:border-slate-700"
    data-comment-id="{{ $comment->id }}"
>
    <p class="text-sm font-medium text-slate-900 dark:text-slate-100">
        <span class="text-brand-red">{{ $comment->user->publicHandle() }}</span>
        <span class="font-normal text-slate-500">· {{ $comment->created_at->locale(app()->getLocale())->diffForHumans() }}</span>
        @if ($comment->mentionedUser)
            <span class="text-brand-red"> → {{ $comment->mentionedUser->publicHandle() }}</span>
        @endif
    </p>
    <p class="comment-body mt-1 text-sm text-slate-600 dark:text-slate-400">{!! \App\Support\CommentMentions::formatContent($comment->contenu) !!}</p>
    <div class="mt-2 flex flex-wrap items-center gap-x-1.5 text-xs">
        @auth
            @if ($isOwner)
                <details class="inline reply-details" name="comment-edit" @if ($showEditForm) open @endif>
                    <summary class="cursor-pointer list-none text-brand-red marker:content-none [&::-webkit-details-marker]:hidden">
                        Modifier
                    </summary>
                    <form method="POST" action="{{ route('posts.comments.update', [$post, $comment]) }}" class="mt-2 max-w-md space-y-2 rounded-lg border border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-900">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="_comment_id" value="{{ $comment->id }}">
                        <textarea
                            name="comment_content"
                            rows="3"
                            required
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-800 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                        >{{ $showEditForm ? old('comment_content') : $comment->contenu }}</textarea>
                        <div class="flex items-center gap-2">
                            <button type="submit" class="rounded-lg bg-brand-green px-3 py-1.5 text-xs font-semibold text-white">
                                Enregistrer
                            </button>
                            <button type="reset" class="text-xs text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200" onclick="this.closest('details').removeAttribute('open')">
                                Annuler
                            </button>
                        </div>
                    </form>
                </details>
                <span class="text-slate-400" aria-hidden="true">·</span>
                <form
                    method="POST"
                    action="{{ route('posts.comments.destroy', [$post, $comment]) }}"
                    class="inline"
                    onsubmit="return confirm('Supprimer ce commentaire ?')"
                >
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-brand-red">Supprimer</button>
                </form>
            @else
                <details class="inline reply-details" name="comment-reply" @if ($showReplyForm) open @endif>
                    <summary class="cursor-pointer list-none text-brand-red marker:content-none [&::-webkit-details-marker]:hidden">
                        Répondre
                    </summary>
                    <form method="POST" action="{{ route('posts.comments.store', $post) }}" class="mt-2 max-w-md space-y-2 rounded-lg border border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-900">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <textarea
                            name="comment_content"
                            rows="3"
                            placeholder="{{ $replyPlaceholder }}"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-800 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                        >{{ $showReplyForm ? old('comment_content') : '' }}</textarea>
                        <div class="flex items-center gap-2">
                            <button type="submit" class="rounded-lg bg-brand-green px-3 py-1.5 text-xs font-semibold text-white">
                                Envoyer
                            </button>
                            <button type="button" class="text-xs text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200" onclick="this.closest('details').removeAttribute('open')">
                                Annuler
                            </button>
                        </div>
                    </form>
                </details>
                <span class="text-slate-400" aria-hidden="true">·</span>
                <details class="group inline">
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
            @endif
        @else
            <details class="group inline">
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
        @endauth
    </div>

    @foreach ($comment->children as $child)
        @include('posts.partials.comment', ['comment' => $child, 'post' => $post, 'depth' => $depth + 1])
    @endforeach
</div>
