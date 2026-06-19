<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentMentionNotification extends Notification
{
    use Queueable;

    public function __construct(public Comment $comment) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->comment->loadMissing(['post', 'user']);

        return (new MailMessage)
            ->subject($this->comment->user->publicHandle().' t\'a mentionné sur Built in Benin')
            ->greeting('Bonjour '.$notifiable->publicHandle().',')
            ->line($this->comment->user->publicHandle().' t\'a mentionné dans un commentaire.')
            ->line('« '.\Illuminate\Support\Str::limit($this->comment->contenu, 200).' »')
            ->action('Voir le commentaire', route('posts.show', $this->comment->post));
    }
}
