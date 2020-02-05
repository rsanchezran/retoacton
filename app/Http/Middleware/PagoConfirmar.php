<?php

namespace App\Http\Middleware;

use App\Code\RolUsuario;
use Carbon\Carbon;
use Closure;

class PagoConfirmar
{

    public function handle($request, Closure $next)
    {
        $usuario = auth()->user();
        if($usuario != null){
            if ((auth()->check() && $usuario->encuestado && $usuario->pagado) || $usuario->rol == RolUsuario::ADMIN) {
                if ($usuario->rol == RolUsuario::ADMIN){
                    auth()->user()->vencido = false;
                }else{
                    if ($usuario->num_inscripciones==1){
                        auth()->user()->vencido = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($usuario->inicio_reto))>intval(env('DIAS'));
                    }else{
                        auth()->user()->vencido = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($usuario->inicio_reto))>intval(env('DIAS2'));
                    }
                }
                return $next($request);
            }
            if (!$usuario->pagado) {
                return redirect('/pago');
            } else {
                return redirect('/encuesta');
            }
        }else{
            return redirect('/');
        }

    }

}