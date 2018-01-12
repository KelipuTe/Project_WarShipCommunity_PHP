<?php

namespace App\Notifications;

use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserUserFollowNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     */
    public function __construct()
    {
        //
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
        /*
         * database 表示使用数据库站内信通知方式
         * mail 表示使用邮件通知方式
         */
        //return ['database','mail'];
    }

    /**
     * notification 需要执行的函数
     * toDatabase() 的函数名是根据 via() 函数中的 database 起的名字
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable){
        /*
         * 写入数据库中的数据
         */
        return [
            'follower_id' => Auth::user()->id,
            'follower' => Auth::user()->username
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    /*public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }*/

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
