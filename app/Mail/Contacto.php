<?php

namespace App\Mail;

use App\Dia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contacto extends Mailable
{

    use Queueable, SerializesModels;

    public $contacto;

    public function __construct($contacto)
    {
        $this->contacto = $contacto;
    }

    public function build()
    {
        if ($this->contacto->etapa == 1) {
            $subject = "Hoy es el momento";
            $boton = "MAS INFORMACIÓN";
        } elseif ($this->contacto->etapa == 2) {
            $subject = "Resultado comprobable";
            $boton = "VER SIMULADOR";
        } else {
            $subject = "Testimonios reales";
            $boton = "VER TESTIMONIOS";
        }
        $send = $this->from(env('MAIL_ADDRESS'), 'Acton')
            ->subject($subject)
            ->to($this->contacto->email);
        $send->view('correo.contacto', ['contacto' => $this->contacto, 'email' => $this->contacto->email, 'boton'=>$boton])
            ->text('correo.contacto_plano', ['contacto' => $this->contacto, 'boton'=>$boton]);
        return $send;
    }
}
