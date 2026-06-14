@php
    $shareUrl = urlencode(route('posts.show', $post));
    $shareTitle = urlencode($post->titre);
@endphp
<div class="flex flex-wrap items-center gap-2 pt-2">
    <span class="text-xs text-slate-500">Partager :</span>
    <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener" class="chip">Twitter</a>
    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener" class="chip">LinkedIn</a>
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener" class="chip">Facebook</a>
    <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" rel="noopener" class="chip">WhatsApp</a>
</div>
