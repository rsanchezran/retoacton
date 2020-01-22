<?php

namespace App\Mail;

use App\Code\LugarEjercicio;
use App\Dia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Dieta extends Mailable
{

    use Queueable, SerializesModels;

    public $dia;
    public $genero;
    public $objetivo;
    public $lugar;
    public $usuario;
    public $dieta;

    public function __construct($dia, $genero, $objetivo, $lugar, $usuario, $dieta)
    {
        $this->dia = $dia;
        $this->genero = $genero;
        $this->objetivo = $objetivo;
        $this->lugar = $lugar;
        $this->usuario = $usuario;
        $this->dieta = $dieta;
    }

    public function build()
    {
        $diaDB = Dia::buildDia($this->dia, $this->genero, $this->objetivo, $this->usuario, $this->dieta);
        $diaDB->ejercicios = $this->lugar == LugarEjercicio::GYM ? $diaDB->gym : $diaDB->casa;
        $pdf = \Barryvdh\DomPDF\Facade::loadView('reto.pdf', ['dia' => $diaDB, 'genero' => $this->genero,
            'objetivo' => $this->objetivo, 'lugar' => $this->lugar]);
        $send = $this->from(env('MAIL_ADDRESS'), 'Acton')
            ->subject("Dieta")
            ->to($this->usuario->email);
        $send->view('correo.dieta', ['dia' => $diaDB->dia, 'comidas' => $diaDB->comidas, 'suplementos' => $diaDB->suplementos,
            'ejercicios' => $diaDB->ejercicios, 'cardio' => $diaDB->cardio, 'email' => $this->usuario->email])
            ->attachdata($pdf->output(), 'dia.pdf')
            ->text('correo.dieta_plano', ['dia' => $diaDB->dia, 'comidas' => $diaDB->comidas, 'suplementos' => $diaDB->suplementos,
                'ejercicios' => $diaDB->ejercicios, 'cardio' => $diaDB->cardio]);
        return $send;
    }
}