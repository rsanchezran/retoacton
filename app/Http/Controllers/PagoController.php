<?php

namespace App\Http\Controllers;

use App\Code\ValidarCorreo;
use App\ComprasCoins;
use App\Contacto;
use App\Mail\EnviarFicha;
use App\User;
use App\Events\CoinsEvent;
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
            'nombres' => 'required|max:100|min:2|regex:/^([a-zA-ZñÑáéíóúÁÉÍÓÚ\s]( )?)+$/',
            'apellidos' => 'required|max:100|min:2|regex:/^([a-zA-ZñÑáéíóúÁÉÍÓÚ\s]( )?)+$/',
            'email' => 'required|max:100|min:3|email',
            'email_confirmation' => 'required|max:100|min:3|email|same:email',
            'codigo' => 'max:7',
            'number' => 'required|max:16|min:16', //numero tarjeta
            'exp_month' => ['required','digits:2','regex:/((0[1-9])|(1[0-2])){1}/'],
            'exp_year' => 'required|digits:2',
            'cvc' => 'required|digits:3', //cvv
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
            'number.required' => 'El número de tarjeta es requerido',
            'number.max' => 'El número de tarjeta debe tener máximo 16 caracteres',
            'number.min' => 'El número de tarjeta debe tener mínimo 16 caracteres',
            'exp_month.required' => 'El mes es requerido',
            'exp_month.digits' => 'El mes debe tener 2 dígitos',
            'exp_month.regex' => 'El mes debe estar entre 01 y 12',
            'exp_year.required' => 'El año es requerido',
            'exp_year.digits' => 'El año debe tener 2 dígitos',
            'cvc.required' => 'El cvv es requerido',
            'cvc.digits' => 'El cvv debe tener 3 dígitos',
        ]);
        $validator->after(function ($validator) use ($request) {
            if (ValidarCorreo::validarCorreo($request->email)) {
                $validator->errors()->add("email", "El email debe tener formato correcto");
            }
        });
        $validator->validate();
    }

    public function tarjeta(Request $request)
    {
        $this->validarOpenpay($request);
        try {
            \DB::beginTransaction();
            $usuario = User::withTrashed()->orderBy('created_at')->where('email', $request->email)->get()->last();
            $cobro = User::calcularMontoCompra($request->codigo, $request->email,
                $usuario == null ? null : $usuario->created_at,
                $usuario == null ? null : $usuario->fecha_inscripcion,
                $usuario == null ? null : $usuario->inicio_reto, $usuario == null ? null : $usuario->deleted_at)->monto;

            if($usuario == null){
                $usuario = Contacto::where('email', $request->email)->get()->last();
                $d = $usuario->dias;
            }else {
                $d = explode('00', $usuario->dias_paso);
            }
            
            if($usuario->dias_paso !== null){
                if(intval($d[0]) == 14){$cobro=500;}
                /*if(intval($d[0]) == 28){$cobro=1000;}
                if(intval($d[0]) == 56){$cobro=2000;}
                if(intval($d[0]) == 84){$cobro=3000;}*/
                if(intval($d[1])==1){
                    if($usuario->saldo<$cobro) {
                        $cobro = ($cobro - $usuario->saldo);
                    }else{
                        $cobro = 0;
                    }
                }
            }else{
                if(intval($d) == 14){$cobro=500;}
                /*if(intval($d[0]) == 28){$cobro=1000;}
                if(intval($d[0]) == 56){$cobro=2000;}
                if(intval($d[0]) == 84){$cobro=3000;}*/
            }


            if ($usuario->dias_paso !== 0 && !$usuario->pago_refrendo){
                $usuario->dias = $usuario->dias_paso;
                $usuario->dias_paso = 0;
                $usuario->pago_refrendo = true;
            }

            Conekta::setApiKey(env("CONEKTA_PRIVATE"));
            Conekta::setApiVersion("2.0.0");
            if (isset($request->telefono)){
                $telefono = $request->telefono;
            }else{
                $telefono = '4686883409';
            }
            $valid_order =
                array(
                    'line_items' => array(
                        array(
                            'name' => 'Acton',
                            'description' => 'Acton reto',
                            'unit_price' => $cobro * 100,
                            'quantity' => 1,
                        )
                    ),
                    'currency' => 'mxn',
                    'customer_info' => array(
                        'name' => $request->nombres,
                        'phone' => $telefono,
                        'email' => $request->email
                    ),
                    'charges' => array(
                        array(
                            'payment_method' => array(
                                "type" => "card",
                                "token_id" => $request->conektaTokenId,
                            ),
                        )
                    ),
                );
            if ($request->meses) {
                $valid_order['charges'][0]['payment_method']['monthly_installments'] = '3';
            }
            $order = \Conekta\Order::create($valid_order);
            $contacto = Contacto::where('email', $request->email)->first();
            $contacto->order_id = $order->id;
            $contacto->save();
            \DB::commit();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'redirect' => url('login'), 'codigo' => $e->getCode(), 'error' => $e->getMessage()]);
        }
        return response()->json(['status' => 'ok', 'redirect' => url('login')]);
    }

    public function openpay(Request $request)
    {
        $this->validarOpenpay($request);
        try {
            \DB::beginTransaction();
            $usuario = User::withTrashed()->orderBy('created_at')->where('email', $request->email)->get()->last();
            $cobro = User::calcularMontoCompra($request->codigo, $request->email,
                $usuario == null ? null : $usuario->created_at,
                $usuario == null ? null : $usuario->fecha_inscripcion,
                $usuario == null ? null : $usuario->inicio_reto, $usuario == null ? null : $usuario->deleted_at)->monto;



            if($usuario == null){
                $usuario = Contacto::where('email', $request->email)->get()->last();
                $d = $usuario->dias;
            }else {
                $d = explode('00', $usuario->dias_paso);
            }
            if($usuario->dias_paso !== null){
                if(intval($d[0]) == 14){$cobro=500;}
                /*if(intval($d[0]) == 28){$cobro=1000;}
                if(intval($d[0]) == 56){$cobro=2000;}
                if(intval($d[0]) == 84){$cobro=3000;}*/
                if(intval($d[1])==1){
                    if($usuario->saldo<$cobro) {
                        $cobro = ($cobro - $usuario->saldo);
                    }else{
                        $cobro = 0;
                    }
                }
            }else{
                if(intval($d) == 14){$cobro=500;}
                /*if(intval($d[0]) == 28){$cobro=1000;}
                if(intval($d[0]) == 56){$cobro=2000;}
                if(intval($d[0]) == 84){$cobro=3000;}*/
            }


            if ($usuario->dias_paso !== 0 && !$usuario->pago_refrendo){
                $usuario->dias = $usuario->dias_paso;
                $usuario->dias_paso = 0;
                $usuario->pago_refrendo = true;
            }

            error_log('AQUI ESTA EL COBRO');
            error_log($cobro);
            $openpay = \Openpay::getInstance(
                env('OPENPAY_ID'),
                env('OPENPAY_PRIVATE')
            );
            \Openpay::setSandboxMode(env('SANDBOX'));
            $customer = array(
                'name' => $request->nombres,
                'last_name' => $request->apellidos,
                'email' => $request->email,
                'phone_number' => '4421112233',
            );
            $chargeRequest = array(
                'method' => 'card',
                'source_id' => $request->token,
                'amount' => "15",
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
                    $request->codigo, $cobro);
            } else {
                $usuario->refrendarPago($cobro);
            }
            \DB::commit();
            $usuario->save();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'redirect' => url('login'), 'codigo' => $e->getCode(), 'error' => $e->getMessage()]);
        }
        return response()->json(['status' => 'ok', 'redirect' => url('login')]);
    }

    public function oxxo(Request $request)
    {
        $this->validarTelefono($request);
        $usuario = User::withTrashed()->orderBy('created_at')->where('email', $request->email)->get()->last();
        $cobro = User::calcularMontoCompra($request->codigo, $request->email,
            $usuario == null ? null : $usuario->created_at,
            $usuario == null ? null : $usuario->fecha_inscripcion,
            $usuario == null ? null : $usuario->inicio_reto,
            $usuario == null ? null : $usuario->deleted_at)->monto;

        if($usuario == null){
            $con = Contacto::where('email', $request->email)->get()->last();
            $d = $con->dias;
        }else {
            $d = explode('00', $usuario->dias_paso);
            if($usuario->dias_paso !== null){
                if(intval($d[0]) == 14){$cobro=500;}
                /*if(intval($d[0]) == 28){$cobro=1000;}
                if(intval($d[0]) == 56){$cobro=2000;}
                if(intval($d[0]) == 84){$cobro=3000;}*/
                if(intval($d[1])==1){
                    if($usuario->saldo<$cobro) {
                        $cobro = ($cobro - $usuario->saldo);
                    }else{
                        $cobro = 0;
                    }
                }
            }else{
                if(intval($d) == 14){$cobro=500;}
                /*if(intval($d[0]) == 28){$cobro=1000;}
                if(intval($d[0]) == 56){$cobro=2000;}
                if(intval($d[0]) == 84){$cobro=3000;}*/
            }

            if ($usuario->dias_paso !== 0 && !$usuario->pago_refrendo){
                $usuario->dias = $usuario->dias_paso;
                $usuario->dias_paso = 0;
                $usuario->pago_refrendo = true;
            }
        }

        Conekta::setApiKey(env("CONEKTA_PRIVATE"));
        Conekta::setApiVersion("2.0.0");
        $valid_order =
            array(
                'line_items' => array(
                    array(
                        'name' => 'Acton',
                        'description' => 'Acton reto',
                        'unit_price' => $cobro * 100,
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
                            'expires_at' => strtotime(date("Y-m-d H:i:s")) + "72000"
                        ),
                    )
                ),
            );
        try {
            $order = \Conekta\Order::create($valid_order);
            $contacto = Contacto::where('email', $request->email)->first();
            if($contacto == null){
                $usuario_s = User::where('email', $request->email)->first();
                $contacto = new Contacto();
                $contacto->nombres = $usuario_s->name;
                $contacto->apellidos = "ok";
                $contacto->email = $usuario_s->email;
                $contacto->telefono = "";
                $contacto->objetivo = "";
                $contacto->dias = "14";
                $contacto->costo = 0;
                $contacto->mensaje = "";
            }
            $contacto->order_id = $order->id;
            $orden = new \stdClass();
            $orden->id = $order->id;
            $orden->referencia = $order->charges[0]->payment_method->reference;
            $orden->monto = ($order->amount / 100);
            $orden->origen = "oxxo";
            $contacto->telefono = $request->telefono;
            $contacto->objetivo = 0;
            $contacto->codigo = $request->codigo;
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
        $cobro = User::calcularMontoCompra($request->codigo, $request->email,
            $usuario == null ? null : $usuario->created_at,
            $usuario == null ? null : $usuario->fecha_inscripcion,
            $usuario == null ? null : $usuario->inicio_reto,
            $usuario == null ? null : $usuario->deleted_at)->monto;

        if($usuario == null){
            $con = Contacto::where('email', $request->email)->get()->last();
            $d = $con->dias;
        }else {
            $d = explode('00', $usuario->dias_paso);
            if($usuario->dias_paso !== null){
                if(intval($d[0]) == 14){$cobro=500;}
                /*if(intval($d[0]) == 28){$cobro=1000;}
                if(intval($d[0]) == 56){$cobro=2000;}
                if(intval($d[0]) == 84){$cobro=3000;}*/
            }else{
                if(intval($d) == 14){$cobro=500;}
                /*if(intval($d[0]) == 28){$cobro=1000;}
                if(intval($d[0]) == 56){$cobro=2000;}
                if(intval($d[0]) == 84){$cobro=3000;}*/
            }

            if ($usuario->dias_paso !== 0 && !$usuario->pago_refrendo){
                $usuario->dias = $usuario->dias_paso;
                $usuario->dias_paso = 0;
                $usuario->pago_refrendo = true;
            }
        }


        Conekta::setApiKey(env("CONEKTA_PRIVATE"));
        Conekta::setApiVersion("2.0.0");
        $valid_order =
            array(
                "line_items" => array(
                    array(
                        "name" => "Acton",
                        "description" => "Acton reto",
                        'unit_price' => $cobro * 100,
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
                            'expires_at' => strtotime(date("Y-m-d H:i:s")) + "72000"
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
            $contacto->codigo = $request->codigo;
            $contacto->objetivo = 0;
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
        $cobro = User::calcularMontoCompra($request->codigo, $request->email,
            $usuario == null ? null : $usuario->created_at,
            $usuario == null ? null : $usuario->fecha_inscripcion,
            $usuario == null ? null : $usuario->inicio_reto, $usuario == null ? null : $usuario->deleted_at)->monto;
        if ($usuario == null) {
            User::crear($request->nombres, $request->apellidos, $request->email,
                'paypal', 0, $request->codigo, $cobro);
        } else {
            $usuario->refrendarPagoCeros($cobro);
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
            'telefono' => 'required|numeric|max:9999999999|integer',
            'codigo' => 'max:7',
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
            'telefono.required' => 'El teléfono es requerido',
            'telefono.numeric' => 'El teléfono debe ser numérico',
            'telefono.max' => 'El teléfono debe tener 10 caracteres',
            'telefono.integer' => 'No puede ingresar números negativos',
            'codigo.max' => 'La referencia debe tener 7 caracteres',
        ]);
        $validator->after(function ($validator) use ($request) {
            if (ValidarCorreo::validarCorreo($request->email)) {
                $validator->errors()->add("email", "El email debe tener formato correcto");
            }
        });
        $validator->validate();
    }

    public function oxxoCoins(Request $request)
    {
        Conekta::setApiKey(env("CONEKTA_PRIVATE"));
        Conekta::setApiVersion("2.0.0");
        $valid_order =
            array(
                'line_items' => array(
                    array(
                        'name' => 'Acton',
                        'description' => 'CompraCoins',
                        'unit_price' => $request->monto * 100,
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
                            'expires_at' => strtotime(date("Y-m-d H:i:s")) + "72000"
                        ),
                    )
                ),
            );
        try {
            $order = \Conekta\Order::create($valid_order);
            $orden = new \stdClass();
            $orden->id = $order->id;
            $orden->referencia = $order->charges[0]->payment_method->reference;
            $orden->monto = ($order->amount / 100);
            $orden->origen = "oxxo";
            $compra = ComprasCoins::create([
                'usuario_id' => $request->user()->id,
                'referencia' => $orden->referencia,
                'tipo_compra' => 'oxxo',
                'monto' => $request->monto,
                'pagado' => 0,

            ]);
            event(new CoinsEvent($compra));
            return response()->json(['status' => 'ok', 'referencia' => $orden->referencia, 'monto' => $orden->monto,
                'origen' => $orden->origen]);
        } catch (\Conekta\ProcessingError $e) {
            echo $e->getMessage();
        } catch (\Conekta\ParameterValidationError $e) {
            echo $e->getMessage();
        }
        return response()->json(['status' => 'error']);
    }



    public function speiCoins(Request $request)
    {

        Conekta::setApiKey(env("CONEKTA_PRIVATE"));
        Conekta::setApiVersion("2.0.0");
        $valid_order =
            array(
                "line_items" => array(
                    array(
                        "name" => "Acton",
                        "description" => "CompraCoins",
                        'unit_price' => $request->monto * 100,
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
                            'expires_at' => strtotime(date("Y-m-d H:i:s")) + "72000"
                        ),//payment_method
                    ) //first charge
                ) //charges
            );
        try {
            $order = \Conekta\Order::create($valid_order);
            $orden = new \stdClass();
            $orden->id = $order->id;
            $orden->referencia = $order["charges"][0]["payment_method"]["clabe"];
            $orden->monto = ($order->amount / 100);
            $orden->origen = "spei";
            $compra = ComprasCoins::create([
                'usuario_id' => $request->user()->id,
                'referencia' => $orden->referencia,
                'tipo_compra' => 'spei',
                'monto' => $request->monto,
                'pagado' => 0,

            ]);
            return response()->json(['status' => 'ok', 'referencia' => $orden->referencia, 'monto' => $orden->monto,
                'origen' => $orden->origen]);
        } catch (\Conekta\ProcessingError $e) {
            echo $e->getMessage();
        } catch (\Conekta\ParameterValidationError $e) {
            echo $e->getMessage();
        }
        return response()->json(['status' => 'error']);
    }

    public function paypalCoins(Request $request)
    {
        $usuario = User::withTrashed()->orderBy('created_at')->where('email', $request->email)->get()->last();
        $cobro = $request->monto;
        $compra = ComprasCoins::create([
            'usuario_id' => $request->user()->id,
            'referencia' => 'paypal',
            'tipo_compra' => 'CompraCoins',
            'monto' => $request->monto,
            'pagado' => 1,

        ]);
        return response()->json(['status' => 'ok']);
    }

    public function tarjetaCoins(Request $request)
    {
        try {

            Conekta::setApiKey(env("CONEKTA_PRIVATE"));
            Conekta::setApiVersion("2.0.0");
            if (isset($request->telefono)){
                $telefono = $request->telefono;
            }else{
                $telefono = '4686883409';
            }
            $valid_order =
                array(
                    'line_items' => array(
                        array(
                            'name' => 'Acton',
                            'description' => 'CompraCoins',
                            'unit_price' => $request->monto * 100,
                            'quantity' => 1,
                        )
                    ),
                    'currency' => 'mxn',
                    'customer_info' => array(
                        'name' => $request->nombres,
                        'phone' => $telefono,
                        'email' => $request->email
                    ),
                    'charges' => array(
                        array(
                            'payment_method' => array(
                                "type" => "card",
                                "token_id" => $request->conektaTokenId,
                            ),
                        )
                    ),
                );
            if ($request->meses) {
                $valid_order['charges'][0]['payment_method']['monthly_installments'] = '3';
            }
            $order = \Conekta\Order::create($valid_order);

            $compra = ComprasCoins::create([
                'usuario_id' => $request->user()->id,
                'referencia' => $order->referencia,
                'tipo_compra' => 'spei',
                'monto' => $request->monto,
                'pagado' => 1,

            ]);

            event(new CoinsEvent($compra));

            $usuario = User::withTrashed()->where('id', $request->user()->id)->get()->last();
            $usuario->saldo = $usuario->saldo+$request->monto;
            $usuario->save();

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'redirect' => url('login'), 'codigo' => $e->getCode(), 'error' => $e->getMessage()]);
        }
        return response()->json(['status' => 'ok', 'redirect' => url('login')]);
    }
}
