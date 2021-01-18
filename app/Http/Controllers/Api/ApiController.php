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
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{

    public function login(Request $request)
    {
        try {
            $usuario = User::where('email', $request->username)->get(['name', 'last_name', 'password', 'email', 'inicio_reto',
                'num_inscripciones'])->first();

            if ($usuario != null && password_verify($request->password, $usuario->password)) {
                $usuario->isVencido();
                $contacto = Contacto::withTrashed()->where('email', $usuario->email)->first();
                $user = new \stdClass();
                $user->telefono = $contacto->telefono;
                $user->name = $usuario->name;
                $user->last_name = $usuario->last_name;
                $user->email = $usuario->email;
                $user->inicio_reto = $usuario->inicio_reto;
                $user->num_inscripciones = $usuario->num_inscripciones;
                $user->vencido = $usuario->vencido;
                return response()->json(['result' => 'ok', 'user' => $user]);
            }
            return response()->json(['result' => 'error', 'error' => 'El usuario y/o la contraseña son incorrectos.']);
        } catch (\Exception $e) {
            return response()->json(['result' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function webhook(Request $request)
    {
        if (isset($request->data['object'])) {
            error_log('Objeto 1');
            $object = $request->data['object'];
            if ($object != null) {
                error_log('OBJETO');

                if (array_key_exists('id', $object)) {
                    error_log('ID EXISTE');
                    if ($object['payment_status'] == 'paid') {
                        $order_id = $object["id"];
                        $cobro = $object["amount"] / 100;
                        $contacto = Contacto::where("order_id", $order_id)->first();
                        error_log('PAGADO');
                        if ($contacto !== null) {
                            $usuario = User::withTrashed()->where('email', $contacto->email)->first();
                            error_log('CONTACTO NULL');
                            if ($usuario == null) {
                                error_log('USUARIO NO NULL');
                                User::crear($contacto->nombres, $contacto->apellidos, $contacto->email,
                                    $object["charges"]["data"]["payment_method"]["type"], 0, $contacto->codigo, $cobro);
                                return response()->json(['status' => 'ok', 'res' => 'usr no null']);
                            } else {
                                error_log('USUARIO NULL');
                                $usuario->refrendarPago($cobro, $contacto->telefono);
                                return response()->json(['status' => 'ok', 'res' => 'usr null']);
                            }
                        }

                        return response()->json(['status' => 'ok', 'res' => 'contacto null']);
                    }

                    return response()->json(['status' => 'ok', 'res' => 'no hay paid']);
                }

                return response()->json(['status' => 'ok', json_encode($object)]);
            }

            return response()->json(['status' => 'ok', 'res' => 'no hay object']);
        }
        error_log('RETURN');
        return response()->json(['status' => 'ok', 'res' => 'res']);
    }

    public function getWebhook()
    {
        http_response_code(200); // Return 200 OK

        return response()->json(['status' => 'ok']);
    }
}
