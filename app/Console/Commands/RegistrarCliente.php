<?php

namespace App\Console\Commands;

use App\Code\MedioContacto;
use App\Contacto;
use App\Job;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RegistrarCliente extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registrar:cliente {cliente}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para crear usuarios por el administrador';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = $this->argument('cliente');
        $usuario = json_decode($user);
        $contacto = new Contacto();
        $contacto->nombres = $usuario->nombres;
        $contacto->apellidos = $usuario->apellidos;
        $contacto->email = $usuario->email;
        $contacto->telefono = $usuario->telefono;
        $contacto->codigo = $usuario->codigo;
        $contacto->objetivo = 0;
        $contacto->etapa = 5;
        $contacto->medio = MedioContacto::OTRO;
        $contacto->save();

        User::crear($usuario->nombres, $usuario->apellidos, $usuario->email, $usuario->tipo_pago, 0,
            $usuario->codigo, $usuario->cobro);
    }
}
