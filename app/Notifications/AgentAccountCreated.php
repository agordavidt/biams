<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AgentAccountCreated extends Notification
{
    use Queueable;

    protected $user;
    protected $password;
    protected $roleName;

    /**
     * Create a new notification instance.
     * Reusable for all agent/admin types
     *
     * @param User $user
     * @param string $password
     * @param string $roleName (e.g., "LGA Admin", "Enrollment Agent", "Distribution Agent")
     */
    public function __construct(User $user, string $password, string $roleName)
    {
        $this->user = $user;
        $this->password = $password;
        $this->roleName = $roleName;
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
        return (new MailMessage)
            ->subject('Account Created - BSIADAMS')
            ->view('mail.accounts.agent_created', [
                'user' => $this->user,
                'password' => $this->password,
                'roleName' => $this->roleName,
                'loginUrl' => url('/login'),
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'Account created successfully',
            'user_id' => $this->user->id,
            'role' => $this->roleName,
        ];
    }
}