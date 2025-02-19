<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserOnboardedNotification extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Welcome to BSIADAMS - Your Profile is Approved!')
            ->view('mail.applications.user_onboarded', [
                'user' => $notifiable,
                'dashboardUrl' => url('/dashboard'),
                'name' => $notifiable->name
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'Your profile has been approved by the admin.',
            'action' => url('/dashboard'),
        ];
    }
}