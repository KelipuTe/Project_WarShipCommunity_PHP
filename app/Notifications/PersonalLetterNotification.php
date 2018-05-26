<?php

namespace App\Notifications;

use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * 这个类记录用户发送私信的站内信信息
 * Class PersonalLetterNotification
 * @package App\Notifications
 */
class PersonalLetterNotification extends Notification
{
    use Queueable;

    protected $personalLetter_id;

    /**
     * Create a new notification instance.
     * @param $id [personalLetter_id]
     */
    public function __construct($id)
    {
        $this->personalLetter_id = $id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable){
        return [
            'from_user_id' => Auth::user()->id, // 发起私信的用户 id
            'from_user_username' => Auth::user()->username, // 发起私信的用户 username
            'personalLetter_id' => $this->personalLetter_id // 私信对应的 id
        ];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
            //
        ];
    }
}
