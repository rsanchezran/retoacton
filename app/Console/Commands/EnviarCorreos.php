<?php

namespace App\Console\Commands;

use App\Code\Utils;
use App\Contacto;
use App\Events\EnviarCorreosEvent;
use App\User;
use Carbon\Carbon;
use Conekta\Log;
use Illuminate\Console\Command;

class EnviarCorreos extends Command
{
    protected $signature = 'enviar_correos';
    protected $description = 'Enviar correo a contactos';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $fecha = Carbon::now();
        $fecha->subDay(1);
        \DB::beginTransaction();
        $contactos1 = Contacto::leftjoin('users', 'contactos.email', 'users.email')
            ->where(function ($q) {
                $q->whereNotNull('users.deleted_at');
                $q->orWhereNull('users.id');
            })->where('contactos.etapa', 1)->where('contactos.created_at', '<', $fecha)
            ->get(['contactos.id', 'contactos.email', 'contactos.etapa']);
        $contactos2 = Contacto::leftjoin('users', 'contactos.email', 'users.email')
            ->where(function ($q) {
                $q->whereNotNull('users.deleted_at');
                $q->orWhereNull('users.id');
            })->where('contactos.etapa', 2)->where('contactos.created_at', '<', $fecha)
            ->get(['contactos.id', 'contactos.email', 'contactos.etapa']);
        $contactos3 = Contacto::leftjoin('users', 'contactos.email', 'users.email')
            ->where(function ($q) {
                $q->whereNotNull('users.deleted_at');
                $q->orWhereNull('users.id');
            })->where('contactos.etapa', 3)->where('contactos.created_at', '<', $fecha)
            ->get(['contactos.id', 'contactos.email', 'contactos.etapa']);
        $contactos4 = Contacto::leftjoin('users', 'contactos.email', 'users.email')
            ->where(function ($q) {
                $q->whereNotNull('users.deleted_at');
                $q->orWhereNull('users.id');
            })->where('contactos.etapa', 4)->where('contactos.created_at', '<', $fecha)
            ->get(['contactos.id', 'contactos.email', 'contactos.etapa']);
        $this->etapa(1, $contactos1);
        $this->etapa(2, $contactos2);
        $this->etapa(3, $contactos3);
        $this->etapa(4, $contactos4);
        $usuarios = User::where('correo_enviado', false)->get();
        foreach ($usuarios as $usuario) {
            $pass = Utils::generarRandomString();
            $mensaje = new \stdClass();
            $mensaje->subject = "Bienvenido al Reto Acton de 8 semanas";
            $mensaje->pass = $pass;
            try {
                $usuario->password = $pass;
                Mail::queue(new Registro($usuario, $mensaje));
                $usuario->correo_enviado = 1;
                $usuario->save();
            } catch (\Exception $e) {
            }
        }
        \DB::commit();
    }

    public function etapa($etapa, $contactos)
    {

        if ($contactos->count() > 0) {
            if ($etapa<4){
                try {
                    event(new EnviarCorreosEvent($contactos));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error($e->getMessage());
                }
            }
            foreach ($contactos as $contacto) {
                $contacto->etapa = $etapa + 1;
                $contacto->save();
            }
        }
    }

}
