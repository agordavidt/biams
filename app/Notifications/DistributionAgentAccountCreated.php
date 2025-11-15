<?php




namespace App\Notifications;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DistributionAgentAccountCreated extends Notification
{
    use Queueable;

    protected $user;
    protected $vendor;
    protected $password;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     * @param Vendor $vendor
     * @param string $password
     */
    public function __construct(User $user, Vendor $vendor, string $password)
    {
        $this->user = $user;
        $this->vendor = $vendor;
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
            ->subject('Distribution Agent Account Created - BSSADAM')
            ->view('mail.accounts.distribution_agent_created', [
                'user' => $this->user,
                'vendor' => $this->vendor,
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
            'message' => 'Distribution Agent account created successfully',
            'user_id' => $this->user->id,
            'vendor_id' => $this->vendor->id,
        ];
    }
}