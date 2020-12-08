<?php

namespace App\Http\Controllers\Auth;

use App\Code\MedioContacto;
use App\Code\TipoPago;
use App\Code\TipoRespuesta;
use App\Code\ValidarCorreo;
use App\CodigosTienda;
use App\Contacto;
use App\Http\Controllers\Controller;
use App\Pregunta;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['saveContacto', 'saveObjetivo', 'save$peso', 'buscarReferencia', 'unsuscribe',
            'unsuscribeSave']);

    }

    public function showRegistrationForm()
    {
        $photos = Storage::disk('local')->files('public/combos');
        $urls = collect();
        foreach ($photos as $photo) {
            $nombre = explode('/', $photo);
            $nombre = $nombre[count($nombre) - 1];
            $urls->push(url("getCombo/" . $nombre));
        }
        $medios = MedioContacto::all();
        return view('auth.register', ['urls' => $urls, 'medios' => $medios]);
    }

    public function saveContacto(Request $request)
    {
        $id = null;
        $validator = Validator::make($request->all(), [
            'nombres' => ['required', 'max:100', 'min:2', 'regex:/^([a-zA-ZñÑáéíóúÁÉÍÓÚ\s]( )?)+$/'],
            'apellidos' => 'required|max:100|min:2|regex:/^([a-zA-ZñÑáéíóúÁÉÍÓÚ\s]( )?)+$/',
            'email' => 'required|max:100|min:3|email',
            'telefono' => 'required|numeric|max:9999999999|integer',
            'codigo' => 'max:7',
        ], [
            'nombres.required' => 'El nombre es obligatorio',
            'nombres.min' => 'Debe capturar mínimo 2 caracteres en el nombre',
            'nombres.max' => 'Debe capturar máximo 100 caracteres en el nombre',
            'nombres.regex' => 'Debe capturar únicamente letras en el nombre',
            'apellidos.required' => 'Los apellidos son obligatorios',
            'apellidos.min' => 'Debe capturar mínimo 2 caracteres en los apellidos',
            'apellidos.max' => 'Debe capturar máximo 100 caracteres en los apellidos',
            'apellidos.regex' => 'Debe capturar únicamente letras en los apellidos',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.min' => 'Debe capturar minimo 3 caracteres en el correo electrónico',
            'email.max' => 'Debe capturar máximo 100 caracteres en el correo electrónico',
            'email.unique' => 'El correo ya ha sido registrado',
            'email.email' => 'El formato no es válido en el correo electrónico',
            'telefono.required' => 'El teléfono es requerido',
            'telefono.numeric' => 'El teléfono debe ser numérico',
            'telefono.max' => 'El teléfono debe tener 10 caracteres',
            'telefono.integer' => 'No puede ingresar números negativos',
            'codigo.max' => 'La referencia debe tener 7 caracteres',
        ]);
        /*$validator->after(function ($validator) use ($request) {
            if (ValidarCorreo::validarCorreo($request->email)) {
                $validator->errors()->add("email", "El email debe tener formato correcto");
            }
        });
        $validator->validate();*/
        $email = trim($request->email);
        $usuario = User::withTrashed()->orderBy('created_at')->where('email', $email)->get()->last();
        if ($usuario!=null&&$usuario->id==1){
            $cobro = new \stdClass();
            $cobro->original = 0;
            $cobro->descuento = 0;
            $cobro->monto = 0;
            $status = 'error';
            $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
        }else{
            error_log('AQUIIIIIIIIIIIIIII');
            error_log($request->tipo);
            $contacto = Contacto::withTrashed()->where("email", $email)->first();
            if ($contacto == null) {
                $contacto = new Contacto();
                $contacto->email = $email;
                $contacto->etapa = 1;
            }
            $contacto->nombres = $result = preg_replace('/\d/', '', $request->nombres);
            $contacto->apellidos = $request->apellidos;
            $contacto->fill(['dias' => $request->tipo]);
            $contacto->telefono = $request->telefono;
            $contacto->medio = $request->medio;
            $contacto->codigo = $request->codigo;
            $contacto->deleted_at = null;
            $contacto->save();
            $cobro = User::calcularMontoCompra($request->codigo, $email,
                $usuario == null ? null : $usuario->created_at,
                $usuario == null ? null : $usuario->fecha_inscripcion,
                $usuario == null ? null : $usuario->inicio_reto, $usuario== null ? null : $usuario->deleted_at);
            $mensaje = '';
            $status = 'ok';
            if ($usuario !== null) {
                if ($usuario->deleted_at == null) {
                    if ($usuario->inicio_reto == null) {
                        $status = 'error';
                        $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
                    } else {
                        if (Carbon::parse($usuario->inicio_reto)->diffInDays(Carbon::now()) < intval(env('DIAS'))) {
                            $status = 'error';
                            $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
                        }
                    }
                }
            }
        }
        return response()->json(['status' => $status, 'original' => $cobro->original, 'descuento' => $cobro->descuento,
            'monto' => $cobro->monto, 'mensaje' => $mensaje, 'fecha' => $contacto->created_at, 'horas' => $cobro->horas]);
    }

    public function saveObjetivo(Request $request)
    {
        $contacto = Contacto::find($request->id);
        $contacto->objetivo = $request->objetivo == 'bajar' ? 0 : 1;
        $contacto->save();
    }

    public function savePeso(Request $request)
    {
        $contacto = Contacto::find($request->id);
        $contacto->peso = intval($request->peso);
        $contacto->ideal = intval($request->ideal);
        $contacto->save();
        return $this->calcularAlcanzable($contacto->peso, $contacto->ideal);
    }

    private function calcularAlcanzable($peso, $ideal)
    {
        if ($peso < 50) {
            if ($peso < $ideal) {
                return ($peso + 5);
            } else {
                return ($peso - 5);
            }
        }
        if ($peso >= 50 && $peso < 60) {
            if ($peso < $ideal) {
                return ($peso + 5);
            } else {
                return ($peso - 5);
            }
        }
        if ($peso >= 60 && $peso < 70) {
            if ($peso < $ideal) {
                return ($peso + 4);
            } else {
                return ($peso - 3);
            }

        }
        if ($peso >= 70 && $peso < 80) {
            if ($peso < $ideal) {
                return ($peso + 4.5);
            } else {
                return ($peso - 3);
            }
        }
        if ($peso >= 80 && $peso <= 90) {
            if ($peso < $ideal) {
                return ($peso + 3);
            } else {
                return ($peso - 4);
            }
        }
        if ($peso > 90) {
            if ($peso < $ideal) {
                return ($peso + 2);
            } else {
                return ($peso - 5);
            }
        }
        if ($peso === $ideal) {
            return ($peso);
        }
        return 0;
    }

    public function buscarReferencia($referencia)
    {
        $user = User::select('name', 'last_name','id','num_inscripciones','inicio_reto')->where('referencia', $referencia)
            ->where('id','!=',1)->get()->first();
        if ($user == null) {
            abort(403, 'Unauthorized action.');
        }else{
            $user->isVencido();
            if ($user->vencido){
                abort(403, 'Unauthorized action.');
            }
        }
        return response()->json(['usuario' => $user->name . ' ' . $user->last_name]);
    }

    public function buscarReferenciaTienda($referencia, $email)
    {
        $user = CodigosTienda::where('codigo', $referencia)->where('email', $email)
            ->where('id','!=',1)->get()->first();

        if (!$user) {
            error_log('BBBB');
            abort(403, 'Unauthorized action.');
        }

        $user = User::where('id', $user->usuario_id_creador)->first();

        return response()->json(['usuario' => $user->name . ' ' .
            $user->last_name]);
    }

    public function unsuscribe($email)
    {
        $contacto = Contacto::where('email', $email)->first();
        return view('unsuscribe', ['contacto' => $contacto]);
    }

    public function unsuscribeSave(Request $request)
    {
        $contacto = Contacto::where('email', $request->email)->first();
        if ($contacto !== null) {
            $contacto->delete();
        }
        return redirect('/');
    }
}
