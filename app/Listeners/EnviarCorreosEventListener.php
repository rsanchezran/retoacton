<?php

namespace App\Listeners;

use App\Events\EnviarCorreosEvent;
use App\Events\MailEvent;
use App\Mail\Contacto;
use App\Mail\Contactos;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class EnviarCorreosEventListener implements ShouldQueue
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
     * @param  MailEvent $event
     * @return void
     */
    public function handle(EnviarCorreosEvent $event)
    {
        foreach ($event->contactos as $contacto){
            try{
                Mail::queue(new Contacto($contacto));
            }catch (\Exception $e){
            }
        }
    }
}
