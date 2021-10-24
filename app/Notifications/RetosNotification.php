<?php

namespace App\Notifications;

use Illuminate\Support\Collection;
use App\Retos;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RetosNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Retos $retos)
    {
        $this->retos = $retos;
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
            'id' => $this->retos->id,
            'descripcion' => $this->retos->descripcion,
            'usuario_reta_id' => $this->retos->usuario_reta_id,
            'usuario_retador_id' => $this->retos->usuario_retador_id,
            'coins' => $this->retos->coins,
            'publico' => $this->retos->publico,
            'video' => $this->retos->video,
            'aceptado' => $this->retos->aceptado,
            'updated' => $this->retos->updated_at,
        ];
    }
}
