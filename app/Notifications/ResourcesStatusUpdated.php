<?php

namespace App\Notifications;

use App\Models\ResourceApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResourcesStatusUpdated extends Notification implements ShouldQueue 
{
    use Queueable;

    protected $application;
    protected $note;

    /**
     * Create a new notification instance.
     *
     * @param ResourceApplication $application
     * @param string|null $note
     * @return void
     */
    public function __construct(ResourceApplication $application, ?string $note = null)
    {
        $this->application = $application;
        $this->note = $note;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Resource Application Status Update')
            ->view('mail.applications.status-updated', [
                'application' => $this->application,
                'note' => $this->note
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'application_id' => $this->application->id,
            'resource_id' => $this->application->resource_id,
            'resource_name' => $this->application->resource->name,
            'status' => $this->application->status,
            'note' => $this->note,
            'updated_at' => $this->application->updated_at->toIso8601String()
        ];
    }
}