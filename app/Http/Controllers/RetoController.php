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
        $usuario_id = $request->user()->id;
        $usuario = User::find($usuario_id);

        if ($request->user()->inicio_reto == '') {//crear inicio del reto
            Storage::makeDirectory('public/reto/' . $usuario_id);
            $usuario->inicio_reto = Carbon::now();
            $usuario->save();
        }
        $inicio_reto = Carbon::parse($request->user()->inicio_reto);//convertir a objeto Carbon
        $diasTranscurridos = Carbon::now()->diffInDays($inicio_reto->format('y-m-d')) + 1; //vector de dias
        $usuarioDias = UsuarioDia::where('usuario_id', $usuario_id)->get()->keyBy('dia_id');
        $dias = collect();

        for ($i = 0; $i < env('DIAS', 90); $i++) {//construir arreglo y ruta de las imagenes para la vista
            $imagenDia = $usuarioDias->get($i + 1);
            if ($imagenDia === null) {
                $imagenDia = new UsuarioDia();
                $imagenDia->comentarios = '';
                $imagenDia->imagen = '';
                $imagenDia->audio = '';
            } else {
                $imagenDia->imagen = url("/reto/getImagen/reto/$usuario_id/" . ($i + 1)) . "/" . (Utils::generarRandomString(10));
                if (Storage::disk('local')->exists("public/reto/$usuario_id/" . ($i + 1) . '.mp3')) {
                    $imagenDia->audio = url("/reto/getAudio/reto/$usuario_id/" . ($i + 1));
                } else {
                    $imagenDia->audio = '';
                }
                $imagenDia->comentarios = Dia::find($imagenDia->dia_id)->comentarios;
            }
            $imagenDia->dia = $i + 1;
            $imagenDia->subir = $request->user()->rol == RolUsuario::ADMIN ? true : $i <= $diasTranscurridos;
            $imagenDia->loading = false;
            $dias->push($imagenDia);
        }
        return view('reto/imagenes', ['rol' => $request->user()->rol, 'datos_reto' => $dias]);
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

        return response()->json(['respuesta' => 'ok', 'imagen' => url("/reto/getImagen/reto/$usuario_id/$request->dia/" . rand(0, 100))]);
    }

    public function retoActon(Request $request)
    {
        $usuario_id = $request->user()->id;

        $web = '/reto/getImagen/reto/'; //ruta para imagenes route /reto/getImagen... en carpeta .../reto
        $dias_activo = count(Storage::allFiles('public/reto' . $usuario_id));

        if ($dias_activo == 0)
            Storage::makeDirectory('public/reto/' . $usuario_id);

        for ($i = 0; $i < env('DIAS', 90); $i++) {
            $datos_reto[$i] = [
                'nombre' => 'Subir Imagen',
                'imagen' => $web . $usuario_id . '/' . ($i + 1),
                'subir' => ($dias_activo <= $i ? true : false),
                'disabled' => ($dias_activo == $i ? false : true)
            ];
        }

        return view('/reto/acton', ['datos_reto' => json_encode($datos_reto)]);
    }

    public function saveActon(Request $request)
    {
        ini_set('max_execution_time', 3000);
        ini_set('memory_limit', '2GB');
        $usuario_id = $request->user()->id;
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
        return response()->json(['respuesta' => 'ok']);
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
        $diasTranscurridos = Carbon::now()->diffInDays($user->inicio_reto);
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

    public function ejemplo()
    {
        $web = '/reto/getImagen/reto/'; //ruta para imagenes route /reto/getImagen... en carpeta .../reto
        $dias = Dia::all()->keyBy('dia');
        $dias_activo = count(Storage::allFiles('public/reto/1'));//contar las imagenes
        $datos_reto = [];
        $dias_reto = env('DIAS', 30);
        for ($i = 0; $i < $dias_activo && $i < env('DIAS', 90); $i++) {//construir arreglo y ruta de las imagenes para la vista
            $datos_reto[$i] = [
                'nombre' => 'Subir Imagen',
                'imagen' => $web . 1 . '/' . ($i + 1) . '/' . (Utils::generarRandomString(10)),
                'subir' => false,
                'disabled' => true,
                'comentario' => '',
                'mostrarImg' => $i < $dias_activo,
                'comentarios' => $dias[$i + 1]->comentarios,
                'dia' => $dias[$i + 1]->id,
            ];
        }
        return view('reto/imagenes', ['rol' => RolUsuario::ADMIN, 'dias_reto' => $dias_reto, 'datos_reto' => json_encode($datos_reto)]);
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
        if ($user->created_at->startOfDay() != Carbon::parse($user->fecha_inscripcion)) {
            $diasTranscurridos = env('DIAS');
        }else{
            $diasTranscurridos = Carbon::now()->diffInDays($user->inicio_reto);
            if ($diasTranscurridos > env('DIAS')) {
                $diasTranscurridos = env('DIAS');
            }
        }
        return view('reto.cliente', ['dias' => $diasTranscurridos == 0 ? 1 : $diasTranscurridos]);
    }

    public function getDia(Request $request, $dia)
    {
        $user = $request->user();
        $ejemplo = UsuarioDia::where('usuario_id', 1)->where('dia_id', $dia)->first();
        if ($ejemplo == null) {
            $ejemplo = new UsuarioDia();
        }
        $ejemplo->comentario = Dia::find($dia)->comentarios;
        $ejemplo->imagen = url("/reto/getImagen/reto/1/$dia");
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
        $usuarioDia->imagen = url("/reto/getImagen/reto/$user->id/$dia");
        return response()->json(['dia' => $usuarioDia, 'ejemplo' => $ejemplo]);
    }

    public function diario(Request $request)
    {
        $user = $request->user();
        $diasTranscurridos = Carbon::now()->diffInDays($user->inicio_reto);
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
        return response()->json(['respuesta' => 'ok', 'audio' => url("/reto/getAudio/reto/$usuario_id/$request->dia/" . rand(0, 100))]);
    }
}
