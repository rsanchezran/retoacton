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

class RetoController extends Controller
{

    public function index(Request $request)
    {
        $usuario = $request->user();
        $usuarioDias = UsuarioDia::where('usuario_id', $usuario->id)->count();
        if ($usuarioDias == 0) {
            $semana = 1;
        } else {
            $semana = $usuarioDias % 7 == 0 ? intval($usuarioDias / 7) : intval($usuarioDias / 7) + 1;
        }
        $dias = $this->getSemana($request, $semana);

        return view('reto/configuracion', ['rol' => $usuario->rol, 'dias' => $dias, 'semana' => $semana,
            'maximo' => $usuarioDias, 'teorico'=>intval(env('DIAS'))]);
    }

    public function getSemana(Request $request, $semana)
    {
        $dias = collect();
        $usuario = $request->user();

        if ($usuario->inicio_reto == '') {//crear inicio del reto
            Storage::makeDirectory('public/reto/' . $usuario->id);
            $usuario->inicio_reto = Carbon::now();
            $usuario->save();
        }
        $usuarioDias = UsuarioDia::where('usuario_id', $usuario->id)->get()->keyBy('dia_id');

        for ($i = 1; $i <= 7; $i++) {//construir arreglo y ruta de las imagenes para la vista
            $dia = (7 * ($semana - 1)) + $i;
            $imagenDia = $usuarioDias->get($dia);
            if ($imagenDia === null) {
                $imagenDia = new UsuarioDia();
                $imagenDia->comentarios = '';
                $imagenDia->imagen = '';
                $imagenDia->audio = '';
            } else {
                $imagenDia->imagen = url("/reto/getImagen/reto/$usuario->id/" . $dia) . "/" . (Utils::generarRandomString(10));
                if (Storage::disk('local')->exists("public/reto/$usuario->id/" . $dia . '.mp3')) {
                    $imagenDia->audio = url("/reto/getAudio/reto/$usuario->id/" . $dia);
                } else {
                    $imagenDia->audio = '';
                }
                $imagenDia->comentarios = Dia::find($imagenDia->dia_id)->comentarios;
            }
            $imagenDia->dia = $dia;
            $imagenDia->subir = $usuario->rol == RolUsuario::ADMIN ? true : $dia <= $usuarioDias->count();
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

    public function getAudio($carpeta, $user_id, $dia)
    {
        if (Storage::disk('local')->exists("public//$carpeta/$user_id/$dia.mp3")) {
            return response()->file(storage_path('app/public/' . $carpeta . '/' . $user_id . '/' . $dia . '.mp3'));
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
        $dia->comentarios = $request->comentarios;
        $dia->save();
        return "ok";
    }

    public function dia(Request $request, $dia, $genero, $objetivo)
    {
        $user = $request->user();
        $user->modo = $user->modo == true;
        $diasTranscurridos = Carbon::now()->startOfDay()->diffInDays($user->inicio_reto) + 1;
        if ($diasTranscurridos > env('DIAS')) {
            $diasTranscurridos = env('DIAS');
        }
        $usuarioDieta = UsuarioDieta::where('usuario_id', $request->user()->id)->where('dieta', '>', 1)->get()->last();
        if ($usuarioDieta == null) {
            $dia = new Dia();
            $dia->nota = "";
            $dia->ejercicioss = collect();
            return view('reto.dia', ['dia' => $dia, 'genero' => $genero, 'objetivo' => $objetivo, 'lugar' => $user->modo,
                'dias' => env('DIAS')]);
        } else {
            if ($usuarioDieta->dieta == 2) {
                if ($dia < env("DIASDIETA")) {
                    $diaDB = Dia::buildDia($dia, $genero, $objetivo, $request->user(), 1);
                    $diaDB->dieta = 1;
                } else {
                    $diaDB = Dia::buildDia($dia, $genero, $objetivo, $request->user(), $usuarioDieta->dieta);
                    $diaDB->dieta = 2;
                }
            } else {
                $diaDB = Dia::buildDia($dia, $genero, $objetivo, $request->user(), $usuarioDieta->dieta);
            }
            return view('reto.dia', ['dia' => $diaDB, 'genero' => $genero, 'objetivo' => $objetivo,
                'dias' => $diasTranscurridos == 0 ? 1 : $diasTranscurridos, 'lugar' => $user->modo]);
        }
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

    public function cliente(Request $request)
    {
        $user = $request->user();
        if ($user->inicio_reto === null) {
            $user->inicio_reto = Carbon::now();
            $user->save();
        }
        $diasRetoOriginal = intval(env('DIAS'));
        $diasReto = intval(env('DIAS2'));
        $diasTranscurridos = UsuarioDia::where('usuario_id', $user->id)->count();
        if ($user->num_inscripciones > 1) {
            $teoricos = $diasRetoOriginal +(($user->num_inscripciones-2)*$diasReto)+ Carbon::now()->startOfDay()->diffInDays(Carbon::parse($user->inicio_reto));
            if($teoricos > $diasRetoOriginal+(($user->num_inscripciones-1)*$diasReto)){
                $teoricos = $diasRetoOriginal+($user->num_inscripciones-1)*$diasReto;
            }
        } else {
            $teoricos = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($user->inicio_reto));
            if($teoricos > $diasRetoOriginal){
                $teoricos = $diasRetoOriginal;
            }
        }
        if ($diasTranscurridos < $teoricos) {
            $diasTranscurridos++;
        }
        if ($diasTranscurridos == 0) {
            $semana = 1;
        } else {
            $semana = $diasTranscurridos % 7 == 0 ? intval($diasTranscurridos / 7) : intval($diasTranscurridos / 7) + 1;
        }
        if ($semana * 7 < $teoricos) {
            $dias = 7;
        } else {
            $diaInicial = ($semana * 7) - 6;
            $dias = $teoricos - ($diaInicial-1);
        }
        return view('reto.cliente', ['dias' => $dias, 'semana' => $semana,
            'maximo' => $diasTranscurridos, 'teoricos' => $teoricos]);
    }

    public function getSemanaCliente(Request $request, $semana)
    {
        $user = $request->user();
        $diaInicial = ($semana * 7) - 6;
        $diaFinal = $semana * 7;
        $diasTranscurridos = UsuarioDia::where('usuario_id', $user->id)->whereBetween('dia_id', [$diaInicial, $diaFinal])->count();
        $teoricos = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($user->inicio_reto));
        if ($semana * 7 < $teoricos) {
            $dias = 7;
        } else {
            $dias = $teoricos - ($diasTranscurridos==0?$diaInicial-1:$diasTranscurridos);
        }
        return ($dias);
    }

    public function getDia(Request $request, $dia)
    {
        $user = $request->user();
        $ejemplo = UsuarioDia::where('usuario_id', 1)->where('dia_id', $dia)->first();
        $diaEjemplo = Dia::find($dia)??new Dia();
        if ($ejemplo == null) {
            $ejemplo = new UsuarioDia();
        }
        $ejemplo->comentario = $diaEjemplo->comentarios;
        $ejemplo->imagen = url("/reto/getImagen/reto/1/$dia/" . Utils::generarRandomString(10));
        if (Storage::disk('local')->exists("public/reto/1/" . ($dia) . '.mp3')) {
            $ejemplo->audio = url("/reto/getAudio/reto/1/$dia");
        } else {
            $ejemplo->audio = "";
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
        $diasTranscurridos = Carbon::now()->diffInDays($user->inicio_reto) + 1;
        if ($diasTranscurridos > env('DIAS')) {
            $diasTranscurridos = env('DIAS');
        }
        return $this->dia($request, $diasTranscurridos == 0 ? 1 : $diasTranscurridos, $user->genero, $user->objetivo);
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
        return response()->json(['respuesta' => 'ok', 'audio' => url("/reto/getAudio/reto/$usuario_id/$request->dia/" . Utils::generarRandomString(10))]);
    }
}
