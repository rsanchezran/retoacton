<?php

namespace App\Mail;

use App\Dia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Duda extends Mailable
{

    use Queueable, SerializesModels;

    public $contacto;
    public $tipo;

    public function __construct($contacto, $tipo)
    {
        $this->contacto = $contacto;
        $this->tipo = $tipo;
    }

    public function build()
    {
        $correo = env("EMAIL_DUDAS");
        if ($this->tipo=='contacto'){
            $correo = env("EMAIL_PROSPECTO");
        }
        $send = $this->from(env('MAIL_ADDRESS'), 'Reto Acton')
            ->subject("Duda de cliente")
            ->to($correo);
        $send->view('correo.duda', ['contacto' => $this->contacto, 'email'=>$this->contacto->email])
            ->text('correo.duda_plano', ['contacto' => $this->contacto,'email'=>$this->contacto->email]);
        return $send;
    }
}
