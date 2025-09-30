<?php

namespace App\Notifications;

use App\Models\ResourceApplication; 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResourceStatusUpdated extends Notification
{
    use Queueable;

    // 💡 Changed properties to accept the full application model and notes
    public ResourceApplication $application;
    protected ?string $notes;

    /**
     * Create a new notification instance.
     *
     * @param ResourceApplication $application // Accepts the model instance
     * @param string|null $notes // Accepts the optional notes/comments
     */
    public function __construct(ResourceApplication $application, ?string $notes = null)
    {
        $this->application = $application;
        $this->notes = $notes;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail']; 
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $statusLabel = $this->application->getStatusLabel(); // Use the model helper for a clean label
        $resourceName = $this->application->resource->name;
        $subject = "Your Resource Application for {$resourceName} Has Been {$statusLabel}";

        // Using the simple MailMessage structure for robustness
        $message = (new MailMessage)
            ->subject($subject)
            ->line("The status of your application for **{$resourceName}** has been updated to **{$statusLabel}**.");

        // Conditionally display notes/comments
        if ($this->notes) {
            $message->line("---")
                    ->line("Admin Notes/Comments:")
                    ->line($this->notes)
                    ->line("---");
        }
        
        // Add a Call to Action
        $message->action('View Application', url('/applications/' . $this->application->id));

        return $message;

        /*
        // If you still prefer the custom view, uncomment and use this structure instead:
        return (new MailMessage)
             ->subject($subject)
             ->view('mail.applications.application_status_updated', [ 
                 'application' => $this->application,
                 'status' => $statusLabel,
                 'comments' => $this->notes, 
                 'notifiable' => $notifiable,
             ]);
        */
    }

    /**
     * Get the array representation of the notification. (For database notifications)
     */
    public function toArray($notifiable)
    {
        return [
            'application_id' => $this->application->id,
            'resource_name' => $this->application->resource->name,
            'status' => $this->application->status,
            'notes' => $this->notes,
        ];
    }
}