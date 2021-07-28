<?php

namespace App\Notifications;

use Illuminate\Support\Collection;
use App\MensajesDirectos;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MensajeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MensajesDirectos $mensajesDirectos)
    {
        $this->mensajeDirecto = $mensajesDirectos;
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
            'id' => $this->mensajeDirecto->id,
            'mensaje' => $this->mensajeDirecto->mensaje,
            'usuario_emisor_id' => $this->mensajeDirecto->usuario_emisor_id,
            'usuario_receptor_id' => $this->mensajeDirecto->usuario_receptor_id,
        ];
    }
}
