<?php

namespace App\Notifications;

use App\Mail\VerifyEmailMail;
use Illuminate\Auth\Notifications\VerifyEmail;

class VerifyEmailNotification extends VerifyEmail
{
    public function toMail(object $notifiable): VerifyEmailMail
    {
        return new VerifyEmailMail($this->verificationUrl($notifiable));
    }
}
