<?php

namespace App\Http\Controllers;

use App\Code\LugarEjercicio;
use App\Code\RolUsuario;
use App\Code\Utils;
use App\Dia;
use App\Dieta;
use App\Suplemento;
use App\User;
use App\UsuarioDia;
use App\UsuarioDieta;
use Carbon\Carbon;
use FontLib\EOT\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Validator;

use App\Pregunta;
use App\Respuesta;

class RetoController extends Controller
{

    public function index(Request $request)
    {
        $usuario = $request->user();
        $usuarioDias = UsuarioDia::where('usuario_id', $usuario->id)->orderByDesc('dia_id')
            ->get()->first();
        //$diasReto = intval(env('DIAS'));
        $diasReto = intval($usuario->dias);
        if ($usuarioDias == null) {
            $usuarioDias = 1;
        } else {
            $usuarioDias = $usuarioDias->dia_id;
        }
        if ($usuarioDias < $diasReto) {
            $usuarioDias = $diasReto;
        }
        if ($usuarioDias == 0) {
            $semana = 1;
        } else {
            $semana = $usuarioDias % 7 == 0 ? intval($usuarioDias / 7) : intval($usuarioDias / 7) + 1;
        }
        $dias = $this->getSemana($usuario, $semana);

        return view('reto.configuracion', ['dias' => $dias, 'semana' => $semana, 'maximo' => $usuarioDias,
            'teorico' => $diasReto]);
    }

    public function getSemana(User $usuario, $semana)
    {
        $dias = collect();

        if ($usuario->inicio_reto == '') {//crear inicio del reto
            Storage::makeDirectory('public/reto/' . $usuario->id);
        }
        $usuarioDias = UsuarioDia::where('usuario_id', $usuario->id)->get()->keyBy('dia_id');

        for ($i = 1; $i <= 7; $i++) {//construir arreglo y ruta de las imagenes para la vista
            $dia = (7 * ($semana - 1)) + $i;
            $imagenDia = $usuarioDias->get($dia);
            if ($imagenDia === null) {
                $imagenDia = new UsuarioDia();
                $imagenDia->comentarios = '';
                $imagenDia->comentario = '';
                $imagenDia->audio = '';
            } else {
                $diaEjemplo = Dia::find($imagenDia->dia_id) ?? new Dia();
                if (Storage::disk('local')->exists("public/reto/$usuario->id/" . $dia . '.mp3')) {
                    $imagenDia->audio = url("/reto/getAudio/reto/$usuario->id/" . $dia . "/" . Utils::generarRandomString(10) . ".mp3");
                    $imagenDia->audioOgg = url("/reto/getAudio/reto/$usuario->id/" . $dia . "/" . Utils::generarRandomString(10) . ".ogg");
                } else {
                    $imagenDia->audio = '';
                    $imagenDia->audioOgg = '';
                }
                $imagenDia->comentarios = $diaEjemplo->comentarios;
                $imagenDia->comentario = $diaEjemplo->comentarios;
            }
            $imagenDia->imagen = url("/reto/getImagen/reto/$usuario->id/" . $dia) . "/" . (Utils::generarRandomString(10));
            $imagenDia->comentar = 0;
            $imagenDia->dia = $dia;
            $imagenDia->subir = true;
            $imagenDia->loading = false;
            $dias->push($imagenDia);
        }
        return $dias;
    }

    public function getImagen($carpeta, $user_id, $dia)
    {
        if (Storage::disk('local')->exists("public/$carpeta/$user_id/$dia.jpg")) {
            return response()->file(storage_path('app/public/' . $carpeta . '/' . $user_id . '/' . $dia . '.jpg'));
        } else {
            return response()->file(public_path('/images/none.png'));
        }
    }

    public function getAudio($carpeta, $user_id, $dia, $random)
    {
        $items = explode('.', $random);
        $extension = $items[1];
        if (Storage::disk('local')->exists("public//$carpeta/$user_id/$dia.") . $extension) {
            $headers = array(
                "Content-Type: audio/$extension",
                "Content-disposition", "attachment; filename='$dia.$extension'"
            );
            return response()->file(storage_path("app/public/$carpeta/$user_id/$dia.$extension"), $headers);
        }
    }

    public function saveImagen(Request $request)
    {
        ini_set('memory_limit', '-1');
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [], []);
        $validator->after(function ($validator) use ($request) {
            $dia = $request->dia - 1;
            $extension = strtolower($request->file('imagen')->getClientOriginalExtension());
            if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {
                $size = ((($request->file('imagen')->getSize() / 1024) / 1024) * 100) / 100;
                if ($size > 20) {
                    $validator->errors()->add("imagen$dia", "El tamaño de la imagen debe ser menor a 20MB");
                }
            } else {
                $validator->errors()->add("imagen$dia", "El formato de la imagen no está permitido");
            }
        });
        $validator->validate();
        $usuario_id = $request->user()->id;
        $usuarioDia = UsuarioDia::where('dia_id', $request->dia)->where('usuario_id', $usuario_id)->first();
        if ($usuarioDia == null) { //checar es imagen nueva o ya esta registrada en tabla usuario_dia
            $usuarioDia = new UsuarioDia();
            $usuarioDia->dia_id = $request->dia;
            $usuarioDia->usuario_id = $usuario_id;
            if ($request->user()->rol == RolUsuario::ADMIN) {
                $diaDB = Dia::find($request->dia);
                if ($diaDB === null) {
                    $diaDB = new Dia();
                    $diaDB->id = $request->dia;
                    $diaDB->dia = $request->dia;
                    $diaDB->comentarios = '';
                    $diaDB->save();
                }
            }
        }
        $usuarioDia->comentario = null;
        $usuarioDia->save();
        Storage::disk('local')->makeDirectory("public/reto/$usuario_id");

        $image = \Intervention\Image\Facades\Image::make($request->file('imagen'))->orientate();
        if ($image->width() < $image->height()) {
            $image->resize(null, 720, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        } else {
            $image->resize(1280, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        $image->save(storage_path("app/public/reto/$usuario_id/$request->dia.jpg"));

        return response()->json(['respuesta' => 'ok', 'imagen' => url("/reto/getImagen/reto/$usuario_id/$request->dia/" . (Utils::generarRandomString(10)))]);
    }

    public function comentar(Request $request)
    {
        $this->validate($request, [
            'comentario' => 'max:255'
        ], [
            'comentario.max' => 'El comentario debe ser menor a 255 caracteres'
        ]);
        $usuarioDia = UsuarioDia::find($request->id);
        $usuarioDia->comentario = $request->comentario;
        $usuarioDia->save();
        return response()->json(['status' => 'ok']);
    }

    public function anotar(Request $request)
    {
        $dia = Dia::find($request->dia);
        if ($dia === null) {
            $dia = new Dia();
            $dia->id = $request->dia;
            $dia->dia = $request->dia;
        }
        $usuarioDia = UsuarioDia::where('usuario_id', $request->user()->id)->where('dia_id', $request->dia)->first();
        if ($usuarioDia === null) {
            $usuarioDia = new UsuarioDia();
            $usuarioDia->dia_id = $request->dia;
            $usuarioDia->usuario_id = $request->user()->id;
            $usuarioDia->save();
        }
        $dia->comentarios = $request->comentarios;
        $dia->save();
        return "ok";
    }

    /**
     * @param Request $request
     * @param $dia
     * @param $genero
     * @param $objetivo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dia(Request $request, $dia, $genero, $objetivo)
    {
        $user = $request->user();
        $user->modo = $user->modo == true;

        //$diasRetoOriginal = intval(env('DIAS'));
        //$diasReto = intval(env('DIAS2'));
        $diasRetoOriginal = intval($user->dias);
        $diasReto = intval($user->dias);
        $diasTranscurridos = UsuarioDia::where('usuario_id', $user->id)->count();

        $inicioReto = Carbon::parse($user->inicio_reto);
        if ($user->num_inscripciones > 1) {
            $teorico = $diasRetoOriginal + (($user->num_inscripciones - 2) * $diasReto) + Carbon::now()->startOfDay()->diffInDays($inicioReto);
            if (Carbon::parse($user->fecha_inscripcion)->startOfDay() == $inicioReto->startOfDay()) {
                $teorico++;
            }
            if ($teorico > $diasRetoOriginal + (($user->num_inscripciones - 1) * $diasReto)) {
                $teorico = $diasRetoOriginal + ($user->num_inscripciones - 1) * $diasReto;
            }
        } else {
            $teorico = Carbon::now()->startOfDay()->diffInDays($inicioReto) + 1;
            if ($teorico > $diasRetoOriginal) {
                $teorico = $diasRetoOriginal;
            }
        }
        if ($teorico == 0) {
            $semana = 1;
            $teorico++;
        } else {
            $semana = $teorico % 7 == 0 ? intval($teorico / 7) : intval($teorico / 7) + 1;
        }
        if ($semana * 7 < $teorico) {
            $dias = 7;
        } else {
            $diaInicial = ($semana * 7) - 6;
            $dias = $teorico - ($diaInicial - 1);
        }

        $usuarioDieta = UsuarioDieta::where('usuario_id', $request->user()->id)->where('dieta', '>', 1)->get()->last();
        if ($usuarioDieta == null) {
            $dia = new Dia();
            $dia->nota = "";
            $dia->ejercicioss = collect();
            //return view('reto.dia', ['dia' => $dia, 'genero' => $genero, 'objetivo' => $objetivo, 'lugar' => $user->modo,
            //'dias' => env('DIAS')]);
            return view('reto.dia', ['dia' => $dia, 'genero' => $genero, 'objetivo' => $objetivo, 'lugar' => $user->modo,
                'dias' => $user->dias, 'diasReto' => $diasReto]);
        } else {
            $sem = $dia % 7 == 0 ? intval($dia / 7) : intval($dia / 7) + 1;
            $numDieta = $sem % 2 == 0 ? intval($sem / 2) : intval($sem / 2) + 1; //Se obtiene el numero de dieta con base en la cantidad de dias del reto
            $numSemanaSuplementacion = $sem % 4 == 0 ? intval($sem / 4) : intval($sem / 4) + 1;
            $dietaCreada = UsuarioDieta::where('usuario_id', $user->id)->where('dieta', $numDieta)->count();
            if ($dietaCreada==0){
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
                $objetivo = Pregunta::where('pregunta', 'like', '%Objetivo fitness%')->first();
                $preguntaPeso = Pregunta::where('pregunta', 'like', '%peso%')->first();
                $objetivo = strpos($respuestas->get($objetivo->id)->respuesta, "Bajar") ? 'bajar' : 'subir';
                $peso = json_decode($respuestas->get($preguntaPeso->id)->respuesta);

                app('App\Http\Controllers\HomeController')->generarDieta($request->user(), $objetivo, $peso, $alimentosIgnorados, $numDieta);
            }

            $diaDB = Dia::buildDia($dia, $genero, $objetivo, $request->user(), $numDieta, $numSemanaSuplementacion);
            return view('reto.dia', ['dia' => $diaDB, 'genero' => $genero, 'objetivo' => $objetivo,
                'dias' => $dias, 'lugar' => $user->modo, 'semana' => $semana, 'maximo' => $diasTranscurridos,
                'teorico' => $teorico, 'diasReto' => $diasReto]);
        }
    }

    public function getSemanaPrograma(Request $request, $semana)
    {
        $user = $request->user();
        $user->modo = $user->modo == true;

        //$diasRetoOriginal = intval(env('DIAS'));
        //$diasReto = intval(env('DIAS2'));
        $diasRetoOriginal = intval($user->dias);
        $diasReto = intval($user->dias);
        error_log('ERRRRRRRRROOOOOOOORRRRR', $diasReto);
        var_dump($diasReto);

        $inicioReto = Carbon::parse($user->inicio_reto)->startOfDay();
        if ($user->num_inscripciones > 1) {
            $teorico = $diasRetoOriginal + (($user->num_inscripciones - 2) * $diasReto) + Carbon::now()->startOfDay()->diffInDays($inicioReto);
            if (Carbon::parse($user->fecha_inscripcion)->startOfDay() == $inicioReto) {
                $teorico++;
            }
            if ($teorico > $diasRetoOriginal + (($user->num_inscripciones - 1) * $diasReto)) {
                $teorico = $diasRetoOriginal + ($user->num_inscripciones - 1) * $diasReto;
            }
        } else {
            $teorico = Carbon::now()->startOfDay()->diffInDays($inicioReto) + 1;
            if ($teorico > $diasRetoOriginal) {
                $teorico = $diasRetoOriginal;
            }
        }
        if ($semana * 7 < $teorico) {
            $dias = 7;
        } else {
            $diaInicial = ($semana * 7) - 6;
            $dias = $teorico - ($diaInicial - 1);
        }
        return $dias;
    }

    public function pdf(Request $request, $dia, $genero, $objetivo, $dieta, $lugar)
    {
        $diaDB = Dia::buildDia($dia, $genero, $objetivo, $request->user(), $dieta);
        $diaDB->ejercicios = $lugar == LugarEjercicio::GYM ? $diaDB->gym : $diaDB->casa;
        $pdf = \Barryvdh\DomPDF\Facade::loadView('reto.pdf', ['dia' => $diaDB, 'genero' => $genero, 'objetivo' => $objetivo, 'lugar' => $lugar]);
        return $pdf->download('reto.pdf');
    }

    public function correo(Request $request)
    {
        Mail::queue(new \App\Mail\Dieta($request->dia, $request->genero, $request->objetivo, $request->lugar, $request->user(), $request->dieta));
        return response()->json(['status' => 'ok']);

    }

    public function comenzar(Request $request)
    {
        $user = $request->user();
        if ($user->inicio_reto === null) {
            $user->inicio_reto = Carbon::now();
            $user->save();
        }
        return redirect('/reto/cliente');
    }

    public function cliente(Request $request)
    {
        $user = $request->user();
        //$diasRetoOriginal = intval(env('DIAS'));
        //$diasReto = intval(env('DIAS2'));
        $diasRetoOriginal = intval($user->dias);
        $diasReto = intval($user->dias);
        $diasTranscurridos = UsuarioDia::where('usuario_id', $user->id)->count();
        $inicioReto = Carbon::parse($user->inicio_reto);
        if ($user->num_inscripciones > 1) {
            $teoricos = $diasRetoOriginal + (($user->num_inscripciones - 2) * $diasReto) + Carbon::now()->startOfDay()->diffInDays($inicioReto);
            if (Carbon::parse($user->fecha_inscripcion)->startOfDay() == $inicioReto) {
                $teoricos++;
            }
            if ($teoricos > $diasRetoOriginal + (($user->num_inscripciones - 1) * $diasReto)) {
                $teoricos = $diasRetoOriginal + ($user->num_inscripciones - 1) * $diasReto;
            }
        } else {
            $teoricos = Carbon::now()->startOfDay()->diffInDays($inicioReto) + 1;
            if ($teoricos > $diasRetoOriginal) {
                $teoricos = $diasRetoOriginal;
            }
        }
        $semana = $teoricos % 7 == 0 ? intval($teoricos / 7) : intval($teoricos / 7) + 1;
        if ($semana * 7 < $teoricos) {
            $dias = 7;
        } else {
            $diaInicial = ($semana * 7) - 6;
            $dias = $teoricos - ($diaInicial - 1);
        }
        return view('reto.cliente', ['dias' => $dias, 'semana' => $semana, 'maximo' => $diasTranscurridos,
            'teoricos' => $teoricos, 'diasReto' => $diasReto]);
    }

    public function getSemanaCliente(Request $request, $semana)
    {
        $user = $request->user();
        //$diasRetoOriginal = intval(env('DIAS'));
        //$diasReto = intval(env('DIAS2'));
        $diasRetoOriginal = intval($user->dias);
        $diasReto = intval($user->dias);
        $inicioReto = Carbon::parse($user->inicio_reto)->startOfDay();
        if ($user->num_inscripciones > 1) {
            $teoricos = $diasRetoOriginal + (($user->num_inscripciones - 2) * $diasReto) + Carbon::now()->startOfDay()->diffInDays($inicioReto);
            if (Carbon::parse($user->fecha_inscripcion)->startOfDay() == $inicioReto) {
                $teoricos++;
            }
            if ($teoricos > $diasRetoOriginal + (($user->num_inscripciones - 1) * $diasReto)) {
                $teoricos = $diasRetoOriginal + ($user->num_inscripciones - 1) * $diasReto;
            }
        } else {
            $teoricos = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($user->inicio_reto)->startOfDay()) + 1;
            if ($teoricos > $diasRetoOriginal) {
                $teoricos = $diasRetoOriginal;
            }
        }

        if ($semana * 7 < $teoricos) {
            $dias = 7;
        } else {
            $diaInicial = ($semana * 7) - 6;
            $dias = $teoricos - ($diaInicial - 1);
        }
        return ($dias);
    }

    public function getDia(Request $request, $dia)
    {
        $user = $request->user();
        $ejemplo = UsuarioDia::where('usuario_id', 1)->where('dia_id', $dia)->first();
        $diaEjemplo = Dia::find($dia) ?? new Dia();
        if ($ejemplo == null) {
            $ejemplo = new UsuarioDia();
        }
        $ejemplo->comentario = $diaEjemplo->comentarios;
        $ejemplo->imagen = url("/reto/getImagen/reto/1/$dia/" . Utils::generarRandomString(10));
        if (Storage::disk('local')->exists("public/reto/1/" . ($dia) . '.mp3')) {
            $ejemplo->audio = url("/reto/getAudio/reto/1/$dia/" . Utils::generarRandomString(10) . ".mp3");
            $ejemplo->audioOgg = url("/reto/getAudio/reto/1/$dia/" . Utils::generarRandomString(10) . ".ogg");
        } else {
            $ejemplo->audio = "";
            $ejemplo->audioOgg = "";
        }
        $usuarioDia = UsuarioDia::where('usuario_id', $user->id)->where('dia_id', $dia)->first();
        if ($usuarioDia == null) {
            $usuarioDia = new UsuarioDia();
        }
        $usuarioDia->dia = $dia;
        $usuarioDia->imagen = url("/reto/getImagen/reto/$user->id/$dia/" . Utils::generarRandomString(10));
        return response()->json(['dia' => $usuarioDia, 'ejemplo' => $ejemplo]);
    }

    public function programa(Request $request)
    {
        $user = $request->user();
        //$diasRetoOriginal = intval(env('DIAS'));
        //$diasReto = intval(env('DIAS2'));
        $diasRetoOriginal = intval($user->dias);
        $diasReto = intval($user->dias);
        $inicioReto = Carbon::parse($user->inicio_reto)->startOfDay();
        if ($user->num_inscripciones > 1) {
            $teoricos = $diasRetoOriginal + (($user->num_inscripciones - 2) * $diasReto) + Carbon::now()->startOfDay()->diffInDays($inicioReto);
            if (Carbon::parse($user->fecha_inscripcion)->startOfDay() == $inicioReto->startOfDay()) {
                $teoricos++;
            }
            if ($teoricos > $diasRetoOriginal + (($user->num_inscripciones - 1) * $diasReto)) {
                $teoricos = $diasRetoOriginal + ($user->num_inscripciones - 1) * $diasReto;
            }
        } else {
            $teoricos = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($user->inicio_reto)) + 1;
            if ($teoricos == 0) {
                $teoricos++;
            }
            if ($teoricos > $diasRetoOriginal) {
                $teoricos = $diasRetoOriginal;
            }
        }
        return $this->dia($request, $teoricos, $user->genero, $user->objetivo, $diasReto);
    }

    public function saveAudio(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [], []);
        $validator->after(function ($validator) use ($request) {
            $dia = $request->dia - 1;
            $extension = $request->file('audio')->getClientOriginalExtension();
            if ($extension == 'mp3') {
                $size = ((($request->file('audio')->getSize() / 1024) / 1024) * 100) / 100;
                if ($size > 20) {
                    $validator->errors()->add("audio$dia", "El tamaño del audio debe ser menor a 20MB");
                }
            } else {
                $validator->errors()->add("audio$dia", "El formato del audio no está permitido");
            }
        });
        $validator->validate();
        $usuario_id = $request->user()->id;
        $usuarioDia = UsuarioDia::where('dia_id', $request->dia)->where('usuario_id', $usuario_id)->first();
        if ($usuarioDia == null) { //checar es imagen nueva o ya esta registrada en tabla usuario_dia
            $usuarioDia = new UsuarioDia();
            $usuarioDia->dia_id = $request->dia;
            $usuarioDia->usuario_id = $usuario_id;
        }
        $usuarioDia->comentario = null;
        $usuarioDia->save();
        $request->file('audio')->storeAs("public/reto/$usuario_id/", $request->dia . '.mp3');
        exec("avconv -i " . storage_path("app/public/reto/$usuario_id/$request->dia.mp3") . " -vn " . storage_path("app/public/reto/$usuario_id/$request->dia.ogg -y"));
        return response()->json(['respuesta' => 'ok',
            'audio' => url("/reto/getAudio/reto/$usuario_id/$request->dia/" . Utils::generarRandomString(10).".mp3"),
            'audioOgg' => url("/reto/getAudio/reto/$usuario_id/$request->dia/" . Utils::generarRandomString(10).".ogg")
            ]);
    }

    public function quitarAudio(Request $request)
    {
        $user = $request->user();
        if (Storage::disk('local')->exists("public/reto/$user->id/$request->dia.mp3")) {
            Storage::disk('local')->delete("public/reto/$user->id/$request->dia.mp3");
            Storage::disk('local')->delete("public/reto/$user->id/$request->dia.ogg");
        }
        return response()->json(['status'=>'ok']);
    }
}
