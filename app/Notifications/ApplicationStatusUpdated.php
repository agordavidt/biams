<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdated extends Notification
{
    use Queueable;

    public $status; // Declare the $status property
    protected $comments;

    /**
     * Create a new notification instance.
     *
     * @param string $status
     */
    public function __construct($status, $comments = null)
    {
        $this->status = $status;
        $this->comments = $comments;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject('Agricultural Practice Application Update')
            ->line('Your application has been ' . $this->status . '.');

        if ($this->status === 'rejected' && $this->comments) {
            $message->line('Reason for rejection:')
                   ->line($this->comments);
        }

        return $message;
    }
    

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
