<?php

namespace App\Http\Controllers;

use App\Code\Genero;
use App\Code\Objetivo;
use App\Code\RolUsuario;
use App\Code\TipoPago;
use App\Code\TipoRespuesta;
use App\Code\Utils;
use App\Code\ValidarCorreo;
use App\Compra;
use App\Console\Commands\Mailchimp;
use App\Contacto;
use App\Dieta;
use App\EncuestaEntrada;
use App\Renovaciones;
use App\Events\EnviarCorreosEvent;
use App\Events\EnviarDudasEvent;
use App\Kits;
use App\Pago;
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
        $usuario->intereses = str_replace(',', ', ', $usuario->intereses);
        $usuario->idiomas = str_replace(',', ', ', $usuario->idiomas);
        $referencias = User::select(['id', 'name', 'email', 'created_at', 'num_inscripciones'])
            ->where('codigo', $request->user()->referencia)
            ->where('pagado', true)->whereNotNull('codigo')->get();

        $monto = env('COBRO_REFRENDO1');
        $original = env('COBRO_REFRENDO1');

        if ($usuario->dias == 14){
            $monto = env('COBRO_REFRENDO1');
            $original = env('COBRO_REFRENDO1');
        }
        if ($usuario->dias == 28){
            $monto = env('COBRO_REFRENDO2');
            $original = env('COBRO_REFRENDO2');
        }
        if ($usuario->dias == 56){
            $monto = env('COBRO_REFRENDO3');
            $original = env('COBRO_REFRENDO3');
        }
        if ($usuario->dias == 84){
            $monto = env('COBRO_REFRENDO4');
            $original = env('COBRO_REFRENDO4');
        }
        $descuento = 0;
        return view('home', ['usuario' => $usuario, 'referencias' => $referencias,
            'monto' => $monto, 'descuento' => $descuento, 'original' => $original, 'saldo' => $usuario->saldo]);
    }

    public function index()
    {
        return view('welcome');
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
        $nombre = Utils::quitarTildes($nombre);
        return response()->file(storage_path("app/public/optimized/$nombre.mp4"));
    }

    public function encuesta(Request $request)
    {
        $user = $request->user();
        if ($user->rol == RolUsuario::ADMIN || ($user->pagado && !$user->encuestado) || !$user->validado) {
            $usuario = $request->user();
            $usuario->medio = "";
            $preguntas = Pregunta::select(['id', 'pregunta', 'opciones', 'multiple', 'ayuda'])->get();
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
        $dialunes = Carbon::parse("monday next week");
        $user->inicio_reto = $dialunes;
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
        $preguntaAlimentos = Pregunta::where('pregunta', 'like', '%Eliminar de mi dieta lo siguiente%')->get();
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
        $objetivo = Pregunta::where('pregunta', 'like', '%Mi objetivo%')->first();
        $preguntaPeso = Pregunta::where('pregunta', 'like', '%peso a%')->first();
        $preguntaPesoIdeal = Pregunta::where('pregunta', 'like', '%peso ideal%')->first();
        $objetivo = strpos($respuestas->get($objetivo->id)->respuesta, "Bajar de peso rápidamente") ? 'bajar' : 'subir';
        $sexo = json_decode($respuestas->get($sexo->id)->respuesta);
        $peso = json_decode($respuestas->get($preguntaPeso->id)->respuesta);
        $user->genero = $sexo[0] == 'H' ? Genero::HOMBRE : Genero::MUJER;
        $user->peso = $peso;
        $user->peso_ideal = json_decode($respuestas->get($preguntaPesoIdeal->id)->respuesta);
        $user->objetivo = $objetivo == 'Bajar de peso rápidamente' ? 0 : 1;
        $user->save();

        if ($user->inicio_reto == null) { //Se generan 4 dietas a lo largo del reto
            if($user->dias == 14){
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 1);
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 2);
            }
            if($user->dias == 28) {
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 1);
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 2);
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 3);
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 4);
            }
            if($user->dias == 56) {
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 1);
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 2);
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 3);
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 4);
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 5);
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 6);
            }
            if($user->dias == 84) {
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 1);
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 2);
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 3);
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 4);
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 5);
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 6);
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 7);
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 8);
            }
            if ($user->rol == RolUsuario::CLIENTE) {
                $this->agregarKit($user, 2);
            }
        } else {
            $dietaAnterior = UsuarioDieta::where('usuario_id', $user->id)->where('dieta', '>', 1)->get()->last();
            if ($user->rol == RolUsuario::CLIENTE) {
                $numDieta = $dietaAnterior == null ? 1 : $dietaAnterior->dieta + 1;
                $renovacion = Renovaciones::where('usuario_id', $user->id)->get()->last();
                if($renovacion == null) {
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta);
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta + 1);
                }else{
                    if($user->dias == 14){
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta);
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta+1);
                    }
                    if($user->dias == 28) {
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta);
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta+1);
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta+2);
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta+3);
                    }
                    if($user->dias == 56) {
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta);
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta+1);
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta+2);
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta+3);
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta+4);
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta+5);
                    }
                    if($user->dias == 84) {
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta);
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta+1);
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta+2);
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta+3);
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta+4);
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta+5);
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta+6);
                        $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $numDieta+7);
                    }
                    if ($renovacion->rol == RolUsuario::CLIENTE) {
                        $this->agregarKit($user, 2);
                    }
                }
                $kits = UsuarioKit::where('user_id', $user->id)->get();
                $this->agregarKit($user, $kits->count() == 0 ? 2 : 1);
            } else {
                if($user->dias == 14){
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 1);
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 2);
                }
                if($user->dias == 28) {
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 1);
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 2);
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 3);
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 4);
                }
                if($user->dias == 56) {
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 1);
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 2);
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 3);
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 4);
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 5);
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 6);
                }
                if($user->dias == 84) {
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 1);
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 2);
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 3);
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 4);
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 5);
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 6);
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 7);
                    $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, 8);
                }
                if ($user->rol == RolUsuario::CLIENTE) {
                    $this->agregarKit($user, 2);
                }
            }
        }
        $user->save();

        \DB::commit();
        return response()->json(['respuesta' => 'ok', 'peso' => $peso, 'peso_ideal' => $user->peso_ideal]);
    }

    public function generarDietaUsuario($usr)
    {

        $ignorar = collect();//Generar dieta
        $user = User::where('id', $usr)->get()->first();
        $preguntaAlimentos = Pregunta::where('pregunta', 'like', '%Eliminar de mi dieta lo siguiente%')->get();
        $respuestas = Respuesta::where('usuario_id', $usr)->get()->keyBy('pregunta_id');
        foreach ($preguntaAlimentos as $preguntaAlimento) {
            foreach (json_decode($respuestas->get($preguntaAlimento->id)->respuesta) as $item) {
                if ($item == 'Pollo' || $item == 'Pavo')
                    $ignorar->push("Pechuga de $item");
                else if ($item == 'Huevo')
                    $ignorar->push("Claras de $item");
                $ignorar->push($item);
            }
        }
        $ignorar->push(24);
        var_dump('iijij');
        $alimentosIgnorados = Dieta::whereIn('comida', $ignorar)->get()->pluck('id');
        $sexo = Pregunta::where('pregunta', 'like', '%Sexo%')->first();
        $objetivo = Pregunta::where('pregunta', 'like', '%Objetivo fitness%')->first();
        var_dump('iijij');
        $preguntaPeso = Pregunta::where('pregunta', 'like', '%peso%')->first();
        $objetivo = strpos($respuestas->get($objetivo->id)->respuesta, "Bajar") ? 'bajar' : 'subir';
        $sexo = json_decode($respuestas->get($sexo->id)->respuesta);
        $peso = json_decode($respuestas->get($preguntaPeso->id)->respuesta);
        var_dump('iijij');


        $dietaAnterior = UsuarioDieta::where('usuario_id', $user->id)->where('dieta', '>', 1)->get()->last();
        if ($user->rol == RolUsuario::CLIENTE) {
            $numDieta = $dietaAnterior == null ? 1 : $dietaAnterior->dieta + 1;
            $days = $user->dias/7;
            var_dump('iijij');
            for ($i=$numDieta-1; $i<$days; $i++) {
                var_dump('iijij');
                $this->generarDieta($user, $objetivo, $peso, $alimentosIgnorados, $i);
            }
        }
        var_dump('iijij');


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
                'Peso actual.respuesta' => ['required', 'numeric', 'min:40', 'max:180',
                    'regex:/^([1-9]([0-9]{1,2}))(\.([0-9][0-9]?))?$/'],
                'Estatura.respuesta' => 'required|numeric|min:100|max:230|integer',
                'Cual seria tu peso ideal.respuesta' => ['required', 'numeric', 'min:40', 'max:180',
                    'regex:/^([1-9]([0-9]{1,2}))(\.([0-9][0-9]?))?$/'],
            ],
            [
                'Peso actual.respuesta.required' => 'El peso en kg es requerido',
                'Peso actual.respuesta.numeric' => 'Debe capturar solo números',
                'Peso actual.respuesta.min' => 'Debe ingresar un valor mínimo de 40',
                'Peso actual.respuesta.max' => 'Debe ingresar un valor máximo de 180',
                'Peso actual.respuesta.regex' => 'Debe capturar máximo 3 enteros y hasta 2 decimales',
                'Estatura.respuesta.required' => 'La estatura en cm es requerida',
                'Estatura.respuesta.numeric' => 'Debe capturar solo números',
                'Estatura.respuesta.min' => 'Debe ingresar un valor mínimo de 100',
                'Estatura.respuesta.max' => 'Debe ingresar un valor máximo de 230',
                'Estatura.respuesta.integer' => 'Debe capturar números enteros',
                'Cual seria tu peso ideal.respuesta.required' => 'El peso ideal es requerido',
                'Cual seria tu peso ideal.respuesta.numeric' => 'Debe capturar solo números',
                'Cual seria tu peso ideal.respuesta.min' => 'Debe ingresar un valor mínimo de 40',
                'Cual seria tu peso ideal.respuesta.max' => 'Debe ingresar un valor máximo de 180',
                'Cual seria tu peso ideal.respuesta.regex' => 'Debe capturar máximo 3 enteros y hasta 2 decimales',
            ]
        )->validate();
    }

    public function validarAbiertasdos(Request $request)
    {
        $preguntas = collect($request->all())->keyBy('pregunta')->toArray();


        Validator::make($preguntas,
            [
                '¿Porqué quiere obtener esta asesoria?.respuesta' => 'required',
                'Pasatiempo favorito.respuesta' => 'required',
                'Un logro del cúal te sientas orgulloso.respuesta' => 'required',
                '3 cualidades que veas en ti.respuesta' => 'required',
            ],
            [
                '¿Porqué quiere obtener esta asesoria?.respuesta.required' => 'Es requerido',
                '¿A que te dedicas?.respuesta.required' => 'Es requerido',
                'Un logro del cúal te sientas orgulloso.respuesta.required' => 'Es requerido',
                '3 cualidades que veas en ti.respuesta.required' => 'Es requerido',
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
            $dias = intval(env('DIAS2') - 1);
            $dia = -1;
            foreach ($kit_id as $kit) { //agregar kits
                $d = $dia + 1;
                $usuario_kit = new UsuarioKit();
                $usuario_kit->user_id = $usuario->id;
                $usuario_kit->kit_id = $kit->id;
                $usuario_kit->fecha_inicio = Carbon::now()->startOfDay()->addDays($d);
                $usuario_kit->fecha_fin = Carbon::now()->startOfDay()->addDays($d + $dias);
                $usuario_kit->save();
                $dia += $dias;
            }
        } else { //escoger un solo kit descpues de la reinscripcion
            $kits = UsuarioKit::withTrashed()->where('user_id', $usuario->id)->get();
            $eliminado = rand(0, 1);
            $restaurado = $eliminado == 0 ? 1 : 0;
            $kits->get($eliminado)->delete();
            $kits->get($restaurado)->deleted_at = null;
            $kits->get($restaurado)->fecha_inicio = Carbon::now();
            $kits->get($restaurado)->fecha_fin = Carbon::now()->addDays((int)env('DIAS') / 2);
            $kits->get($restaurado)->save();
        }
    }

    public function etapa1($email)
    {
        $contacto = Contacto::where('email', $email)->first();
        if ($contacto === null) {
            abort(404);
        }
        $urls = collect();
        $photos = Storage::disk('local')->files('public/combos');
        $usuario = User::withTrashed()->orderBy('created_at')->where('email', $contacto->email)->get()->last();
        $cobro = User::calcularMontoCompra($contacto->codigo, $contacto->email,
            $usuario == null ? null : $usuario->created_at,
            $usuario == null ? null : $usuario->fecha_inscripcion,
            $usuario == null ? null : $usuario->inicio_reto, $usuario == null ? null : $usuario->deleted_at);
        $mensaje = $usuario == null ? '' : 'Este usuario ya pertenece al RETO ACTON.';
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
            'original' => $cobro->original, 'descuento' => $cobro->descuento, 'monto' => $cobro->monto, 'mensaje' => $mensaje]);
    }

    public function etapa2($email)
    {
        $contacto = Contacto::where('email', $email)->first();
        if ($contacto === null) {
            abort(404);
        }
        $usuario = User::withTrashed()->orderBy('created_at')->where('email', $contacto->email)->get()->last();
        $cobro = User::calcularMontoCompra($contacto->codigo, $contacto->email,
            $usuario == null ? null : $usuario->created_at,
            $usuario == null ? null : $usuario->fecha_inscripcion,
            $usuario == null ? null : $usuario->inicio_reto, $usuario == null ? null : $usuario->deleted_at);
        $mensaje = $usuario == null ? '' : 'Este usuario ya pertenece al RETO ACTON.';
        $urls = collect();
        $photos = Storage::disk('local')->files('public/combos');
        foreach ($photos as $photo) {
            $nombre = explode('/', $photo);
            $nombre = $nombre[count($nombre) - 1];
            $urls->push(url("getCombo/" . $nombre));
        }
        $contacto->peso = "";
        $contacto->ideal = "";
        return view("auth.peso", ['urls' => $urls, 'contacto' => $contacto,
            'original' => $cobro->original, 'descuento' => $cobro->descuento, 'monto' => $cobro->monto, 'mensaje' => $mensaje]);
    }

    public function etapa3($email)
    {
        $contacto = Contacto::where('email', $email)->first();
        if ($contacto === null) {
            abort(404);
        }
        $usuario = User::withTrashed()->orderBy('created_at')->where('email', $contacto->email)->get()->last();
        $cobro = User::calcularMontoCompra($contacto->codigo, $contacto->email,
            $usuario == null ? null : $usuario->created_at,
            $usuario == null ? null : $usuario->fecha_inscripcion,
            $usuario == null ? null : $usuario->inicio_reto, $usuario == null ? null : $usuario->deleted_at);
        $mensaje = $usuario == null ? '' : 'Este usuario ya pertenece al RETO ACTON.';
        $urls = collect();
        $photos = Storage::disk('local')->files('public/combos');
        foreach ($photos as $photo) {
            $nombre = explode('/', $photo);
            $nombre = $nombre[count($nombre) - 1];
            $urls->push(url("getCombo/" . $nombre));
        }
        return view("auth.ultimo", ['urls' => $urls, 'contacto' => $contacto,
            'original' => $cobro->original, 'descuento' => $cobro->descuento, 'monto' => $cobro->monto, 'mensaje' => $mensaje]);
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
                'mensaje.required' => 'Es necesario que captures el mensaje que nos quieres dar',
                'mensaje.max' => 'El mensaje debe ser menor a 500 caracteres',
                'telefono.max' => 'El teléfono debe ser menor a 10 caracteres',
                'telefono.numeric' => 'El teléfono debe contener caracteres númericos',
            ]);
        $validator->after(function ($validator) use ($request) {
            curl_setopt_array($ch = curl_init(), array(
                    CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
                    CURLOPT_POST => TRUE,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_POSTFIELDS => array(
                        'secret' => env('CAPTCHA_PRIVATE'),
                        'response' => $request->response,
                    )
                )
            );
            $response = json_decode(curl_exec($ch));
            curl_close($ch);
            if ($response->success != 'true') {
                $validator->errors()->add('captcha', 'Debes seleccionar el captcha');
            }
            if (ValidarCorreo::validarCorreo($request->email)) {
                $validator->errors()->add("email", "El email debe tener formato correcto");
            }
        });
        $validator->validate();
        $contacto = Contacto::withTrashed()->where("email", $request->email)->first();
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
        event(new EnviarDudasEvent($contacto, 'contacto'));
        return response()->json(['status' => 'ok', 'redirect' => url('/')]);
    }

    public function dudas(Request $request)
    {
        $user = $request->user();
        $user->mensaje = "";
        return view('auth.dudas', ["contacto" => $user]);
    }

    public function saveDudas(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'mensaje' => 'required|max:500',
            ], [
                'mensaje.required' => 'Es necesario que captures el mensaje que nos quieres dar',
                'mensaje.max' => 'El mensaje debe ser menor a 500 caracteres',
            ]);
        $validator->after(function ($validator) use ($request) {
            curl_setopt_array($ch = curl_init(), array(
                    CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
                    CURLOPT_POST => TRUE,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_POSTFIELDS => array(
                        'secret' => env('CAPTCHA_PRIVATE'),
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
        $contacto->mensaje = $request->mensaje;
        $contacto->save();
        event(new EnviarDudasEvent($contacto, 'cliente'));
        return response()->json(['status' => 'ok', 'redirect' => url('/')]);
    }

    public function verPagos(User $user)
    {
        $pagos = Compra::where('usuario_id', $user->id)->orderBy('created_at')->get(['created_at','pagado']);
        return $pagos;
    }

    public function subirArchivo1(Request $request)
    {
        $user = $request->user();
        $request->validate([

            'file' => 'required|mimes:jpg,png|max:2048',

        ]);

        $fileName = $user->id.'_1.jpg';
        $result = [];
        $result['error'] = '';

        try {
            $request->file->move(public_path('images/'), $fileName);
            $user->archivo_validacion_1 = "images/$fileName";
            $user->save();
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
            return json_encode($result);
        }

        return json_encode($result);
    }

    public function subirArchivo2(Request $request)
    {
        $user = $request->user();
        $request->validate([

            'file' => 'required|mimes:jpg,png|max:2048',

        ]);


        $fileName = $user->id.'_2.jpg';
        $result = [];
        $result['error'] = '';

        try {
            $request->file->move(public_path('images/'), $fileName);
            $user->archivo_validacion_2 = "images/$fileName";
            $user->save();
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
            return json_encode($result);
        }

        return json_encode($result);
    }

    public function enviarValidacion(Request $request)
    {
        $user = $request->user();


        try {
            $enviado = $user->enviado_validacion;
            if($enviado == 0) {
                $user->enviado_validacion = 1;
            }
            if($enviado == 1) {
                $user->enviado_validacion = 2;
            }
            $user->save();
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
            return json_encode($result);
        }

        return json_encode("{'ok': 'pk'}");
    }

    public function validaAdmin($id)
    {
        $user = User::where('id', $id)->first();
        print_r($user);


        try {
            $user->enviado_validacion = 2;
            $user->save();
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
            return json_encode($result);
        }

        return json_encode("{'ok': 'pk'}");
    }

    public function enviarRechazo($id)
    {
        $user = User::where('id', $id)->first();


        try {
            $user->enviado_validacion = 0;
            $user->save();
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
            return json_encode($result);
        }

        return json_encode("{'ok': 'pk'}");
    }



    public function encuestaEntrada(Request $request)
    {
        $preguntas = EncuestaEntrada::select(['id', 'pregunta', 'opciones', 'multiple', 'excluye'])->get();
        $photos = Storage::disk('local')->files('public/img');
        $urls = collect();
        foreach ($photos as $photo) {
            $nombre = explode('/', $photo);
            $nombre = $nombre[count($nombre) - 1];
            $urls->push(url("getImagen/" . $nombre));
        }
        foreach ($preguntas as $pregunta) {
            $pregunta->mostrar = false;
            $pregunta->excluye = $pregunta->excluye;

            if ($pregunta->multiple == 1) { //De multiples Selecciones
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
            } else if ($pregunta->multiple === 0) { //De una seleccion Respuesta
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
        return view('encuesta_entrada', ['preguntas' => $preguntas, 'urls' => $urls]);

    }
}

