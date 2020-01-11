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
        $send = $this->from(env('MAIL_ADDRESS'), 'Acton')
            ->subject("Reto Acton")
            ->to($this->contacto->email);
        $send->view('correo.contacto', ['contacto' => $this->contacto, 'email'=>$this->contacto->email]);
        return $send;
    }
}