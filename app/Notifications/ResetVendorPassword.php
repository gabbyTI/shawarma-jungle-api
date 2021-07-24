<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as NotificationsResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetVendorPassword extends NotificationsResetPassword
{

    public function toMail($notifiable)
    {
        $url = url(config('app.vendor_url') . 'vendor/password/reset/' . $this->token) . '?email=' . urlencode($notifiable->email);
        return (new MailMessage)
            ->subject(Lang::get('Reset Password Notification'))
            ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::get('Reset Password'), $url)
            ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.vendors.expire')]))
            ->line(Lang::get('If you did not request a password reset, no further action is required.'));
    }
}
