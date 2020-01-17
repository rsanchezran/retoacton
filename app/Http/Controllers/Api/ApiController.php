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
            return response()->json(['result' => 'ok', 'user' => $usuario]);
        }

        return response()->json(['result' => 'error', 'error' => 'El usuario y/o la contraseña son incorrectos.']);
    }

    public function webhook(Request $request)
    {
        if ($request->data["object"] != null) {
            if (array_key_exists('order_id', $request->data["object"])) {
                $order_id = $request->data["object"]["order_id"];
                $contacto = Contacto::where("order_id", $order_id)->first();
                if ($contacto !== null) {
                    $usuario = User::where('email', $contacto->email)->first();
                    if ($usuario==null){
                        User::crear($contacto->nombres, $contacto->apellidos, $contacto->email,
                            $request->data["object"]["payment_method"]["type"], 0, $contacto->codigo);
                        $pagoController = new PagoController();
                        $pagoController->aumentarSaldo($contacto->codigo);
                    }else{
                        $usuario->telefono = $contacto->telefono;
                        $usuario->objetivo = 0;
                        $usuario->pagado = true;
                        $usuario->fecha_inscripcion = Carbon::now();
                        $usuario->save();
                        $mensaje = new \stdClass();
                        $mensaje->subject = "Bienvenido de nuevo al Reto Acton";
                        $mensaje->pass = "";
                        try{
                            Mail::queue(new Registro($usuario, $mensaje));
                            $usuario->correo_enviado = 1;
                            $usuario->save();
                        }catch (\Exception $e){}
                    }
                }
            }
        }
        return "ok";
    }
}