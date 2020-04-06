<?php

namespace App\Http\Controllers;

use App\Code\Objetivo;
use App\Code\TipoRespuesta;
use App\Code\ValidarCorreo;
use App\Contacto;
use App\Mail\EnviarFicha;
use App\Mail\Registro;
use App\Pregunta;
use App\Respuesta;
use App\Rules\validarAnio;
use App\Rules\validarMes;
use App\User;
use App\UsuarioKit;
use Carbon\Carbon;
use Conekta\Conekta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();
        $contacto = Contacto::where('email', $usuario->email)->first();
        if ($contacto !== null) {
            $usuario->telefono = $contacto->telefono;
        }
        if ($usuario->pagado) {
            return redirect('home');
        } else {
            return view('pago', ['usuario' => $usuario]);
        }
    }

    public function validarOpenpay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombres' => ['required', 'max:100', 'min:2', 'regex:/^([a-zA-ZñÑáéíóúÁÉÍÓÚ\s]( )?)+$/'],
            'apellidos' => 'required|max:100|min:2|regex:/^([a-zA-ZñÑáéíóúÁÉÍÓÚ\s]( )?)+$/',
            'email' => 'required|max:100|min:3|email',
            'email_confirmation' => 'required|max:100|min:3|email|same:email',
            'telefono' => 'nullable|numeric|max:9999999999|integer',
            'numero' => 'required|max:16|min:16', //numero tarjeta
            'codigo' => 'required|digits:3', //cvv
            'mes' => ['required', 'digits:2', 'regex:/((0[1-9])|(1[0-2])){1}/'],
            'ano' => ['required', 'digits:2'],
        ], [
            'nombres.required' => 'Este campo es obligatorio',
            'nombres.min' => 'Debe capturar mínimo 2 caracteres',
            'nombres.max' => 'Debe capturar máximo 100 caracteres',
            'nombres.regex' => 'Debe capturar únicamente letras',
            'apellidos.required' => 'Este campo es obligatorio',
            'apellidos.min' => 'Debe capturar mínimo 2 caracteres',
            'apellidos.max' => 'Debe capturar máximo 100 caracteres',
            'apellidos.regex' => 'Debe capturar únicamente letras',
            'email.required' => 'Este campo es obligatorio',
            'email.min' => 'Debe capturar minimo 3 caracteres',
            'email.max' => 'Debe capturar máximo 100 caracteres',
            'email.unique' => 'El correo ya ha sido registrado',
            'email.email' => 'El formato no es válido',
            'email_confirmation.required' => 'Este campo es obligatorio',
            'email_confirmation.min' => 'Debe capturar minimo 3 caracteres',
            'email_confirmationmail.max' => 'Debe capturar máximo 100 caracteres',
            'email_confirmation.unique' => 'El correo ya ha sido registrado',
            'email_confirmation.email' => 'El formato no es válido',
            'email_confirmation.same' => 'El correo electrónico de confirmación no es igual al primer correo que ingresaste',
            'telefono.max' => 'Debe ser menor a 12 caracteres',
            'telefono.numeric' => 'Debe ser numérico',
            'telefono.integer' => 'No puede ingresar números negativos',
            'numero.required' => 'El número de tarjeta es requerido',
            'numero.max' => 'El número de tarjeta debe tener máximo 16 caracteres',
            'numero.min' => 'El número de tarjeta debe tener mínimo 16 caracteres',
            'codigo.required' => 'El cvv es requerido',
            'codigo.digits' => 'El cvv debe tener 3 dígitos',
            'mes.required' => 'El mes es requerido',
            'mes.digits' => 'El mes debe tener 2 dígitos',
            'mes.regex' => 'El mes debe estar entre 01 y 12',
            'ano.required' => 'El año es requerido',
            'ano.digits' => 'El año debe tener 2 dígitos',
        ]);
        $validator->after(function ($validator) use ($request) {
            if (ValidarCorreo::validarCorreo($request->email)) {
                $validator->errors()->add("email", "El email debe tener formato correcto");
            }
        });
        $validator->validate();
    }

    public function openpay(Request $request)
    {
        $this->validarOpenpay($request);
        try {
            \DB::beginTransaction();
            $usuario = User::withTrashed()->orderBy('created_at')->where('email', $request->email)->get()->last();
            $cobro = User::calcularMontoCompra($request->pregunta, $request->email,
                $usuario == null ? null : $usuario->created_at,
                $usuario  == null ? null : $usuario->fecha_inscripcion,
                $usuario == null ? null : $usuario->inicio_reto, $usuario == null ? null : $usuario->deleted_at)->monto;
            $openpay = \Openpay::getInstance(
                env('OPENPAY_ID'),
                env('OPENPAY_PRIVATE')
            );
            \Openpay::setSandboxMode(env('SANDBOX'));
            $customer = array(
                'name' => $request->nombres,
                'last_name' => $request->apellidos,
                'email' => $request->email
            );
            $chargeRequest = array(
                'method' => 'card',
                'source_id' => $request->token,
                'amount' => "$cobro",
                'currency' => 'MXN',
                'description' => 'Inscripción al Reto Acton',
                'device_session_id' => $request->deviceSessionId,
                'customer' => $customer
            );
            if ($request->meses) {
                $chargeRequest["payment_plan"] = ["payments" => 3];
            }
            $openpay->charges->create($chargeRequest);
            if ($usuario == null) {
                User::crear($request->nombres, $request->apellidos, $request->email, 'tarjeta', 0,
                    $request->pregunta, $cobro);
            } else {
                $usuario->refrendarPago($cobro);
            }
            \DB::commit();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'redirect' => url('login'), 'codigo' => $e->getCode(), 'error' => $e->getMessage()]);
        }
        return response()->json(['status' => 'ok', 'redirect' => url('login')]);
    }

    public function oxxo(Request $request)
    {
        $this->validarTelefono($request);
        $usuario = User::withTrashed()->orderBy('created_at')->where('email', $request->email)->get()->last();
        $cobro = User::calcularMontoCompra($request->pregunta, $request->email,
            $usuario == null ? null : $usuario->created_at,
            $usuario == null ? null : $usuario->fecha_inscripcion,
            $usuario  == null ? null : $usuario->inicio_reto,
            $usuario == null ? null : $usuario->deleted_at)->monto;
        Conekta::setApiKey(env("CONEKTA_PRIVATE"));
        Conekta::setApiVersion("2.0.0");
        $valid_order =
            array(
                'line_items' => array(
                    array(
                        'name' => 'Acton',
                        'description' => 'Acton reto',
                        'unit_price' => $cobro*100,
                        'quantity' => 1,
                    )
                ),
                'currency' => 'mxn',
                'customer_info' => array(
                    'name' => $request->nombres,
                    'phone' => '52' . $request->telefono,
                    'email' => $request->email
                ),
                'charges' => array(
                    array(
                        'payment_method' => array(
                            'type' => 'oxxo_cash',
                            'expires_at' => strtotime(date("Y-m-d H:i:s")) + "24000"
                        ),
                    )
                ),
            );
        try {
            $order = \Conekta\Order::create($valid_order);
            $contacto = Contacto::where('email', $request->email)->first();
            $contacto->order_id = $order->id;
            $orden = new \stdClass();
            $orden->id = $order->id;
            $orden->referencia = $order->charges[0]->payment_method->reference;
            $orden->monto = ($order->amount / 100);
            $orden->origen = "oxxo";
            $contacto->telefono = $request->telefono;
            $contacto->objetivo = 0;
            $contacto->codigo = $request->pregunta;
            $contacto->save();
            try {
                Mail::queue(new EnviarFicha($contacto, $orden));
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
            return response()->json(['status' => 'ok', 'referencia' => $orden->referencia, 'monto' => $orden->monto,
                'origen' => $orden->origen]);
        } catch (\Conekta\ProcessingError $e) {
            echo $e->getMessage();
        } catch (\Conekta\ParameterValidationError $e) {
            echo $e->getMessage();
        }
        return response()->json(['status' => 'error']);
    }

    public function spei(Request $request)
    {
        $this->validarTelefono($request);
        $usuario = User::withTrashed()->orderBy('created_at')->where('email', $request->email)->get()->last();
        $cobro = User::calcularMontoCompra($request->pregunta, $request->email,
            $usuario == null ? null : $usuario->created_at,
            $usuario == null ? null : $usuario->fecha_inscripcion,
            $usuario == null ? null : $usuario->inicio_reto, $usuario == null ? null : $usuario->deleted_at)->monto;
        Conekta::setApiKey(env("CONEKTA_PRIVATE"));
        Conekta::setApiVersion("2.0.0");
        $valid_order =
            array(
                "line_items" => array(
                    array(
                        "name" => "Acton",
                        "description" => "Acton reto",
                        'unit_price' => $cobro*100,
                        "quantity" => 1
                    )//first line_item
                ), //line_items
                "currency" => "MXN",
                "customer_info" => array(
                    "name" => $request->nombres,
                    "email" => $request->email,
                    'phone' => '52' . $request->telefono,
                ), //customer_info
                "charges" => array(
                    array(
                        "payment_method" => array(
                            "type" => "spei",
                            'expires_at' => strtotime(date("Y-m-d H:i:s")) + "24000"
                        ),//payment_method
                    ) //first charge
                ) //charges
            );
        try {
            $order = \Conekta\Order::create($valid_order);
            $contacto = Contacto::where('email', $request->email)->first();
            $contacto->order_id = $order->id;
            $orden = new \stdClass();
            $orden->id = $order->id;
            $orden->referencia = $order["charges"][0]["payment_method"]["clabe"];
            $orden->monto = ($order->amount / 100);
            $orden->origen = "spei";
            $contacto->telefono = $request->telefono;
            $contacto->objetivo = 0;
            $contacto->codigo = $request->pregunta;
            $contacto->save();
            try {
                Mail::queue(new EnviarFicha($contacto, $orden));
            } catch (\Exception $e) {
            }
            return response()->json(['status' => 'ok', 'referencia' => $orden->referencia, 'monto' => $orden->monto,
                'origen' => $orden->origen]);
        } catch (\Conekta\ProcessingError $e) {
            echo $e->getMessage();
        } catch (\Conekta\ParameterValidationError $e) {
            echo $e->getMessage();
        }
        return response()->json(['status' => 'error']);
    }

    public function paypal(Request $request)
    {
        $usuario = User::withTrashed()->orderBy('created_at')->where('email', $request->email)->get()->last();
        $cobro = User::calcularMontoCompra($request->pregunta, $request->email,
            $usuario == null ? null : $usuario->created_at,
            $usuario == null ? null : $usuario->fecha_inscripcion,
            $usuario == null ? null : $usuario->inicio_reto, $usuario == null ? null : $usuario->deleted_at)->monto;
        if ($usuario == null) {
            User::crear($request->nombres, $request->apellidos, $request->email,
                'paypal', 0, $request->pregunta, $cobro);
        } else {
            $usuario->refrendarPago($cobro);
        }
        return response()->json(['status' => 'ok', 'redirect' => url('login')]);
    }

    public function validarTelefono($request)
    {
        $validator = Validator::make($request->all(), [
            'nombres' => ['required', 'max:100', 'min:2', 'regex:/^([a-zA-ZñÑáéíóúÁÉÍÓÚ\s]( )?)+$/'],
            'apellidos' => 'required|max:100|min:2|regex:/^([a-zA-ZñÑáéíóúÁÉÍÓÚ\s]( )?)+$/',
            'email' => 'required|max:100|min:3|email',
            'email_confirmation' => 'required|max:100|min:3|email|same:email',
            'telefono' => 'numeric|max:9999999999|integer',
            'referencia' => 'min:7',
        ], [
            'nombres.required' => 'El nombre es obligatorio',
            'nombres.min' => 'El nombre debe tener mínimo 2 caracteres',
            'nombres.max' => 'El nombre debe tener máximo 100 caracteres',
            'nombres.regex' => 'Debe capturar únicamente letras en el nombre',
            'apellidos.required' => 'Los apellidos son obligatorios',
            'apellidos.min' => 'Los apellidos deben tener mínimo 2 caracteres',
            'apellidos.max' => 'Los apellidos deben tener máximo 100 caracteres',
            'apellidos.regex' => 'Debe capturar únicamente letras en los apellidos',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.min' => 'El correo electrónico debe tener minimo 3 caracteres',
            'email.max' => 'El correo electrónico debe tener máximo 100 caracteres',
            'email.unique' => 'El correo ya ha sido registrado',
            'email.email' => 'El formato del correo electrónico no es válido',
            'email_confirmation.required' => 'La confirmación de correo electrónico campo es obligatorio',
            'email_confirmation.min' => 'La confirmación de correo electrónico debe tener minimo 3 caracteres',
            'email_confirmationmail.max' => 'La confirmación de correo electrónico debe tener máximo 100 caracteres',
            'email_confirmation.email' => 'El formato de la confirmación del correo electrónico no es válido',
            'email_confirmation.same' => 'El correo electrónico de confirmación no es igual al primer correo que ingresaste',
            'referencia.max' => 'El código de referencia debe tener minimo 6 caracteres',
            'telefono.max' => 'El teléfono debe tener máximo 12 caracteres',
            'telefono.numeric' => 'El teléfono debe ser numérico',
            'telefono.integer' => 'No puede ingresar números negativos en el teléfono',
            'referencia.min' => 'La referencia debe tener 7 caracteres',
        ]);
        $validator->after(function ($validator) use ($request) {
            if (ValidarCorreo::validarCorreo($request->email)) {
                $validator->errors()->add("email", "El email debe tener formato correcto");
            }
        });
        $validator->validate();
    }
}
