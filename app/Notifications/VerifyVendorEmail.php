<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail as NotificationsVerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyVendorEmail extends NotificationsVerifyEmail
{
    protected function verificationUrl($notifiable)
    {
        $appUrl = config('app.vendor_url', config('app.url'));

        $url = URL::temporarySignedRoute(
            'verification.verify.vendor',
            Carbon::now()->addMinutes(60),
            ['vendor' => $notifiable->id,]
        );

        return str_replace(url('/api'), $appUrl, $url);
    }
}
