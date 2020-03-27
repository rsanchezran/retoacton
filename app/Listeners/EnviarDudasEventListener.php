<?php

namespace App\Listeners;

use App\Events\EnviarCorreosEvent;
use App\Events\EnviarDudasEvent;
use App\Events\MailEvent;
use App\Mail\Contacto;
use App\Mail\Contactos;
use App\Mail\Duda;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnviarDudasEventListener implements ShouldQueue
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
    public function handle(EnviarDudasEvent $event)
    {
        try{
        Mail::queue(new Duda($event->contacto, $event->tipo));
        }catch (\Exception $e){
            Log::error($e->getMessage());
        }
    }
}
