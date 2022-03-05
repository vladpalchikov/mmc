<?php

namespace MMC\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class ErrorToSlack extends Notification
{
    use Queueable;
    public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $data = $this->data;
        return (new SlackMessage)
                    ->error()
                    ->content($data['error'])
                    ->attachment(function ($attachment) use ($data) {
                        $attachment
                           ->fields([
                                'Пользователь' => $data['user'],
                                'Роль' => $data['role'],
                                'URL' => $data['url'],
                                'Время возникновения' => date('d.m.Y H:i:s'),
                            ]);
                    });
    }
}
