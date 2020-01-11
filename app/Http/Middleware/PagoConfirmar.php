<?php

namespace App\Http\Middleware;

use App\Code\RolUsuario;
use Closure;

class PagoConfirmar
{

    public function handle($request, Closure $next)
    {
        $usuario = auth()->user();
        if($usuario != null){
            if ((auth()->check() && $usuario->encuestado && $usuario->pagado) || $usuario->rol == RolUsuario::ADMIN) {
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