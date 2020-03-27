<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Code\MedioContacto;
use App\Code\TipoEjercicio;
use App\Code\Utils;
use App\Code\Videos;
use App\Contacto;
use App\Dia;
use App\Ejercicio;
use App\Events\ProcesarVideoEvent;
use App\Notas;
use App\Serie;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ConfiguracionController extends Controller
{
    public function videos(Request $request)
    {
        $this->authorize('configurar.videos');
        $videos = collect();
        foreach (Videos::allString() as $video) {
            $videos->push(['nombre' => $video, 'src' => url('/getVideo/') . "/$video/" . rand(1, 100)]
            );
        }
        $categorias = Categoria::all();
        foreach ($categorias as $categoria) {
            $categoria->mostrar = false;
            $categoria->ejercicios = $this->getEjerciciosCategoria($categoria->nombre);
            $categoria->nueva = false;
        }
        $pendientes = $this->getVideosPendientes();

        return view('configuracion.videos', ['videos' => $videos, 'categorias' => $categorias, 'pendientes' => $pendientes]);
    }

    public function saveVideo(Request $request)
    {
        $this->authorize('configurar.videos');
        $this->validate($request, [
            'video' => 'required|mimetypes:video/mp4|file|max:332000',
        ], [
                'video.mimetypes' => 'El video es obligatorio',
                'video.mimetypes' => 'El formato debe ser .mp4',
                'video  .size' => 'El archivo debe ser menor a 300MB',
            ]
        );
        $nombre = str_replace(" ", "_", $request->nombre);
        $nombre = Utils::clearString($nombre);
        $archivoVideo = $request->video;
        $archivoVideo->storeAs('public', 'home/' . $nombre . '.mp4');
        event(new ProcesarVideoEvent("public/home", "public/optimized", "$nombre.mp4"));
        return "ok";
    }

    public function saveCategoria(Request $request){
        $this->validate($request, [
            'nombre'=>'min:2|max:20|unique:categorias,nombre'
        ],[
            'nombre.min'=>'El nombre debe tener minimo 5 caracteres',
            'nombre.max'=>'El nombre debe tener maximo 20 caracteres',
            'nombre.unique'=>'Este nombre ya se encuentra en uso',
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
        $this->validate($request,
            [
                'nota' => 'max:250',
                'gym.*.nombre' => 'required|max:50',
                'gym.*.ejercicios.*.ejercicio' => 'required|max:50',
                'gym.*.ejercicios.*.subseries.*.repeticiones' => 'max:40',
                'casa.*.ejercicios.*.subseries.*.repeticiones' => 'max:40',
                'gym.*.ejercicios.*.video' => 'required',
                'casa.*.nombre' => 'required|max:50',
                'casa.*.ejercicios.*.ejercicio' => 'required|max:50',
                'casa.*.ejercicios.*.video' => 'required',
                'cardio.*.ejercicio' => 'required|max:50',
                'cardio.*.video' => 'required',
            ],
            [
                'nota.max' => 'Debe capturar máximo 250 caracteres',
                'gym.*.nombre.required' => 'El nombre de la serie en gym es obligatorio',
                'gym.*.nombre.max' => 'Debe capturar máximo 50 caracteres en el nombre de la serie en gym',
                'gym.*.ejercicios.*.ejercicio.required' => 'El nombre del ejercicio en gym es obligatorio',
                'gym.*.ejercicios.*.subseries.*.repeticiones.max' => 'Esta repeticion debe ser maximo 40',
                'casa.*.ejercicios.*.subseries.*.repeticiones.max' => 'Esta repeticion debe ser maximo 40',
                'gym.*.ejercicios.*.ejercicio.max' => 'Debe capturar máximo 50 caracteres en el nombre del ejercicio',
                'gym.*.ejercicios.*.video.required' => 'El video del ejercicio en gym es obligatorio',
                'casa.*.nombre.required' => 'El nombre de la serie en casa es obligatorio',
                'casa.*.nombre.max' => 'Debe capturar máximo 50 caracteres en el nombre de la serie en casa',
                'casa.*.ejercicios.*.ejercicio.required' => 'El nombre del ejercicio en casa es obligatorio',
                'casa.*.ejercicios.*.video.required' => 'El video del ejercicio en casa es obligatorio',
                'cardio.*.ejercicio.required' => 'El ejercicio de cardio es obligatorio',
                'cardio.*.ejercicio.max' => 'Debe capturar máximo 50 caracteres',
                'cardio.*.video.required' => 'El video del cardio es obligatorio',
            ]);
        \DB::beginTransaction();
        $now = Carbon::now();
        $filtro = function ($datos) use ($request) { //funcion para cada with con campos similares
            $datos->where('dia_id', $request->dia)->where('genero', $request->genero)->where('objetivo', $request->objetivo);
        };
        $dia = Dia::where('dia', $request->dia)->with(['ejercicios' => $filtro, 'cardio' => $filtro, 'notas' => $filtro])->first();
        if ($dia==null){
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
            $cardioDb->video = $cardio['video'];
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
            $contactos = $contactos->where('contactos.nombres', 'like', "%$campos->nombres%")->orWhere('contactos.apellidos','like',"%$campos->nombres%");
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
}
