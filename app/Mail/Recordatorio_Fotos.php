<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Recordatorio_Fotos extends Mailable{
    use Queueable, SerializesModels;

    public $datos;
    public $subject;

    public function __construct($usuario, $datos_mensaje)
    {
        $this->datos = $usuario;
        $this->subject = $datos_mensaje;
    }

    public function build(){
        $send = $this->from(env('MAIL_ADDRESS'), 'Acton')
            ->subject($this->subject)
            ->to($this->datos->email);
        $send->view('correo.recordatorio_fotos', ['email'=>$this->datos->email])
            ->text('correo.recordatorio_fotos_plano', ['email' => $this->datos->email]);
    }
}