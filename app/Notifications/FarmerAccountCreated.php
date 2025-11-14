<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Farmer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FarmerAccountCreated extends Notification
{
    use Queueable;

    protected $user;
    protected $farmer;
    protected $password;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     * @param Farmer $farmer
     * @param string $password
     */
    public function __construct(User $user, Farmer $farmer, string $password)
    {
        $this->user = $user;
        $this->farmer = $farmer;
        $this->password = $password;
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
            ->subject('Farmer Account Approved - BSIADAMS')
            ->view('mail.accounts.farmer_created', [
                'user' => $this->user,
                'farmer' => $this->farmer,
                'password' => $this->password,
                'loginUrl' => url('/login'),
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'Farmer account created successfully',
            'farmer_id' => $this->farmer->id,
            'user_id' => $this->user->id,
        ];
    }
}