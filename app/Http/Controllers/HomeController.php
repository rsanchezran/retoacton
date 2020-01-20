<?php

namespace App\Http\Controllers;

use App\Code\Genero;
use App\Code\Objetivo;
use App\Code\RolUsuario;
use App\Code\TipoPago;
use App\Code\TipoRespuesta;
use App\Contacto;
use App\Dieta;
use App\Kits;
use App\Pregunta;
use App\Rango;
use App\User;
use App\UsuarioDieta;
use App\UsuarioKit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Respuesta;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{

    public function home(Request $request)
    {
        $comision = intval(env('COMISION'));
        $usuario = User::where('id', $request->user()->id)->get()->first();
        $usuario->total = $usuario->ingresados * $comision;
        $usuario->depositado = $usuario->total - $usuario->saldo;
        $referencias = User::select(['id', 'name', 'email', 'created_at'])
            ->where('codigo', $request->user()->referencia)->whereNotNull('codigo')->get();
        return view('home', ['usuario' => ($usuario), 'referencias' => $referencias]);
    }

    public function index()
    {
        $photos = Storage::disk('local')->files('public/img');
        $testimonios = Storage::disk('local')->files('public/testimonios');
        $urls = collect();
        $personas = collect();
        foreach ($photos as $photo) {
            $nombre = explode('/', $photo);
            $nombre = $nombre[count($nombre) - 1];
            $urls->push(url("getImagen/" . $nombre));
        }
        foreach ($testimonios as $testimonio) {
            $nombre = explode('/', $testimonio);
            $nombre = $nombre[count($nombre) - 1];
            $personas->push(url("getTestimonio/" . $nombre));
        }
        return view('welcome', ['urls' => $urls, 'testimonios' => $personas]);
    }

    public function getImage($imagen)
    {
        return response()->download(
            storage_path('app/public/img/' . $imagen),
            'filename.png',
            ['Content-Type' => 'image/png']
        );
    }

    public function getTestimonio($imagen)
    {
        return response()->download(
            storage_path('app/public/testimonios/' . $imagen),
            'filename.png',
            ['Content-Type' => 'image/png']
        );
    }

    public function getCombo($imagen)
    {
        return response()->download(
            storage_path('app/public/combos/' . $imagen),
            'filename.png',
            ['Content-Type' => 'image/png']
        );
    }

    public function getVideo($video, $random)
    {
        $nombre = str_replace(" ", "_", $video);
        return response()->file(storage_path("app/public/optimized/$nombre.mp4"));
    }

    public function encuesta(Request $request)
    {
        $user = $request->user();
        if ($user->rol == RolUsuario::ADMIN || ($user->pagado && !$user->encuestado)) {
            $usuario = $request->user();
            $usuario->medio = "";
            $preguntas = Pregunta::select(['id', 'pregunta', 'opciones', 'multiple'])->get();
            $photos = Storage::disk('local')->files('public/img');
            $urls = collect();
            foreach ($photos as $photo) {
                $nombre = explode('/', $photo);
                $nombre = $nombre[count($nombre) - 1];
                $urls->push(url("getImagen/" . $nombre));
            }
            foreach ($preguntas as $pregunta) {
                $pregunta->mostrar = false;

                if ($pregunta->multiple == TipoRespuesta::MULTIPLE) { //De multiples Selecciones
                    $pregunta->animacion = 'spiral'; //depende del nombre de la animacion en la vista register.blade
                    $opciones = json_decode($pregunta->opciones);
                    $pregunta->opciones = collect();
                    foreach ($opciones as $op) {
                        $opcion = new \stdClass();
                        $opcion->respuesta = $op;
                        $opcion->selected = false;
                        $pregunta->opciones->push($opcion);
                    }
                    $pregunta->respuesta = [];
                } else if ($pregunta->multiple === TipoRespuesta::UNICA) { //De una seleccion Respuesta
                    $pregunta->animacion = 'vertical';
                    $opciones = json_decode($pregunta->opciones);
                    $pregunta->opciones = collect();
                    foreach ($opciones as $op) {
                        $opcion = new \stdClass();
                        $opcion->respuesta = $op;
                        $opcion->selected = false;
                        $pregunta->opciones->push($opcion);
                    }
                    $pregunta->respuesta = '';
                } else {
                    $pregunta->respuesta = '';
                }
            }
            return view('encuesta', ['user' => $usuario, 'preguntas' => $preguntas, 'urls' => $urls]);
        } else {
            return redirect('home');
        }
    }

    public function save(Request $request)
    {
        \DB::beginTransaction();
        $user = $request->user();
        if ($user->inicio_reto != null) {
            Respuesta::where('usuario_id', $user->id)->delete();
        }
        foreach ($request->respuestas as $respuesta) {
            $guardar = new Respuesta();
            $guardar->pregunta_id = $respuesta['id'];
            $guardar->usuario_id = $request->user()->id;
            $guardar->respuesta = json_encode($respuesta['respuesta'], JSON_UNESCAPED_UNICODE);
            $guardar->save();
        }
        $user->encuestado = true;
        $ignorar = collect();//Generar dieta
        $preguntaAlimentos = Pregunta::where('pregunta', 'like', '%no quiero%')->get();
        $respuestas = Respuesta::where('usuario_id', $user->id)->get()->keyBy('pregunta_id');
        foreach ($preguntaAlimentos as $preguntaAlimento) {
            foreach (json_decode($respuestas->get($preguntaAlimento->id)->respuesta) as $item) {
                if ($item == 'Pollo' || $item == 'Pavo')
                    $ignorar->push("Pechuga de $item");
                else if ($item == 'Huevo')
                    $ignorar->push("Claras de $item");
                $ignorar->push($item);
            }
        }
        $alimentosIgnorados = Dieta::whereIn('comida', $ignorar)->get()->pluck('id');
        $sexo = Pregunta::where('pregunta', 'like', '%Sexo%')->first();
        $objetivo = Pregunta::where('pregunta', 'like', '%Objetivo fitness%')->first();
        $preguntaPeso = Pregunta::where('pregunta', 'like', '%peso%')->first();
        $objetivo = strpos($respuestas->get($objetivo->id)->respuesta, "Bajar") ? 'bajar' : 'subir';
        $sexo = json_decode($respuestas->get($sexo->id)->respuesta);
        $peso = json_decode($respuestas->get($preguntaPeso->id)->respuesta);
        $user->genero = $sexo[0] == 'H' ? Genero::HOMBRE : Genero::MUJER;
        $user->objetivo = $objetivo == 'bajar' ? 0 : 1;
        $user->save();

        if ($user->inicio_reto == null) {
            $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 1);
            $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 2);
            if ($user->rol == RolUsuario::CLIENTE) {
                $this->agregarKit($user, 2);
            }
        } else {
            $dietaAnterior = UsuarioDieta::where('usuario_id', $user->id)->where('dieta', '>', 1)->get()->last();
            if ($user->rol == RolUsuario::CLIENTE) {
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $dietaAnterior->dieta + 1);
                $this->agregarKit($user);
            } else {
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 1);
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 2);
            }
        }
        $user->save();

        \DB::commit();
        return response()->json(['respuesta' => 'ok']);
    }

    public function buscarComidas($objetivo, $proteinas_permitidas)
    { //buscar las comidas a las que pertenece cada tipocomida
        $relacionProteina = Dieta::select('dietas.comida', 'rd.comida as tipo')
            ->join('rango_dietas as rd', 'dietas.id', 'rd.dieta_id')
            ->where('rd.tipo', $objetivo)->where('dietas.tipo', 1)->distinct()->get();

        $comidas = $relacionProteina->map(function ($relacion) use ($proteinas_permitidas) {
            foreach ($proteinas_permitidas as $proteina_perm) {
                if (strpos($relacion->comida, $proteina_perm) !== false)
                    return $relacion->tipo;
            }
        });
        $comidas = $comidas->unique()->filter(function ($comida) {
            return $comida != null;
        })->values()->toArray();
        return $comidas;
    }

    public function checarAlimentosIgnorados($alimentosIgnorados, $peso, $txtobjetivo)
    {
        //revisa que todas las comidas tengan almenos una proteina
        $alimentosIgnorados = array_flip($alimentosIgnorados->toArray()); //cambiar arreglo de key por valor

        $rango_id = Rango::whereRaw('? between inicio and fin', [$peso > 120 ? 120 : $peso])->get()->first()->id;
        //traer todas las comidas pero solo con sus proteinas
        $comidasProteina = Dieta::select('dietas.id', 'dietas.comida as nombre', 'rd.comida as tipo', 'rd.comida')
            ->join('rango_dietas as rd', 'dietas.id', 'rd.dieta_id')->join('rangos as r', 'rd.rango_id', 'r.id')
            ->where('r.id', $rango_id)->where('rd.tipo', $txtobjetivo)->where('dietas.tipo', 1)->whereNull('rd.deleted_at')
            ->distinct()->get()->groupBy('comida');

        foreach ($comidasProteina as $comida) { //compara cada comida original con la ignorada
            $sizeComida = count($comida);
            if ($sizeComida > 1) { //si la comida es mayor de 1 no necesita comparar
                $count = 0;
                $proteina_id = collect();
                foreach ($comida as $proteina) { //busca si la key existe como id de la proteina original
                    if (key_exists($proteina->id, $alimentosIgnorados)) {
                        $count++;
                        $proteina_id->push($proteina->id);
                    }
                }
                if ($count == $sizeComida) { //si encuentra que se seleccionaron todas escoge una
                    for ($i = 0; $i < ($sizeComida - 1); $i++) {
                        $random = rand(0, ($sizeComida - 1));
                        unset($alimentosIgnorados[$proteina_id[$random]]);
                    }
                }
            }
        }
        //regresa el arreglo como estaba y lo indexa otra ves por los valores perdidos con el unset
        $nuevosIgnorados = collect(array_flip($alimentosIgnorados))->values();

        return $nuevosIgnorados;
    }

    public function generarDieta($user, $txtobjetivo, $peso, $alimentosIgnorados, $numDieta)
    {
        $now = Carbon::now();
        $alimentosIgnorados = $this->checarAlimentosIgnorados($alimentosIgnorados, $peso, $txtobjetivo);
        $rango = Rango::with(['rango_dietas' => function ($query) use ($txtobjetivo, $alimentosIgnorados) {
            $query->where('tipo', $txtobjetivo);
            $query->whereNull('deleted_at');
            if (count($alimentosIgnorados) != 0) {
                $query->whereNotIn('dieta_id', $alimentosIgnorados);
            }
        }, 'rango_dietas.dieta'])->whereRaw('? between inicio and fin', [$peso > 120 ? 120 : $peso])->first();
        $comidas = $rango->rango_dietas->groupBy('comida');
        $dieta = collect();
        foreach ($comidas as $iComida => $comida) {
            $dieta->put($iComida, collect());
            $tipos = $comida->groupBy('dieta.tipo');
            foreach ($tipos as $iTipo => $tipo) {
                $limite = $tipo->count() - 1;
                $i = rand(0, $limite);
                $alimento = $tipo[$i]->dieta;
                $dieta->get($iComida)->push($alimento->gramos == '' ? ($alimento->comida) : ("$alimento->gramos $alimento->comida"));
            }
        }
        $dietaAnterior = collect();
        $i = 0; //Se agrega codigo para sobreescribir la dieta del administrador para que pueda probar diferentes combinaciones de dietas
        $numAlimentos = 0;
        if ($user->rol == RolUsuario::ADMIN) {
            $dietaAnterior = UsuarioDieta::where('usuario_id', $user->id)->where('dieta', $numDieta)->get();
            foreach ($dietaAnterior as $anterior) {
                $anterior->deleted_at = $now;
                $anterior->save();
            }
            $numAlimentos = $dietaAnterior->count();
        }
        foreach ($dieta as $index => $comida) {
            foreach ($comida as $alimento) {
                if ($i < $numAlimentos) {
                    $usuarioDieta = $dietaAnterior[$i];
                } else {
                    $usuarioDieta = new UsuarioDieta();
                    $usuarioDieta->usuario_id = $user->id;
                }
                $usuarioDieta->comida = $index;
                $usuarioDieta->alimento = $alimento;
                $usuarioDieta->dieta = $numDieta;
                $usuarioDieta->deleted_at = null;
                $usuarioDieta->save();
                $i++;
            }
        }
    }

    public function validarAbiertas(Request $request)
    {
        $preguntas = collect($request->all())->keyBy('pregunta')->toArray();

        Validator::make($preguntas,
            [
                'Peso en Kg.respuesta' => ['required', 'numeric', 'min:40', 'max:180',
                    'regex:/^([1-9]([0-9]{1,2}))(\.([0-9][0-9]?))?$/'],
                'Estatura en cm.respuesta' => 'required|numeric|min:100|max:230|integer',
            ],
            [
                'Peso en Kg.respuesta.required' => 'El peso en kg es requerido',
                'Peso en Kg.respuesta.numeric' => 'Debe capturar solo números',
                'Peso en Kg.respuesta.min' => 'Debe ingresar un valor mínimo de 40',
                'Peso en Kg.respuesta.max' => 'Debe ingresar un valor máximo de 180',
                'Peso en Kg.respuesta.regex' => 'Debe capturar máximo 3 enteros y hasta 2 decimales',
                'Estatura en cm.respuesta.required' => 'La estatura en cm es requerida',
                'Estatura en cm.respuesta.numeric' => 'Debe capturar solo números',
                'Estatura en cm.respuesta.min' => 'Debe ingresar un valor mínimo de 100',
                'Estatura en cm.respuesta.max' => 'Debe ingresar un valor máximo de 230',
                'Estatura en cm.respuesta.integer' => 'Debe capturar números enteros',
            ]
        )->validate();
    }

    public function faqs()
    {
        return view("faqs");
    }

    public function webhook(Request $request)
    {
        Log::info($request->object);
        return "ok";
    }


    public function agregarKit($usuario, $No_kits = 1)
    {
        if ($No_kits == 2) {
            $kit_id = Kits::select('id')->where('objetivo', $usuario->objetivo)->where('genero', $usuario->genero)->get();
            $dias = (int)env('DIAS') / 2;
            foreach ($kit_id as $index => $kit) { //agregar kits
                $usuario_kit = new UsuarioKit();
                $usuario_kit->user_id = $usuario->id;
                $usuario_kit->kit_id = $kit->id;
                if ($index == 0) { //fecha para el primer kit
                    $usuario_kit->fecha_inicio = Carbon::now();
                    $usuario_kit->fecha_fin = Carbon::now()->addDays($dias);
                } else { //fecha para los kits siguientes
                    $usuario_kit->fecha_inicio = Carbon::now()->addDays($dias + 1);
                    $dias += $dias;
                    $usuario_kit->fecha_fin = Carbon::now()->addDays($dias);
                }
                $usuario_kit->save();
            }
        } else { //escoger un solo kit descpues de la reinscripcion
            $usuario_kit = UsuarioKit::where('user_id', $usuario->id)->get();
            $kit_1 = $usuario_kit[0];
            $kit_2 = $usuario_kit[1];
            $kit_delete = rand(0, 1);
            UsuarioKit::where('id', ($kit_delete == 0 ? $kit_1['id'] : $kit_2['id']))->delete();
            $kit_elegido = UsuarioKit::where('user_id', $usuario->id);
            $kit_elegido->fecha_inicio = Carbon::now();
            $kit_elegido->fecha_inicio = Carbon::now()->addDays((int)env('DIAS') / 2);
        }
    }

    public function etapa1($id)
    {
        $contacto = Contacto::find($id);
        $urls = collect();
        $photos = Storage::disk('local')->files('public/combos');
        $pregunta = Pregunta::select('id', 'pregunta', 'opciones')->where('id', TipoRespuesta::PREGUNTAS_REGISTRO[0])->get()->first();
        $pregunta->pregunta = strtolower($pregunta->pregunta);
        $opciones = collect();
        for ($i = 0, $respuesta = json_decode($pregunta->opciones); $i < count($respuesta); $i++) {
            $opciones->push([
                'nombre' => $respuesta[$i],
                'selected' => false
            ]);
        }
        $pregunta->opciones = $opciones->toArray();
        foreach ($photos as $photo) {
            $nombre = explode('/', $photo);
            $nombre = $nombre[count($nombre) - 1];
            $urls->push(url("getCombo/" . $nombre));
        }
        return view("auth.objetivo", ['urls' => $urls, 'pregunta' => $pregunta, 'contacto' => $contacto,
            'monto' => env('COBRO_ORIGINAL'), 'descuento' => env('COBRO')]);
    }

    public function etapa2($id)
    {
        $contacto = Contacto::find($id);
        $urls = collect();
        $photos = Storage::disk('local')->files('public/combos');
        foreach ($photos as $photo) {
            $nombre = explode('/', $photo);
            $nombre = $nombre[count($nombre) - 1];
            $urls->push(url("getCombo/" . $nombre));
        }
        return view("auth.peso", ['urls' => $urls, 'contacto' => $contacto, 'monto' => env('COBRO_ORIGINAL'),
            'descuento' => env('COBRO')]);
    }

    public function etapa3($id)
    {
        $contacto = Contacto::find($id);
        $urls = collect();
        $photos = Storage::disk('local')->files('public/combos');
        foreach ($photos as $photo) {
            $nombre = explode('/', $photo);
            $nombre = $nombre[count($nombre) - 1];
            $urls->push(url("getCombo/" . $nombre));
        }
        return view("auth.ultimo", ['urls' => $urls, 'contacto' => $contacto, 'monto' => env('COBRO_ORIGINAL'),
            'descuento' => env('COBRO')]);
    }

    public function terminos()
    {
        return view('auth.terminos');
    }

    public function contacto()
    {
        $contacto = new Contacto();
        $contacto->nombres = "";
        $contacto->apellidos = "";
        $contacto->email = "";
        $contacto->telefono = "";
        $contacto->objetivo = "";
        $contacto->mensaje = "";
        return view('auth.contacto', ["contacto" => $contacto, 'objetivos' => Objetivo::all()]);
    }

    public function contactoSave(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'nombres' => ['required', 'max:100', 'min:2', 'regex:/^([a-zA-ZñÑáéíóúÁÉÍÓÚ\s]( )?)+$/'],
                'apellidos' => 'required|max:100|min:2|regex:/^([a-zA-ZñÑáéíóúÁÉÍÓÚ\s]( )?)+$/',
                'email' => 'required|max:100|min:3|email',
                'telefono' => 'nullable|numeric|max:9999999999|integer',
                'mensaje' => 'required|max:500',
            ], [
                'nombres.required' => 'Es necesario que captures tu nombre',
                'apellidos.required' => 'Es necesario que captures por lo menos tu primer apellido',
                'email.required' => 'Es necesario que captures tu correo electrónico',
                'mensaje.required' => 'Es necesario que captures el mensaje que nos quieres dar',
                'nombres.max' => 'El nombre debe ser menor a 100 caracteres',
                'apellidos.max' => 'Los apellidos deben ser menor a 100 caracteres',
                'email.max' => 'La dirección de correo debe ser menor a 100 caracteres',
                'mensaje.max' => 'El mensaje debe ser menor a 500 caracteres',
                'telefono.max' => 'El teléfono debe ser menor a 20 caracteres',
            ]);
        $validator->after(function ($validator) use ($request) {
            curl_setopt_array($ch = curl_init(), array(
                    CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
                    CURLOPT_POST => TRUE,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_POSTFIELDS => array(
                        'secret' => '6Ley_MAUAAAAAAgsyBzBZhvwz-AE1fObmbvMwV47',
                        'response' => $request->response,
                    )
                )
            );
            $response = json_decode(curl_exec($ch));
            curl_close($ch);
            if ($response->success != 'true') {
                $validator->errors()->add('captcha', 'Debes seleccionar el captcha');
            }
        });
        $validator->validate();
        $contacto = Contacto::where('email', $request->email)->first();
        if ($contacto === null) {
            $contacto = new Contacto();
            $contacto->email = $request->email;
        }
        $contacto->nombres = $request->nombres;
        $contacto->apellidos = $request->apellidos;
        $contacto->telefono = $request->telefono;
        $contacto->objetivo = strpos("Bajar", $request->objetivo) !== false ? Objetivo::BAJAR : Objetivo::SUBIR;
        $contacto->mensaje = $request->mensaje;
        $contacto->etapa = 1;
        $contacto->medio = "Contacto";
        $contacto->save();
        return response()->json(['status' => 'ok', 'redirect' => url('/')]);
    }
}
