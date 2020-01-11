<?php

namespace App\Listeners;

use App\Events\MailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class MailEventListener implements ShouldQueue
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
    public function handle(MailEvent $event)
    {
        $empleados = $event->datos;
        $class = "App\Mail\\".$event->call;

        foreach ($empleados as  $em){
            Mail::queue(new $class($em, $event->mensaje));
        }
    }

    public function quitar_tildes($cadena)
    {
        $no_permitidas = array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'À', 'Ã', 'Ì', 'Ò', 'Ù', 'Ã™', 'Ã ', 'Ã¨', 'Ã¬', 'Ã²', 'Ã¹',
            'ç', 'Ç', 'Ã¢', 'ê', 'Ã®', 'Ã´', 'Ã»', 'Ã‚', 'ÃŠ', 'ÃŽ', 'Ã”', 'Ã›', 'ü', 'Ã¶', 'Ã–', 'Ã¯', 'Ã¤', '«', 'Ò', 'Ã', 'Ã„', 'Ã‹');
        $permitidas = array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N', 'A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'c', 'C',
            'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'u', 'o', 'O', 'i', 'a', 'e', 'U', 'I', 'A', 'E');
        $texto = str_replace($no_permitidas, $permitidas, $cadena);

        return $texto;
    }
}
