<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnviarFicha extends Mailable
{

    use Queueable, SerializesModels;

    public $contacto;
    public $orden;

    public function __construct($contacto, $orden)
    {
        $this->contacto = $contacto;
        $this->orden = $orden;
    }

    public function build()
    {

        $send = $this->from(env('MAIL_ADDRESS'), 'Acton')
            ->subject("Ficha para pago en " . $this->orden->origen)
            ->to($this->contacto->email);
        $send->view('correo.ficha', ['contacto' => $this->contacto, 'orden' => $this->orden, 'email' => $this->contacto->email])
            ->text('correo.ficha_plano', ['orden' => $this->orden]);
        return $send;
    }
}