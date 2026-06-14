<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPostCommentNotification extends Notification
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
            ->subject('Nouveau commentaire sur « '.$this->comment->post->titre.' »')
            ->greeting('Bonjour '.$notifiable->name.',')
            ->line($this->comment->user->name.' a commenté ton article.')
            ->line('« '.\Illuminate\Support\Str::limit($this->comment->contenu, 200).' »')
            ->action('Voir l\'article', route('posts.show', $this->comment->post));
    }
}
