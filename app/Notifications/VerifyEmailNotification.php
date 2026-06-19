<?php

namespace App\Notifications;

use App\Mail\VerifyEmailMail;
use Illuminate\Auth\Notifications\VerifyEmail;

class VerifyEmailNotification extends VerifyEmail
{
    /**
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new VerifyEmailMail($verificationUrl))
            ->to($notifiable->getEmailForVerification());
    }
}
