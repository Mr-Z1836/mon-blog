<?php

namespace App\Support;

use App\Models\Comment;
use App\Models\User;
use App\Notifications\CommentMentionNotification;
use App\Notifications\NewPostCommentNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CommentNotifier
{
    /**
     * @param  Collection<int, User>  $mentionedUsers
     */
    public static function send(Comment $comment, Collection $mentionedUsers): void
    {
        if (! config('blog.comments_notify_author', true)) {
            return;
        }

        $comment->loadMissing(['post.author', 'user']);
        $commenterId = $comment->user_id;

        $author = $comment->post->author;

        if ($author && $author->id !== $commenterId) {
            self::notifyUser($author, new NewPostCommentNotification($comment), $comment);
        }

        foreach ($mentionedUsers as $mentionedUser) {
            if ($mentionedUser->id === $commenterId) {
                continue;
            }

            if ($author && $mentionedUser->id === $author->id) {
                continue;
            }

            self::notifyUser($mentionedUser, new CommentMentionNotification($comment), $comment);
        }
    }

    private static function notifyUser(User $user, object $notification, Comment $comment): void
    {
        try {
            $user->notify($notification);
        } catch (\Throwable $exception) {
            Log::warning('Comment notification failed.', [
                'comment_id' => $comment->id,
                'recipient_id' => $user->id,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
