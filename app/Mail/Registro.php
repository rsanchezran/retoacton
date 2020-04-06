<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Registro extends Mailable
{

    use Queueable, SerializesModels;

    public $usuario;
    public $mensaje;

    public function __construct($usuario, $mensaje)
    {
        $this->usuario = $usuario;
        $this->mensaje = $mensaje; //subject, mensaje
    }

    public function build()
    {
        $send = $this->from(env('MAIL_ADDRESS'), 'Reto Acton')
            ->subject($this->mensaje->subject)
            ->to($this->usuario->email);
        $this->usuario->pass = $this->mensaje->pass;
        $send->view('correo.registro', ['usuario' => $this->usuario, 'email'=>$this->usuario->email])
            ->text('correo.registro_plano', ['usuario' => $this->usuario]);
        return $send;
    }
}
