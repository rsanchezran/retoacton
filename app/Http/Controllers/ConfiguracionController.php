<?php

namespace App\Http\Controllers;

use App\Events\MensajesDirectosEvent;
use App\MensajesDirectos;
use App\Notifications\MensajeNotification;
use Auth;
use App\Categoria;
use App\Notifications;
use App\CodigosTienda;
use App\Code\MedioContacto;
use App\Code\TipoEjercicio;
use App\Code\Utils;
use App\Code\Videos;
use App\Console\Commands\EnviarCorreos;
use App\Contacto;
use App\Dia;
use App\Ejercicio;
use App\VideosPublicos;
use App\Amistades;
use App\Events\ProcesarVideoEvent;
use App\Code\ValidarCorreo;
use App\Notas;
use App\Serie;
use App\User;
use App\Code\RolUsuario;
use App\Code\LugarEjercicio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;

use App\Code\TipoRespuesta;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;

class ConfiguracionController extends Controller
{
    public function videos(Request $request)
    {
        $this->authorize('configurar.videos');
        $videos = VideosPublicos::all();

        $vv = array();
        foreach ($videos as $video) {
            $vv[] = [
                'nombre' => $video->nombre,
                'activo' => $video->activo,
                'id' => $video->id,
                'src' => url('/getVideo/') . "/$video->nombre/" . rand(1, 100)
            ];
        }
        $categorias = Categoria::all();
        foreach ($categorias as $categoria) {
            $categoria->mostrar = false;
            $categoria->ejercicios = $this->getEjerciciosCategoria($categoria->nombre);
            $categoria->nueva = false;
        }
        $pendientes = $this->getVideosPendientes();

        return view('configuracion.videos', ['videos' => collect($vv), 'categorias' => $categorias, 'pendientes' => $pendientes]);
    }


    public function videos_coach(Request $request)
    {
        $usuario = User::where('id', auth()->user()->id)->first();
        if($usuario->rol !== 'admin') {
            $videos = VideosPublicos::where('usuario_id', auth()->user()->id)->get();
        }else{
            $videos = VideosPublicos::all();
        }
        $vv = array();
        foreach ($videos as $video) {
            $vv[] = ['nombre' => $video->nombre, 'src' => url('/getVideo/') . "/$video->nombre/" . rand(1, 100)];
        }

        return view('configuracion.videos_coach', ['videos' => collect($vv)]);
    }


    public function videos_publicos(Request $request)
    {
        $categorias = Categoria::all();
        foreach ($categorias as $categoria) {
            $categoria->mostrar = false;
            $categoria->ejercicios = $this->getEjerciciosCategoria($categoria->nombre);
            $categoria->nueva = false;
        }

        return view('configuracion.videos_publicos', ['categorias' => $categorias]);
    }


    public function detalle_video(Request $request, $video){
        $videos = VideosPublicos::where('activo', 1)->where('nombre', $video);
        if($videos->count() == 0){
            return view('welcome');
        }
        foreach ($videos as $v) {
            if($v->nombre == $video) {
                $videos->push(['nombre' => $v->nombre, 'src' => url('/getVideo/') . "/$v->nombre/" . rand(1, 100)]
                );
            }
        }
        $categorias = Categoria::all();
        foreach ($categorias as $categoria) {
            $categoria->mostrar = false;
            $categoria->ejercicios = $this->getEjerciciosCategoria($categoria->nombre);
            $categoria->nueva = false;
        }
        $pendientes = $this->getVideosPendientes();

        $url_video = str_replace ( ' ', '%20', $video);

        return view('videos_publicos', ['videos' => url('/getVideo/') . "/$url_video/" . rand(1, 100),
            'categorias' => $categorias, 'pendientes' => $pendientes, 'nombre' => ucfirst($video) ]);
    }


    public function saveVideo(Request $request)
    {
        //$this->authorize('configurar.videos');
        $this->validate($request, [
            'video' => 'required|mimetypes:video/mp4|file|max:332000',
        ], [
                'video.mimetypes' => 'El video es obligatorio',
                'video.mimetypes' => 'El formato debe ser .mp4',
                'video  .size' => 'El archivo debe ser menor a 300MB',
            ]
        );
        $usuario = User::where('id', auth()->user()->id)->first();
        $activo = 1;
        if($usuario->rol == 'coach') {
            $activo = 0;
        }
        $video_existe = VideosPublicos::firstOrCreate([
            'nombre' => strtolower($request->nombre),
            'usuario_id' => auth()->user()->id,
            'activo' => $activo
        ]);
        $video_existe->usuario_id = auth()->user()->id;
        $video_existe->save();
        $nombre = str_replace(" ", "_", $request->nombre);
        $nombre = Utils::clearString($nombre);
        $archivoVideo = $request->video;
        $archivoVideo->storeAs('public', 'home/' . $nombre . '.mp4');
        $path = $request->file('video')->storeAs(
            'public/optimized', $nombre . '.mp4'
        );
        //event(new ProcesarVideoEvent("public/home", "public/optimized", "$nombre.mp4"));
        //event(new ProcesarVideoEvent("home", "public/optimized", "$nombre.mp4"));
        return "ok";
    }

    public function saveCategoria(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'min:2|max:20|unique:categorias,nombre'
        ], [
            'nombre.min' => 'El nombre debe tener minimo 5 caracteres',
            'nombre.max' => 'El nombre debe tener maximo 20 caracteres',
            'nombre.unique' => 'Este nombre ya se encuentra en uso',
        ]);
        $categoria = Categoria::find($request->id);
        if ($categoria == null) {
            $categoria = new Categoria();
        }
        $categoria->nombre = str_replace(' ', '_', substr(strtolower($request->nombre), 0, 20));
        $categoria->save();
    }

    public function saveEjercicio(Request $request)
    {
        $this->authorize('configurar.videos');
        $validator = Validator::make($request->all(), [], []);
        $validator->after(function ($validator) use ($request) {
            $size = 0;
            foreach ($request->archivos as $archivo) {
                $extension = $archivo->getClientOriginalExtension();
                if ($extension != 'mp4' && $extension != 'zip') {
                    $validator->errors()->add($request->nombre, 'El archivo no tiene el formato mp4 o zip');
                }
                $size += ((($archivo->getSize() / 1024) / 1024) * 100) / 100;
            }
            if ($size > 320) {
                $validator->errors()->add($request->nombre, 'El tamaño de los videos subidos excede los 320MB');
            }
        });
        $validator->validate();
        if (!Storage::disk('local')->exists("/ejercicios/$request->nombre")) {
            Storage::disk('local')->makeDirectory("/ejercicios/$request->nombre");
        }
        if (!Storage::disk('local')->exists("/optimized/$request->nombre")) {
            Storage::disk('local')->makeDirectory("/optimized/$request->nombre");
        }
        foreach ($request->archivos as $archivo) {
            $extension = $archivo->getClientOriginalExtension();
            $nombreVideo = $archivo->getClientOriginalName();
            $elementos = collect(explode('.', $nombreVideo));
            $elementos->pop();
            $nombreVideo = Utils::clearString($elementos->implode(''), true) . "." . $extension;
            $nombreReplace = str_replace('.mp4', '', $nombreVideo);
            if ($extension == 'zip') {
                $archivo->storeAs('ejercicios', $nombreVideo);
                $zip = new \ZipArchive();
                $zip->open(storage_path("app/ejercicios/") . $nombreVideo);
                $zip->extractTo(storage_path("app") . "/ejercicios/$request->nombre/");
                Storage::disk('local')->delete("/ejercicios/$nombreVideo");
            } else {
                $archivo->storeAs("ejercicios/$request->nombre/", "$nombreVideo");
            }
        }
        return response()->json(['status' => 'ok', 'videoNuevo' => $nombreReplace]);
    }

    public function programa()
    {
        $diasReto = intval(env('DIAS'));
        $diasDB = Dia::orderByDesc('dia')->first();
        if ($diasDB == null) {
            $diasDB = 1;
        } else {
            $diasDB = $diasDB->dia;
        }
        $diasTranscurridos = $diasDB < $diasReto ? $diasReto : $diasDB;
        $semana = 1;
        if ($diasTranscurridos == 0) {
            $semana = 1;
        } else {
            $semana = $diasTranscurridos % 7 == 0 ? intval($diasTranscurridos / 7) : intval($diasTranscurridos / 7) + 1;
        }
        $dias = $this->getSemanaEjercicios($semana);
        return (view('configuracion.programa', ['dias' => $dias, 'semana' => $semana, 'maximo' => $diasTranscurridos,
            'teorico' => $diasReto]));
    }

    public function getSemanaEjercicios($semana)
    {
        $dias = collect();
        $primerDia = (($semana - 1) * 7) + 1;
        $ultimoDia = $semana * 7;
        $diasDB = Dia::whereBetween('dia', [$primerDia, $ultimoDia])->with(['ejercicios'])->get()->keyBy('dia');

        for ($i = 1; $i <= 7; $i++) {
            $dia = (($semana - 1) * 7) + $i;
            $diaDB = $diasDB->get($dia);
            if ($diaDB == null) {
                $diaDB = new \stdClass();
                $diaDB->dia = $dia;
                $diaDB->ejercicios = collect();
                $diaDB->cardio = collect();
                $diaDB->alimentos = collect();
                $diaDB->suplementos = collect();
            }
            $diaDB->ejerciciosG = $diaDB->ejercicios->groupBy(function ($item) {
                return "$item->genero-$item->objetivo";
            });
            $diaDB->ejerciciosG = $diaDB->ejerciciosG->map(function ($item) {
                return $item->groupBy('lugar');
            });
            $diaDB->ejercicios = "Sin ejercicios";
            $dias->push($diaDB);
        }
        return $dias;
    }

    public function saveDia(Request $request)
    {
        $this->authorize('configurar.dia');
        $messages = [
            'nota.max' => 'Debe capturar máximo 250 caracteres',
            'gym.*.ejercicios.*.ejercicio.required' => 'El nombre del ejercicio en GYM es obligatorio',
            'gym.*.ejercicios.*.ejercicio.max' => 'El ejercicio en GYM debe ser máximo de 50 caracteres',
            'gym.*.ejercicios.*.video.required' => 'El video del ejercicio en GYM es obligatorio',
            'casa.*.ejercicios.*.ejercicio.required' => 'El nombre del ejercicio en casa es obligatorio',
            'casa.*.ejercicios.*.video.required' => 'El video del ejercicio en casa es obligatorio',
            'cardio.*.ejercicio.required' => 'El ejercicio de cardio es obligatorio',
            'cardio.*.ejercicio.max' => 'El ejercicio de cardio debe ser máximo de 50 caracteres',
            'cardio.*.video.required' => 'El video del cardio es obligatorio',
        ];
        foreach ($request->get('gym') as $igym => $gym) {
            $messages["gym.$igym.nombre.required"] = "El nombre de la serie ".($igym+1)." en GYM es obligatorio";
            $messages["gym.$igym.nombre.max"] = "El nombre de la serie ".($igym+1)." en GYM debe ser máximo de 50 caracteres";
            foreach ($gym['ejercicios'] as $iejercicio => $ejercicio) {
                foreach ($ejercicio['subseries'] as $isubserie => $subserie) {
                    $messages["gym.$igym.ejercicios.$iejercicio.subseries.$isubserie.repeticiones.required"] =
                        "La repeticion ".($isubserie+1)." de GYM en la serie ".($igym+1).". dentro del ejercicio .".($iejercicio+1)." es obligatorio";
                    $messages["gym.$igym.ejercicios.$iejercicio.subseries.$isubserie.repeticiones.max"] =
                        "La repeticion ".($isubserie+1)." de GYM en la serie ".($igym+1).". dentro del ejercicio .".($iejercicio+1)." debe ser máximo 50 caracteres";
                }
            }
        }
        foreach ($request->get('casa') as $igym => $gym) {
            $messages["casa.$igym.nombre.required"] = "El nombre de la serie ".($igym+1)." en casa es obligatorio";
            $messages["casa.$igym.nombre.max"] = "El nombre de la serie ".($igym+1)." en casa debe ser máximo de 50 caracteres";
            foreach ($gym['ejercicios'] as $iejercicio => $ejercicio) {
                foreach ($ejercicio['subseries'] as $isubserie => $subserie) {
                    $messages["casa.$igym.ejercicios.$iejercicio.subseries.$isubserie.repeticiones.required"] =
                        "La repeticion ".($isubserie+1)." de casa en la serie ".($igym+1).". dentro del ejercicio .".($iejercicio+1)." es obligatorio";
                    $messages["casa.$igym.ejercicios.$iejercicio.subseries.$isubserie.repeticiones.max"] =
                        "La repeticion ".($isubserie+1)." de casa en la serie ".($igym+1).". dentro del ejercicio .".($iejercicio+1)." debe ser máximo 50 caracteres";
                }
            }
        }
        $this->validate($request,
            [
                'nota' => 'max:250',
                'gym.*.nombre' => 'required|max:50',
                'gym.*.ejercicios.*.ejercicio' => 'required|max:50',
                'gym.*.ejercicios.*.subseries.*.repeticiones' => 'required|max:50',
                'gym.*.ejercicios.*.video' => 'required',
                'casa.*.ejercicios.*.subseries.*.repeticiones' => 'required|max:50',
                'casa.*.nombre' => 'required|max:50',
                'casa.*.ejercicios.*.ejercicio' => 'required|max:50',
                'casa.*.ejercicios.*.video' => 'required',
                'cardio.*.ejercicio' => 'required|max:50',
            ], $messages);
        \DB::beginTransaction();
        $now = Carbon::now();
        $filtro = function ($datos) use ($request) { //funcion para cada with con campos similares
            $datos->where('dia_id', $request->dia)->where('genero', $request->genero)->where('objetivo', $request->objetivo);
        };
        $dia = Dia::where('dia', $request->dia)->with(['ejercicios' => $filtro, 'cardio' => $filtro, 'notas' => $filtro])->first();
        if ($dia == null) {
            $dia = new Dia();
            $dia->id = $request->dia;
            $dia->dia = $request->dia;
            $dia->save();
        }
        if ($dia->notas->first() != null)
            $notas = Notas::find($dia->notas->first()->id);
        else {
            $notas = new Notas();
            $notas->genero = $request->genero;
            $notas->objetivo = $request->objetivo;
            $notas->dia_id = $dia->id;
        }
        $notas->descripcion = $request->nota == null ? '' : $request->nota;
        $notas->save();
        $series = Serie::where($filtro)->get()->keyBy('id');
        $cardios = Ejercicio::where('tipo', TipoEjercicio::AEROBICO)->where($filtro)->get()->keyBy('id');
        $ejercicios = Ejercicio::where('tipo', TipoEjercicio::ANAEROBICO)->where($filtro)->get()->keyBy('id');
        foreach ($series as $serie) {
            $serie->deleted_at = $now;
            $serie->save();
        }
        foreach ($ejercicios as $ejercicio) {
            $ejercicio->deleted_at = $now;
            $ejercicio->save();
        }
        foreach ($cardios as $ejercicio) {
            $ejercicio->deleted_at = $now;
            $ejercicio->save();
        }
        $this->procesarSerie($request->gym, $series, $request->dia, $ejercicios, $request->genero, $request->objetivo);
        $this->procesarSerie($request->casa, $series, $request->dia, $ejercicios, $request->genero, $request->objetivo);

        foreach ($request->cardio as $cardio) {
            $cardioDb = $cardios->get($cardio['id']);
            if ($cardioDb == null) {
                $cardioDb = new Ejercicio();
            }
            $cardioDb->dia_id = $dia->id;
            $cardioDb->ejercicio = $cardio['ejercicio'];
            $cardioDb->video = $cardio['video'] == null ? '' : $cardio['video'];
            $cardioDb->tipo = TipoEjercicio::AEROBICO;
            $cardioDb->genero = $request->genero;
            $cardioDb->objetivo = $request->objetivo;
            $cardioDb->orden = $cardio['orden'];
            $cardioDb->deleted_at = null;
            $cardioDb->save();
        }

        \DB::commit();
        return response()->json(['status' => 'ok', 'redirect' => url('configuracion/programa')]);
    }

    public function procesarSerie($lugar, $series, $dia, $ejercicios, $genero, $objetivo)
    {
        foreach ($lugar as $serie) {
            $serieDb = $series->get($serie['id']);
            if ($serieDb == null) {
                $serieDb = new Serie();
                $serieDb->dia_id = $dia;
                $serieDb->genero = $genero;
                $serieDb->objetivo = $objetivo;
            }
            $serieDb->nombre = $serie['nombre'];
            $serieDb->orden = $serie['orden'];
            $serieDb->deleted_at = null;
            $serieDb->save();
            foreach ($serie['ejercicios'] as $ejercicio) {
                $ejercicioDb = $ejercicios->get($ejercicio['id']);
                if ($ejercicioDb === null) {
                    $ejercicioDb = new Ejercicio();
                    $ejercicioDb->dia_id = $dia;
                    $ejercicioDb->genero = $genero;
                    $ejercicioDb->objetivo = $objetivo;
                    $ejercicioDb->serie_id = $serieDb->id;
                }
                $ejercicioDb->ejercicio = $ejercicio['ejercicio'];
                $ejercicioDb->video = $ejercicio['video'];
                $ejercicioDb->tipo = $ejercicio['tipo'];
                $ejercicioDb->orden = $ejercicio['orden'];
                $ejercicioDb->lugar = $ejercicio['lugar'];;
                $ejercicioDb->subseries = json_encode($ejercicio['subseries']);
                $ejercicioDb->deleted_at = null;
                $ejercicioDb->save();
            }
        }
    }

    public function getEjercicios(Request $request, $categoria = null)
    {
        $ejercicios = collect();
        if ($categoria == null) {
            $files = [];
            $folders = Storage::disk('local')->directories("/optimized/");
            foreach ($folders as $folder) {
                $filesFolder = Storage::disk('local')->files($folder);
                foreach ($filesFolder as $file) {
                    $files[] = $file;
                }
            }
        } else {
            $files = Storage::disk('local')->files("/optimized/$categoria");
        }
        foreach ($files as $file) {
            $eje = str_replace('optimized/', '', $file);
            $eje = explode('.mp4', $eje)[0];
            $ejercicios->push($eje);
        }
        if ($request->nombre == '') {
            return $ejercicios;
        } else {
            return collect($ejercicios->filter(function ($item) use ($request) {
                return strpos(strtolower($item), strtolower($request->nombre)) !== FALSE;
            }));
        }
    }

    public function getEjercicio($categoria, $ejercicio)
    {
        return response()->file(storage_path("app/optimized/$categoria/$ejercicio.mp4"));
    }

    public function dia(Request $request, $dia, $genero, $objetivo)
    {
        $this->authorize('configurar.dia');
        $diaDB = $this->getDia($request, $dia, $genero, $objetivo);
        $configuraciones = collect();
        for ($i = 1; $i <= env("DIAS"); $i++) {
            $configuraciones->put("$i-0-0", "Hombre bajar día $i");
            $configuraciones->put("$i-0-1", "Hombre subir día $i");
            $configuraciones->put("$i-1-0", "Mujer bajar día $i");
            $configuraciones->put("$i-1-1", "Mujer subir día $i");
        }
        unset($diaDB->ejercicios);
        return (view('configuracion.dia', ['dia' => $diaDB, 'genero' => $genero, 'objetivo' => $objetivo,
            'usuario' => $request->user(),
            'configuraciones' => $configuraciones]));
    }

    public function getDia(Request $request, $dia, $genero, $objetivo)
    {
        $diaDB = Dia::buildDia($dia, $genero, $objetivo, $request->user());
        return $diaDB;
    }

    public function contactos(Request $request)
    {
        $this->authorize('contactos');
        $medios = MedioContacto::all();
        return view('configuracion.contactos', ['medios' => $medios]);
    }

    public function buscarContactos(Request $request)
    {
        $this->authorize('contactos');
        $campos = json_decode($request->campos);
        $contactos = Contacto::leftjoin('users', 'contactos.email', 'users.email')
            ->select(['contactos.id', 'contactos.nombres', 'contactos.apellidos', 'contactos.email',
                'contactos.telefono', 'contactos.medio', 'contactos.created_at', 'contactos.etapa', 'users.deleted_at'])
            ->whereNull('contactos.deleted_at')->whereNull('users.id');
        if ($campos->email != '') {
            $contactos = $contactos->where('contactos.email', 'like', "%$campos->email%");
        }
        if ($campos->nombres != '') {
            $contactos = $contactos->where('contactos.nombres', 'like', "%$campos->nombres%")->orWhere('contactos.apellidos', 'like', "%$campos->nombres%");
        }
        if ($campos->medio != '') {
            $contactos = $contactos->where('contactos.medio', $campos->medio);
        }
        $contactos = $contactos->orderByDesc('created_at');
        $contactos = $contactos->paginate();
        foreach ($contactos as $contacto) {
            $contacto->contacto = $contacto->deleted_at == null;
        }
        return $contactos;
    }

    public function enviarCorreo(Request $request)
    {
        $contacto = Contacto::find($request->id);
        Mail::queue(new \App\Mail\Contacto($contacto));
    }

    public function quitarEjercicio(Request $request)
    {
        Storage::disk('local')->delete("/optimized/$request->categoria/$request->ejercicio.mp4");
        return response()->json(['status' => 'ok']);
    }

    public function getEjerciciosCategoria($categoria)
    {
        $ejercicios = collect();
        $files = Storage::disk('local')->files("/optimized/$categoria");
        foreach ($files as $file) {
            $ejercicio = explode('/', $file);
            $ejercicio = explode('.', $ejercicio[count($ejercicio) - 1])[0];
            $ejercicios->push($ejercicio);
        }
        return $ejercicios;
    }

    public function getMensaje(Contacto $contacto)
    {
        return $contacto->mensaje;
    }

    public function getVideosPendientes()
    {
        $pendientes = collect();
        $folders = Storage::disk('local')->directories("/ejercicios/");
        foreach ($folders as $folder) {
            $pts = Storage::disk('local')->files($folder);
            foreach ($pts as $pendiente) {
                $pt = explode("/", $pendiente);
                $pendientes->push($pt[count($pt) - 1]);
            }
        }
        return $pendientes;
    }

    public function quitarContacto(Request $request)
    {
        $contacto = Contacto::find($request->id);
        if ($contacto !== null) {
            $contacto->etapa = 1;
            $contacto->delete();
            $usuario = User::where('email', $contacto->email)->first();
            if ($usuario != null) {
                $usuario->delete();
            }
        }
    }

    public function exportarContactos($filtros)
    {
        $this->authorize('contactos');
        $campos = json_decode($filtros);
        $contactos = Contacto::leftjoin('users', 'contactos.email', 'users.email')
            ->select(['contactos.id', 'contactos.nombres', 'contactos.apellidos', 'contactos.email',
                'contactos.telefono', 'contactos.medio', 'contactos.created_at', 'contactos.etapa', 'users.deleted_at']);
        if ($campos->email != '') {
            $contactos = $contactos->where('contactos.email', 'like', "%$campos->email%");
        }
        if ($campos->nombres != '') {
            $contactos = $contactos->where('contactos.nombres', 'like', "%$campos->nombres%");
        }
        if ($campos->medio != '') {
            $contactos = $contactos->where('contactos.medio', $campos->medio);
        }
        $contactos = $contactos->get();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="invitados.xlsx"');
        header('Cache-Control: max-age=0');

        $spreadsheet = new Spreadsheet();
        $row = 1;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValueByColumnAndRow(1, 1, 'Nombre');
        $sheet->setCellValueByColumnAndRow(2, 1, 'Email');
        $sheet->setCellValueByColumnAndRow(3, 1, 'Teléfono');
        $sheet->setCellValueByColumnAndRow(4, 1, 'Código');
        $sheet->setCellValueByColumnAndRow(5, 1, 'Etapa');
        $sheet->setCellValueByColumnAndRow(6, 1, 'Medio');
        $sheet->setCellValueByColumnAndRow(7, 1, 'Registrado');
        $row++;
        foreach ($contactos as $usuario) {
            $sheet->setCellValueByColumnAndRow(1, $row, $usuario->nombres . ' ' . $usuario->apellidos);
            $sheet->setCellValueByColumnAndRow(2, $row, $usuario->email);
            $sheet->setCellValueByColumnAndRow(3, $row, "$usuario->telefono");
            $sheet->setCellValueByColumnAndRow(4, $row, $usuario->codigo);
            $sheet->setCellValueByColumnAndRow(5, $row, $usuario->etapa);
            $sheet->setCellValueByColumnAndRow(6, $row, $usuario->medio);
            $sheet->setCellValueByColumnAndRow(7, $row, $usuario->deleted_at == null ? 'No registrado' : 'Registrado');
            $row++;
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function enviarCorreos()
    {
        $enviarCorreos = new EnviarCorreos();
        $enviarCorreos->handle();
    }


    public function agregarTienda(Request $request)
    {
        $medios = MedioContacto::all();
        $usr = User::where('tipo_referencia', 2)->get();
        return view('configuracion.tienda', ['medios' => $medios,'users' => $usr]);
    }


    public function agregarCoach(Request $request)
    {
        $medios = MedioContacto::all();
        $usr = User::where('tipo_referencia', 5)->get();
        return view('configuracion.coach', ['medios' => $medios,'users' => $usr]);
    }

    public function saveContactoTienda(Request $request)
    {
        $id = null;
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:100|min:3|email',
            'codigo' => 'max:7',
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.min' => 'Debe capturar minimo 3 caracteres en el correo electrónico',
            'email.max' => 'Debe capturar máximo 100 caracteres en el correo electrónico',
            'email.unique' => 'El correo ya ha sido registrado',
            'email.email' => 'El formato no es válido en el correo electrónico',
            'codigo.max' => 'La referencia debe tener 7 caracteres',
        ]);
        $validator->after(function ($validator) use ($request) {
            if (ValidarCorreo::validarCorreo($request->email)) {
                $validator->errors()->add("email", "El email debe tener formato correcto");
            }
        });
        $validator->validate();
        $email = trim($request->email);
        $codigo = trim($request->codigo);
        $usuario = User::withTrashed()->orderBy('created_at')->where('email', $email)->get()->last();
        $cod = CodigosTienda::where('email', $email)->get()->last();
        if ($usuario!=null&&$usuario->id==1&&$cod!=null){
            $status = 'error';
            $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
        }else{
            $contacto = CodigosTienda::where("email", $email)->first();
            if ($contacto == null) {
                $contacto = new CodigosTienda();
                $contacto->email = $email;
                $contacto->codigo = $codigo;
            }
            $contacto->email = $email;
            $contacto->codigo = $codigo;
            $contacto->usuario_id_creador = Auth::id();
            $contacto->save();
            $mensaje = '';
            $status = 'ok';
            if ($usuario !== null) {
                if ($usuario->deleted_at == null) {
                    if ($usuario->inicio_reto == null) {
                        $status = 'error';
                        $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
                    } else {
                        if (Carbon::parse($usuario->inicio_reto)->diffInDays(Carbon::now()) < intval($usuario->dias)) {
                            $status = 'error';
                            $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
                        }
                    }
                }
            }else{
                $contacto = User::withTrashed()->where("email", $email)->first();
                if ($contacto == null) {
                    $contacto = new User();
                    $contacto->email = $email;
                }
                $contacto->name = $result = preg_replace('/\d/', '', $request->nombres);
                $contacto->last_name = $request->apellidos;
                $contacto->tipo_referencia = 2;
                $contacto->referencia = Str::random(7);
                $contacto->deleted_at = null;
                $contacto->password = Hash::make('acton'.$contacto->name);
                $contacto->rol = 'tienda';
                $contacto->encuestado = 1;
                $contacto->pagado = 1;
                $contacto->modo = 1;
                $contacto->cp = "1";
                $contacto->estado = "1";
                $contacto->colonia = "1";
                $contacto->ciudad = "1";
                $contacto->save();
                $mensaje = '';
                $status = 'ok';
                if ($usuario !== null) {
                    if ($usuario->deleted_at == null) {
                        if ($usuario->inicio_reto == null) {
                            $status = 'error';
                            $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
                        } else {
                            if (Carbon::parse($usuario->inicio_reto)->diffInDays(Carbon::now()) < intval($usuario->dias)) {
                                $status = 'error';
                                $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
                            }
                        }
                    }
                }
            }
        }
        return response()->json(['status' => $status, 'mensaje' => $mensaje]);
    }

    public function saveContactoCoach(Request $request)
    {
        $id = null;
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:100|min:3|email',
            'codigo' => 'max:7',
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.min' => 'Debe capturar minimo 3 caracteres en el correo electrónico',
            'email.max' => 'Debe capturar máximo 100 caracteres en el correo electrónico',
            'email.unique' => 'El correo ya ha sido registrado',
            'email.email' => 'El formato no es válido en el correo electrónico',
            'codigo.max' => 'La referencia debe tener 7 caracteres',
        ]);
        $validator->after(function ($validator) use ($request) {
            if (ValidarCorreo::validarCorreo($request->email)) {
                $validator->errors()->add("email", "El email debe tener formato correcto");
            }
        });
        $validator->validate();
        $email = trim($request->email);
        $codigo = trim($request->codigo);
        $usuario = User::withTrashed()->orderBy('created_at')->where('email', $email)->get()->last();
        $cod = CodigosTienda::where('email', $email)->get()->last();
        if ($usuario!=null&&$usuario->id==1&&$cod!=null){
            $status = 'error';
            $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
        }else{
            $contacto = CodigosTienda::where("email", $email)->first();
            if ($contacto == null) {
                $contacto = new CodigosTienda();
                $contacto->email = $email;
                $contacto->codigo = $codigo;
            }
            $contacto->email = $email;
            $contacto->codigo = $codigo;
            $contacto->usuario_id_creador = Auth::id();
            $contacto->save();
            $mensaje = '';
            $status = 'ok';
            if ($usuario !== null) {
                if ($usuario->deleted_at == null) {
                    if ($usuario->inicio_reto == null) {
                        $status = 'error';
                        $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
                    } else {
                        if (Carbon::parse($usuario->inicio_reto)->diffInDays(Carbon::now()) < intval($usuario->dias)) {
                            $status = 'error';
                            $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
                        }
                    }
                }
            }else{
                $contacto = User::withTrashed()->where("email", $email)->first();
                if ($contacto == null) {
                    $contacto = new User();
                    $contacto->email = $email;
                }
                $contacto->name = $result = preg_replace('/\d/', '', $request->nombres);
                $contacto->last_name = $request->apellidos;
                $contacto->tipo_referencia = 5;
                $contacto->referencia = Str::random(7);
                $contacto->deleted_at = null;
                $contacto->password = Hash::make('acton'.$contacto->name);
                $contacto->rol = 'coach';
                $contacto->encuestado = 1;
                $contacto->pagado = 1;
                $contacto->modo = 1;
                $contacto->cp = "1";
                $contacto->estado = "1";
                $contacto->colonia = "1";
                $contacto->ciudad = "1";
                $contacto->enviado_validacion = "2";
                $contacto->save();
                $mensaje = '';
                $status = 'ok';
                if ($usuario !== null) {
                    if ($usuario->deleted_at == null) {
                        if ($usuario->inicio_reto == null) {
                            $status = 'error';
                            $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
                        } else {
                            if (Carbon::parse($usuario->inicio_reto)->diffInDays(Carbon::now()) < intval($usuario->dias)) {
                                $status = 'error';
                                $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
                            }
                        }
                    }
                }
            }
        }
        return response()->json(['status' => $status, 'mensaje' => $mensaje]);
    }

    public function saveCodigoTienda(Request $request)
    {
        $id = null;
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:100|min:3|email',
            'codigo' => 'max:7',
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.min' => 'Debe capturar minimo 3 caracteres en el correo electrónico',
            'email.max' => 'Debe capturar máximo 100 caracteres en el correo electrónico',
            'email.unique' => 'El correo ya ha sido registrado',
            'email.email' => 'El formato no es válido en el correo electrónico',
            'codigo.max' => 'La referencia debe tener 7 caracteres',
        ]);
        $validator->after(function ($validator) use ($request) {
            if (ValidarCorreo::validarCorreo($request->email)) {
                $validator->errors()->add("email", "El email debe tener formato correcto");
            }
        });
        $validator->validate();
        $email = trim($request->email);
        $codigo = trim($request->codigo);
        $usuario = User::withTrashed()->orderBy('created_at')->where('email', $email)->get()->last();
        $cod = CodigosTienda::where('email', $email)->get()->last();
        if ($usuario!=null&&$usuario->id==1&&$cod!=null){
            $status = 'error';
            $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
        }else {
            $contacto = CodigosTienda::where("email", $email)->first();
            if ($contacto == null) {
                $contacto = new CodigosTienda();
                $contacto->email = $email;
                $contacto->codigo = $codigo;
            }
            $contacto->email = $email;
            $contacto->codigo = $codigo;
            $contacto->usuario_id_creador = Auth::id();
            $contacto->save();
            $mensaje = '';
            $status = 'ok';
        }
        return response()->json(['status' => $status, 'mensaje' => $mensaje]);
    }

    public function saveCodigoEntrenador(Request $request)
    {
        $id = null;
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:100|min:3|email',
            'codigo' => 'required|max:7',
            'nombre' => 'required',
            'apellidos' => 'required',
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.min' => 'Debe capturar minimo 3 caracteres en el correo electrónico',
            'email.max' => 'Debe capturar máximo 100 caracteres en el correo electrónico',
            'email.unique' => 'El correo ya ha sido registrado',
            'email.email' => 'El formato no es válido en el correo electrónico',
            'codigo.max' => 'La referencia debe tener 7 caracteres',
        ]);
        $validator->after(function ($validator) use ($request) {
            if (ValidarCorreo::validarCorreo($request->email)) {
                $validator->errors()->add("email", "El email debe tener formato correcto");
            }
        });
        $validator->validate();
        $email = trim($request->email);
        $codigo = trim($request->codigo);
        $nombre = trim($request->nombre);
        $apellidos = trim($request->apellidos);
        $ref = User::where('id', Auth::id())->get()->first();
        $usuario = User::withTrashed()->orderBy('created_at')->where('email', $email)->get()->last();
        if ($usuario!=null){
            $status = 'error';
            $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
        }else {
            $usuario = User::create([
                'name' => $nombre,
                'last_name' => $apellidos,
                'email' => $email,
                'password' => Hash::make('acton'.$nombre),
                'pagado' => true,
                'encuestado' => true,
                'objetivo' => 1,
                'referencia' => $codigo,
                'codigo' => $ref->referencia,
                'rol' => RolUsuario::ENTRENADOR,
                'tipo_pago' => '',
                'modo' => LugarEjercicio::GYM,
                'fecha_inscripcion' => Carbon::now(),
                'correo_enviado' => 1,
                'num_inscripciones' => 1,
                'dias' => 14,
                'cp' => '0',
                'colonia' => '0',
                'estado' => '0',
                'ciudad' => '0',
                'tipo_referencia' => 2,
            ]);
            $usuario->tipo_referencia = 2;
            $usuario->save();
            $mensaje = '';
            $status = 'ok';
        }
        return response()->json(['status' => $status, 'mensaje' => $mensaje]);
    }


    public function agregarUsuarioNuevo(Request $request)
    {
        $medios = MedioContacto::all();
        $usr = User::where('tipo_referencia', 3)->get();
        return view('configuracion.usuario_nuevo', ['medios' => $medios,'users' => $usr]);
    }

    public function saveContactoUsuarioNuevo(Request $request)
    {
        $id = null;
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:100|min:3|email',
            'codigo' => 'max:7',
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.min' => 'Debe capturar minimo 3 caracteres en el correo electrónico',
            'email.max' => 'Debe capturar máximo 100 caracteres en el correo electrónico',
            'email.unique' => 'El correo ya ha sido registrado',
            'email.email' => 'El formato no es válido en el correo electrónico',
            'codigo.max' => 'La referencia debe tener 7 caracteres',
        ]);
        $validator->after(function ($validator) use ($request) {
            if (ValidarCorreo::validarCorreo($request->email)) {
                $validator->errors()->add("email", "El email debe tener formato correcto");
            }
        });
        $validator->validate();
        $email = trim($request->email);
        $codigo = trim($request->codigo);
        $usuario = User::withTrashed()->orderBy('created_at')->where('email', $email)->get()->last();
        if ($usuario!=null&&$usuario->id==1){
            $status = 'error';
            $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
        }else{
            if ($usuario !== null) {
                if ($usuario->deleted_at == null) {
                    if ($usuario->inicio_reto == null) {
                        $status = 'error';
                        $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
                    } else {
                        if (Carbon::parse($usuario->inicio_reto)->diffInDays(Carbon::now()) < intval($usuario->dias)) {
                            $status = 'error';
                            $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
                        }
                    }
                }
            }else{
                $contacto = Contacto::withTrashed()->where("email", $email)->first();
                if ($contacto == null) {
                    $contacto = new Contacto();
                    $contacto->email = $email;
                    $contacto->etapa = 1;
                }
                $contacto->nombres = $result = preg_replace('/\d/', '', $request->nombres);
                $contacto->apellidos = $request->apellidos;
                $contacto->dias = $request->dias;
                $contacto->telefono = $request->telefono;
                $contacto->medio = 'Por alta directa';
                $contacto->codigo = '';
                $contacto->deleted_at = null;
                $contacto->save();

                $contacto = User::withTrashed()->where("email", $email)->first();
                if ($contacto == null) {
                    $contacto = new User();
                    $contacto->email = $email;
                }

                $random = Str::random(7);

                $contacto->name = $result = preg_replace('/\d/', '', $request->nombres);
                $contacto->last_name = $request->apellidos;
                $contacto->tipo_referencia = 3;
                $contacto->deleted_at = null;
                $contacto->password = Hash::make('acton'.$contacto->name);
                $contacto->rol = 'cliente';
                $contacto->encuestado = 0;
                $contacto->pagado = 1;
                $contacto->modo = 1;
                $contacto->referencia = strtoupper($random);
                $contacto->codigo = $request->referencia;
                $contacto->cp = NULL;
                $contacto->estado = NULL;
                $contacto->ciudad = NULL;
                $contacto->colonia = NULL;
                $contacto->dias = $request->dias;
                $contacto->save();
                $usuario_ref = User::where('referencia', $request->referencia)->get();
                if (count($usuario_ref) > 0) {
                    $saldo_favor = 0;
                    if ($request->dias == 14) {
                        $saldo_favor = env('COMISION1');
                    }
                    if ($request->dias == 28) {
                        $saldo_favor = env('COMISION2');
                    }
                    if ($request->dias == 56) {
                        $saldo_favor = env('COMISION3');
                    }
                    if ($request->dias == 84) {
                        $saldo_favor = env('COMISION4');
                    }
                    $usuario_ref->saldo = $usuario_ref->saldo + $saldo_favor;
                    $usuario_ref->save();
                }
                $mensaje = '';
                $status = 'ok';
                if ($usuario !== null) {
                    if ($usuario->deleted_at == null) {
                        if ($usuario->inicio_reto == null) {
                            $status = 'error';
                            $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
                        } else {
                            if (Carbon::parse($usuario->inicio_reto)->diffInDays(Carbon::now()) < intval($usuario->dias)) {
                                $status = 'error';
                                $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
                            }
                        }
                    }
                }
            }
        }
        return response()->json(['status' => $status, 'mensaje' => $mensaje]);
    }

    /*
        public function saveContactoTienda(Request $request)
        {
            $id = null;
            $validator = Validator::make($request->all(), [
                'email' => 'required|max:100|min:3|email',
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
            ]);
            $validator->after(function ($validator) use ($request) {
                if (ValidarCorreo::validarCorreo($request->email)) {
                    $validator->errors()->add("email", "El email debe tener formato correcto");
                }
            });
            $validator->validate();
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
                $contacto = User::withTrashed()->where("email", $email)->first();
                if ($contacto == null) {
                    $contacto = new User();
                    $contacto->email = $email;
                }
                $contacto->name = $result = preg_replace('/\d/', '', $request->nombres);
                $contacto->last_name = $request->apellidos;
                $contacto->tipo_referencia = 2;
                $contacto->deleted_at = null;
                $contacto->password = Hash::make('acton'.$contacto->name);
                $contacto->rol = 'tienda';
                $contacto->save();
                $mensaje = '';
                $status = 'ok';
                if ($usuario !== null) {
                    if ($usuario->deleted_at == null) {
                        if ($usuario->inicio_reto == null) {
                            $status = 'error';
                            $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
                        } else {
                            if (Carbon::parse($usuario->inicio_reto)->diffInDays(Carbon::now()) < intval($usuario->dias)) {
                                $status = 'error';
                                $mensaje = 'Este usuario ya pertenece al RETO ACTON.';
                            }
                        }
                    }
                }
            }
            return response()->json(['status' => $status, 'mensaje' => $mensaje]);
        }*/

    public function generarCodigo(Request $request)
    {
        $medios = MedioContacto::all();
        $ref = User::where('id', Auth::id())->get()->first();
        $usr = User::where('rol', 'entrenador')->where('codigo', $ref->referencia)->get();
        return view('configuracion.codigos', ['medios' => $medios,'codigos' => $usr]);
    }

    public function pagarTienda(Request $request)
    {
        $usr = User::where('id', $request->usuario)->get()->first();
        error_log('USER');
        error_log($usr);
        $usr->ref_tienda_pagado += $usr->saldo;
        $usr->saldo = 0;
        $usr->save();
        return response()->json(['status' => 'OK', 'mensaje' => '']);
    }


    public function mensajes(Request $request)
    {
         return view('configuracion.mensajes');
    }


    public function buscarSeguir(Request $request)
    {
        $usuarios = User::where('rol', '!=', '111');

        $amistad = Amistades::where('usuario_solicita_id', auth()->user()->id)->select('usuario_amigo_id')->get();
        $amistad_dos = Amistades::where('usuario_amigo_id', auth()->user()->id)->select('usuario_solicita_id')->get();
        $usuarios = $usuarios->where(function ($query) use ($amistad,$amistad_dos) {
            $query->whereIn('id', $amistad)
                ->orWhereIn('id', $amistad_dos);
        });
        //$amistad_me_siguen = Amistades::where('usuario_amigo_id', auth()->user()->id)->select('usuario_solicita_id')->get();
        //$usuarios = $usuarios->whereIn('id', $amistad_me_siguen);

        $usuarios = $usuarios->orderByDesc('created_at');
        $usuarios = $usuarios->select(['users.*'])->paginate(15);
        $comision = intval(env('COMISION'));
        $contactos = Contacto::whereIn('email', $usuarios->pluck('email'))->get()->keyBy('email');
        foreach ($usuarios as $usuario) {
            $usuario->dias_reto = 0;
            //$usuario->isVencido();
            if ($usuario->inicio_reto != null) {
                $usuario->dias_reto = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($usuario->inicio_reto)->startOfDay()) + 1;
            }

            $mio = Auth::id();
            $mensajes_e = MensajesDirectos::where(function($query) use ($usuario,$mio){
                $query->where('usuario_receptor_id', '=', $usuario->id);
                $query->where('usuario_emisor_id', '=', $mio);
            })->where('visto', '0')->count();

            $mensajes_r = MensajesDirectos::where(function($query) use ($usuario,$mio){
                $query->where('usuario_emisor_id', '=', $usuario->id);
                $query->where('usuario_receptor_id', '=', $mio);
            })->where('visto', '0')->count();


            $usuario->sin_leer = $mensajes_e+$mensajes_r;

            $usuario->total = $usuario->ingresados * $comision;
            $usuario->depositado = $usuario->total - $usuario->saldo;
            $usuario->pendientes = $usuario->saldo / $comision;
            $usuario->pagados = $usuario->depositado / $comision;
            $contacto = $contactos->get($usuario->email);
            $usuario->medio = $contacto == null ? '' : $contacto->medio;
            $usuario->telefono = $contacto == null ? '' : $contacto->telefono;
            //$usuario->vigente = !$usuario->vencido;
            $amistad = Amistades::where('usuario_amigo_id', $usuario->id)->where('usuario_solicita_id', Auth::id())->first();
            $usuario->amistad = 'no';
            if($amistad){
                $usuario->amistad = 'si';
            }

        }

        return $usuarios;
    }


    public function buscarSeguirMensajes(Request $request)
    {
        $usuarios = User::where('rol', '!=', '111');

        $amistad = Amistades::where('usuario_solicita_id', auth()->user()->id)->select('usuario_amigo_id')->get();
        $amistad_dos = MensajesDirectos::where('usuario_receptor_id', auth()->user()->id)->select('usuario_emisor_id')->get();
        $usuarios = $usuarios->where(function ($query) use ($amistad,$amistad_dos) {
            $query->whereIn('id', $amistad)
                ->orWhereIn('id', $amistad_dos);
        });
        //$amistad_me_siguen = Amistades::where('usuario_amigo_id', auth()->user()->id)->select('usuario_solicita_id')->get();
        //$usuarios = $usuarios->whereIn('id', $amistad_me_siguen);

        $usuarios = $usuarios->orderByDesc('created_at');
        $usuarios = $usuarios->select(['users.*'])->paginate(15);
        $comision = intval(env('COMISION'));
        $contactos = Contacto::whereIn('email', $usuarios->pluck('email'))->get()->keyBy('email');
        foreach ($usuarios as $usuario) {
            $usuario->dias_reto = 0;
            //$usuario->isVencido();
            if ($usuario->inicio_reto != null) {
                $usuario->dias_reto = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($usuario->inicio_reto)->startOfDay()) + 1;
            }

            $mio = Auth::id();
            $mensajes_e = MensajesDirectos::where(function($query) use ($usuario,$mio){
                $query->where('usuario_receptor_id', '=', $usuario->id);
                $query->where('usuario_emisor_id', '=', $mio);
            })->where('visto', '0')->count();

            $mensajes_r = MensajesDirectos::where(function($query) use ($usuario,$mio){
                $query->where('usuario_emisor_id', '=', $usuario->id);
                $query->where('usuario_receptor_id', '=', $mio);
            })->where('visto', '0')->count();


            $usuario->sin_leer = $mensajes_e+$mensajes_r;

            $usuario->total = $usuario->ingresados * $comision;
            $usuario->depositado = $usuario->total - $usuario->saldo;
            $usuario->pendientes = $usuario->saldo / $comision;
            $usuario->pagados = $usuario->depositado / $comision;
            $contacto = $contactos->get($usuario->email);
            $usuario->medio = $contacto == null ? '' : $contacto->medio;
            $usuario->telefono = $contacto == null ? '' : $contacto->telefono;
            //$usuario->vigente = !$usuario->vencido;
            $amistad = Amistades::where('usuario_amigo_id', $usuario->id)->where('usuario_solicita_id', Auth::id())->first();
            $usuario->amistad = 'no';
            if($amistad){
                $usuario->amistad = 'si';
            }

        }

        return $usuarios;
    }


    public function mensaje_directo($id, Request $request)
    {
        $mensajes = Notifications::where('notifiable_id', auth()->user()->id)->get();

        foreach ($mensajes as $a) {
            $a->read_at = \Carbon\Carbon::now();
            $a->save();
        }

        return view('configuracion.mensaje_directo', ['id' => $id]);
    }

    public function conversacion($id){
        //$mensajes = MensajesDirectos::where('usuario_emisor_id', $id)->orWhere('usuario_receptor_id', $id);
        //$mensajes = $mensajes->where('usuario_emisor_id', auth()->user()->id)->orWhere('usuario_receptor_id', auth()->user()->id)->get();

        $mio = Auth::id();
        $mensajes_e = MensajesDirectos::where(function($query) use ($id,$mio){
            $query->where('usuario_receptor_id', $id);
            $query->where('usuario_emisor_id', $mio);
        })->orWhere(function($query) use ($id,$mio){
            $query->where('usuario_receptor_id', $mio);
            $query->where('usuario_emisor_id', $id);
        })->get();

        $actualizado = $mensajes_e;

        $i = 0;
        $ilen = count( $actualizado );
        foreach ($actualizado as $a){
            $a->visto = '1';
            $a->save();
            if( ++$i == $ilen && $a->usuario_emisor_id == auth()->user()->id ){
                $a->visto = '0';
                $a->save();
            }
        }


        return $mensajes_e;
    }

    public function nuevo_mensaje($id, Request $request){
        $mensajes_directos = MensajesDirectos::create([
            'usuario_emisor_id'=> auth()->user()->id,
            'usuario_receptor_id' => $id,
            'visto' => '0',
            'mensaje' => $request->mensaje
        ]);

        $mensajes = $mensajes_directos;
        $mensajes = MensajesDirectos::where('usuario_emisor_id', $id)->orWhere('usuario_receptor_id', $id);
        $mensajes = $mensajes->where('usuario_emisor_id', auth()->user()->id)->orWhere('usuario_receptor_id', auth()->user()->id)->get();

        //$usuario = User::where('id', $id)->first();
        //$usuario->notify(new MensajeNotification($mensajes_directos));
        event(new MensajesDirectosEvent($mensajes_directos));

        return $mensajes;
    }

    public function cambiar_disponibilidad($activo, $id, Request $request){

        $video_publico = VideosPublicos::where('id', $id)->first();
        $activo_ = 1;
        if($activo == 'false'){
            $activo_ = 0;
        }
        $video_publico->activo = $activo_;
        $video_publico->save();

        return $video_publico;
    }


    public function usuarios_coach(Request $request)
    {
        $referencias = User::select(['id', 'name', 'email', 'created_at', 'num_inscripciones'])
            ->where('codigo', $request->user()->referencia)
            ->where('pagado', true)->whereNotNull('codigo')->get();
        return view('configuracion.usuarios_coach', ['referencias' => $referencias]);
    }

    public function setDia(Request $request)
    {
        $user = $request->user();
        $dias = $user->dias-($request->semana*7);
        $fecha = $request->inicio;
        $fecha = explode(' ', $fecha);
        $hoy = new Carbon($fecha[0]);
        $user->inicio_reto = $hoy->subDays($dias);
        $user->save();
        return response()->json(['status' => 'ok']);
    }

}
