<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 26/06/19
 * Time: 10:25 AM
 */

namespace App\Http\Controllers\Api;

use App\Contacto;
use App\Http\Controllers\PagoController;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;

class ApiController extends Controller
{

    public function login(Request $request)
    {
        $usuario = User::where('email', $request->username)->first();

        if ($usuario != null && password_verify($request->password, $usuario->password)) {
            $contacto = Contacto::withTrashed()->where('email',$usuario->email)->first;
            $usuario->telefono = $contacto->telefono;
            return response()->json(['result' => 'ok', 'user' => $usuario]);
        }

        return response()->json(['result' => 'error', 'error' => 'El usuario y/o la contraseña son incorrectos.']);
    }

    public function webhook(Request $request)
    {
        if ($request->data["object"] != null) {
            if (array_key_exists('order_id', $request->data["object"])) {
                $order_id = $request->data["object"]["order_id"];
                $cobro = $request->data["object"]["amount"];
                $contacto = Contacto::where("order_id", $order_id)->first();
                if ($contacto !== null) {
                    $usuario = User::where('email', $contacto->email)->first();
                    if ($usuario == null) {
                        User::crear($contacto->nombres, $contacto->apellidos, $contacto->email,
                            $request->data["object"]["payment_method"]["type"], 0, $contacto->codigo, $cobro);
                    } else {
                        $usuario->refrendarPago($cobro, $contacto->telefono);
                    }
                }
            }
        }
        return "ok";
    }
}