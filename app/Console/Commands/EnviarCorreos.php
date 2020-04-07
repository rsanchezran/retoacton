<?php

namespace App\Console\Commands;

use App\Code\Utils;
use App\Contacto;
use App\Mail\Registro;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        $mailchimp = new Mailchimp();
        if (!Cache::has('etapa1.workflow')) {
            $expiresAt = Carbon::now()->addHours(8);
            $mailchimp->getAutomations($expiresAt);
        }
        \DB::beginTransaction();
        $contactos1 = Contacto::leftjoin('users', 'contactos.email', 'users.email')
            ->where(function ($q) {
                $q->whereNotNull('users.deleted_at');
                $q->orWhereNull('users.id');
            })->where('contactos.etapa', 1)->where('contactos.created_at', '<', $fecha)
            ->get(['contactos.id', 'contactos.email', 'contactos.etapa', 'contactos.nombres', 'contactos.apellidos'])->keyBy('email');
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
                Log::error($e->getMessage());
            }
        }
        \DB::commit();
    }

    public function etapa($etapa, $contactos)
    {
        $mailchimp = new Mailchimp();
        if ($contactos->count() > 0) {
            if ($etapa == 1) { //sucribirlos a mailchimp
                $lista = $mailchimp->getLista("Reto Acton");
                $mailchimp->enviarMiembros($lista, $contactos);
                $mailchimp->enviarCorreo($contactos, Cache::get('etapa1.workflow'), Cache::get('etapa1.queue'));
            } else {
                if ($etapa == 2) {
                    $mailchimp->enviarCorreo($contactos, Cache::get('etapa2.workflow'), Cache::get('etapa2.queue'));
                } else {
                    if ($etapa == 3) {
                        $mailchimp->enviarCorreo($contactos, Cache::get('etapa3.workflow'), Cache::get('etapa3.queue'));
                    } else {
                        $lista = $mailchimp->getLista("Reto Acton");
                        foreach ($contactos as $contacto) {
                            $mailchimp->quitarMiembro($lista, $contacto->email);
                        }
                    }
                }
            }
            foreach ($contactos as $contacto) {
                $contacto->etapa = $etapa + 1;
                $contacto->save();
            }
        }
    }

}
