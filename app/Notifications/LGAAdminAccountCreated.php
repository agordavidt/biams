<?php




namespace App\Notifications;

use App\Models\User;
use App\Models\LGA;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LGAAdminAccountCreated extends Notification
{
    use Queueable;

    protected $user;
    protected $password;
    protected $administrativeUnit;
    protected $administrativeUnitName;

    /**
     * Create a new notification instance.
     * Flexible for LGA, Department, or Agency admins
     *
     * @param User $user
     * @param string $password
     * @param mixed $administrativeUnit (LGA, Department, or Agency model)
     * @param string $administrativeUnitName
     */
    public function __construct(User $user, string $password, $administrativeUnit = null, string $administrativeUnitName = '')
    {
        $this->user = $user;
        $this->password = $password;
        $this->administrativeUnit = $administrativeUnit;
        $this->administrativeUnitName = $administrativeUnitName;
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
            ->subject('Administrator Account Created - BSIADAMS')
            ->view('mail.accounts.admin_created', [
                'user' => $this->user,
                'password' => $this->password,
                'administrativeUnit' => $this->administrativeUnit,
                'administrativeUnitName' => $this->administrativeUnitName,
                'roleName' => $this->user->roles->first()->name ?? 'Administrator',
                'loginUrl' => url('/login'),
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'Administrator account created successfully',
            'user_id' => $this->user->id,
            'role' => $this->user->roles->first()->name ?? 'N/A',
        ];
    }
}