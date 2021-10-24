<?php

namespace App\Notifications;

use App\InteraccionAlbum;
use App\MiAlbum;
use Illuminate\Support\Collection;
use App\ComprasCoins;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReaccionesNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(InteraccionAlbum $interaccion)
    {
        $this->interaccion = $interaccion;
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
        $album = MiAlbum::where('id', $this->interaccion->album_id)->first();
        return [
            'id' => $this->interaccion->id,
            'usuario_like_id' => $this->interaccion->usuario_like_id,
            'dinero_acton' => $this->interaccion->dinero_acton,
            'tipo_like' => $this->interaccion->tipo_like,
            'album_id' => $this->interaccion->album_id,
            'imagen' => $album->imagen,
        ];
    }
}
