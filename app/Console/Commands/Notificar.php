<?php
namespace App\Console\Commands;

use App\Mail\Recordatorio_Fotos;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;

class Notificar extends Command{
    protected $signature = 'notificar';
    protected $description = 'Notificar que suban sus fotos semenalmente';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(){
        $usuarios = User::all();

        foreach ($usuarios as $usuario) { //enviar correo solo a personas que tengan mas de un dia sin subir foto
            $dias_fotos = count(Storage::allFiles('public/reto/' . $usuario->id));
            $inicio_reto = Carbon::parse($usuario->inicio_reto);

            $dias_reto = Carbon::now()->diffInDays($inicio_reto->format('y-m-d'))+1;

            if(($dias_reto-$dias_fotos)>=2) {
                Mail::queue(new Recordatorio_Fotos($usuario, 'Recordario semanal reto Acton'));
            }
        }
    }
}