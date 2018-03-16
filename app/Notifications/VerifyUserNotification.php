<?php

namespace App\Notifications;

use App\User;
use App\VerifyUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class VerifyUserNotification
 * @package App\Notifications
 */
class VerifyUserNotification extends Notification
{

    use Queueable;

    /**
     * @var User $user
     */
    protected $user;

    /**
     * @var VerifyUser $verifyUser
     */
    protected $verifyUser;

    /**
     * VerifyUser constructor.
     */
    public function __construct($user, $verifyUser)
    {
        $this->user = $user;
        $this->verifyUser = $verifyUser;
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Welcome to the site ' . $this->user->name . '.')
            ->line('Your registered email is ' . $this->user->email . '. Please click on the below link to verify your email account')
            ->action('Verify Email', url('user/verify/' . $this->verifyUser->token));
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
