<?php

namespace App\Listeners;

use App\Notifications\AmistadesNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class SeguirListener
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
        User::where('id', $event->amistades->usuario_amigo_id)
            ->each(function (User $user) use($event){
                Notification::send($user, new AmistadesNotification($event->amistades));
            });
    }
}
