<?php

namespace App\Mail;

use App\Dia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contactos extends Mailable
{

    use Queueable, SerializesModels;

    public $contactos;

    public function __construct($contactos)
    {
        $this->contactos = $contactos;
    }

    public function build()
    {
        $send = $this->from(env('MAIL_ADDRESS'), 'Acton')
            ->subject("Reto Acton")
            ->to($this->contactos->pluck('email'));
        $send->view('correo.contacto', ['etapa' => $this->contactos->first()->etapa, 'email'=>''])
            ->text('correo.contacto_plano', ['etapa' => $this->contactos->first()->etapa]);
        return $send;
    }
}