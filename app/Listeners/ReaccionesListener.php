<?php

namespace App\Listeners;

use App\Notifications\ReaccionesNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\MiAlbum;

class ReaccionesListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $album = MiAlbum::where('id', $event->interaccion->album_id)->first();
        User::where('id', $album->usuario_id)
            ->each(function (User $user) use($event){
                Notification::send($user, new ReaccionesNotification($event->interaccion));
            });
    }
}
